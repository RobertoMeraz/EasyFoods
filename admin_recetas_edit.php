<?php
session_start();
require_once 'funciones.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}

// Obtener el ID de la receta a editar
$receta_id = $_GET['id'] ?? 0;

// Obtener categorías para el dropdown
try {
    global $conn;
    $stmt = $conn->query("SELECT id, nombre FROM categorias ORDER BY nombre");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener categorías: " . $e->getMessage();
    $categorias = [];
}

// Obtener datos actuales de la receta
try {
    $stmt = $conn->prepare("SELECT * FROM recetas WHERE id_receta = ?");
    $stmt->execute([$receta_id]);
    $receta = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$receta) {
        $_SESSION['error_message'] = "Receta no encontrada";
        header('Location: admin_recetas.php');
        exit;
    }
} catch(PDOException $e) {
    $error = "Error al obtener receta: " . $e->getMessage();
    $receta = [];
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $tiempo_preparacion = $_POST['tiempo_preparacion'] ?? null;
    $calorias = $_POST['calorias'] ?? null;
    $categoria_id = $_POST['categoria_id'] ?? null;
    $dificultad = $_POST['dificultad'] ?? 'facil';
    $porciones = $_POST['porciones'] ?? 1;

    // Manejo de la imagen
    $imagen = $receta['imagen']; // Mantener la imagen actual por defecto
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/recetas/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetPath)) {
            // Eliminar la imagen anterior si existe
            if (!empty($receta['imagen']) && file_exists($receta['imagen'])) {
                unlink($receta['imagen']);
            }
            $imagen = $targetPath;
        }
    }

    try {
        $stmt = $conn->prepare("
            UPDATE recetas SET
                titulo = :titulo,
                descripcion = :descripcion,
                tiempo_preparacion = :tiempo_preparacion,
                calorias = :calorias,
                imagen = :imagen,
                categoria_id = :categoria_id,
                dificultad = :dificultad,
                porciones = :porciones
            WHERE id_receta = :id_receta
        ");

        $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':tiempo_preparacion' => $tiempo_preparacion,
            ':calorias' => $calorias,
            ':imagen' => $imagen,
            ':categoria_id' => $categoria_id,
            ':dificultad' => $dificultad,
            ':porciones' => $porciones,
            ':id_receta' => $receta_id
        ]);

        $_SESSION['success_message'] = "Receta actualizada exitosamente!";
        header("Location: admin_recetas_edit.php?id=$receta_id");
        exit;

    } catch(PDOException $e) {
        $error = "Error al actualizar receta: " . $e->getMessage();
    }
    
    // Volver a cargar los datos de la receta después de la actualización
    $stmt = $conn->prepare("SELECT * FROM recetas WHERE id_receta = ?");
    $stmt->execute([$receta_id]);
    $receta = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Receta - Easy Foods Admin</title>
    <link rel="stylesheet" href="estilos/estilo_admin.css">
    <style>
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        textarea {
            min-height: 100px;
        }
        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #45a049;
        }
        .current-image {
            max-width: 200px;
            max-height: 150px;
            margin-top: 0.5rem;
            display: block;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include 'admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="admin-main">
            <h1>Editar Receta: <?= htmlspecialchars($receta['titulo'] ?? '') ?></h1>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success_message']) ?></div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" required maxlength="100" 
                           value="<?= htmlspecialchars($receta['titulo'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion"><?= htmlspecialchars($receta['descripcion'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="tiempo_preparacion">Tiempo de preparación (minutos):</label>
                    <input type="number" id="tiempo_preparacion" name="tiempo_preparacion" min="1"
                           value="<?= htmlspecialchars($receta['tiempo_preparacion'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="calorias">Calorías:</label>
                    <input type="number" id="calorias" name="calorias" min="1"
                           value="<?= htmlspecialchars($receta['calorias'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="porciones">Porciones:</label>
                    <input type="number" id="porciones" name="porciones" min="1"
                           value="<?= htmlspecialchars($receta['porciones'] ?? 1) ?>">
                </div>
                
                <div class="form-group">
                    <label for="categoria_id">Categoría:</label>
                    <select id="categoria_id" name="categoria_id">
                        <option value="">Sin categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria['id']) ?>"
                                <?= ($categoria['id'] == ($receta['categoria_id'] ?? '')) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="dificultad">Dificultad:</label>
                    <select id="dificultad" name="dificultad">
                        <option value="facil" <?= ($receta['dificultad'] ?? 'facil') == 'facil' ? 'selected' : '' ?>>Fácil</option>
                        <option value="medio" <?= ($receta['dificultad'] ?? 'facil') == 'medio' ? 'selected' : '' ?>>Medio</option>
                        <option value="dificil" <?= ($receta['dificultad'] ?? 'facil') == 'dificil' ? 'selected' : '' ?>>Difícil</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                    <?php if (!empty($receta['imagen'])): ?>
                        <p>Imagen actual:</p>
                        <img src="<?= htmlspecialchars($receta['imagen']) ?>" alt="Imagen actual de la receta" class="current-image">
                        <label>
                            <input type="checkbox" name="eliminar_imagen" value="1"> Eliminar imagen actual
                        </label>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn-submit">Guardar Cambios</button>
                    <a href="admin_recetas.php" class="btn-cancel">Cancelar</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>