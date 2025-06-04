<?php
session_start();
require_once 'funciones.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}

// Obtener categorías para el dropdown
try {
    global $conn;
    $stmt = $conn->query("SELECT id, nombre FROM categorias ORDER BY nombre");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener categorías: " . $e->getMessage();
    $categorias = [];
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? ''; 
    $tiempo_preparacion = $_POST['tiempo_preparacion'] ?? '';
    $calorias = $_POST['calorias'] ?? '';
    $categoria_id = $_POST['categoria_id'] ?? '';
    $dificultad = $_POST['dificultad'] ?? 'facil';
    $porciones = $_POST['porciones'] ?? 1;
    $usuario_id = $_SESSION['user_id'];

    // Manejo de la imagen
    $imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/recetas/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetPath)) {
            $imagen = $targetPath;
        }
    }

    try {
        $stmt = $conn->prepare("
            INSERT INTO recetas (
                titulo, 
                descripcion, 
                tiempo_preparacion, 
                calorias, 
                imagen, 
                categoria_id, 
                usuario_id, 
                dificultad, 
                porciones
            ) VALUES (
                :titulo, 
                :description, 
                :tiempo_preparacion, 
                :calorias, 
                :imagen, 
                :categoria_id, 
                :usuario_id, 
                :dificultad, 
                :porciones
            )
        ");

        $stmt->execute([
            ':titulo' => $titulo,
            ':description' => $description,
            ':tiempo_preparacion' => $tiempo_preparacion,
            ':calorias' => $calorias,
            ':imagen' => $imagen,
            ':categoria_id' => $categoria_id,
            ':usuario_id' => $usuario_id,
            ':dificultad' => $dificultad,
            ':porciones' => $porciones
        ]);

        $receta_id = $conn->lastInsertId();
        $_SESSION['success_message'] = "Receta agregada exitosamente!";
        header("Location: admin_recetas_edit.php?id=$receta_id");
        exit;

    } catch(PDOException $e) {
        $error = "Error al agregar receta: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Receta - Easy Foods Admin</title>
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
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include 'header.php'; ?>
        <!-- Sidebar -->
        <?php include 'admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="admin-main">
            <h1>Agregar Nueva Receta</h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" required maxlength="100">
                </div>
                
                <div class="form-group">
                    <label for="description">Descripción:</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="tiempo_preparacion">Tiempo de preparación (minutos):</label>
                    <input type="number" id="tiempo_preparacion" name="tiempo_preparacion" min="1">
                </div>
                
                <div class="form-group">
                    <label for="calorias">Calorías:</label>
                    <input type="number" id="calorias" name="calorias" min="1">
                </div>
                
                <div class="form-group">
                    <label for="porciones">Porciones:</label>
                    <input type="number" id="porciones" name="porciones" min="1" value="1">
                </div>
                
                <div class="form-group">
                    <label for="categoria_id">Categoría:</label>
                    <select id="categoria_id" name="categoria_id">
                        <option value="">Seleccione una categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria['id']) ?>">
                                <?= htmlspecialchars($categoria['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="dificultad">Dificultad:</label>
                    <select id="dificultad" name="dificultad">
                        <option value="facil">Fácil</option>
                        <option value="medio">Medio</option>
                        <option value="dificil">Difícil</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn-submit">Guardar Receta</button>
                    <a href="admin_recetas.php" class="btn-cancel">Cancelar</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>