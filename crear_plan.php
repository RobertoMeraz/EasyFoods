<?php
// crear_plan.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'conexion.php';
require_once 'funciones.php'; // Para isLoggedIn() y otras que puedas necesitar

global $conn; // Para la conexión PDO

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$errores = [];
$mensaje_exito = '';

// Variables para repoblar el formulario en caso de error
$nombre_plan_form = '';
$descripcion_plan_form = '';
$selected_recetas_form = [];

// 1. Obtener las recetas favoritas del usuario para mostrarlas en el formulario
$recetas_favoritas = [];
try {
    $stmt_fav = $conn->prepare("
        SELECT r.id_receta, r.titulo, r.imagen 
        FROM recetas r
        JOIN favoritos f ON r.id_receta = f.receta_id
        WHERE f.usuario_id = :usuario_id
        ORDER BY r.titulo ASC
    ");
    $stmt_fav->bindParam(':usuario_id', $user_id, PDO::PARAM_INT);
    $stmt_fav->execute();
    $recetas_favoritas = $stmt_fav->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errores[] = "Error al cargar tus recetas favoritas: " . $e->getMessage();
    error_log("Error cargando favoritos para plan (Usuario: $user_id): " . $e->getMessage());
}


// 2. Procesar el formulario cuando se envía (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_plan_form = trim($_POST['nombre_plan'] ?? '');
    $descripcion_plan_form = trim($_POST['descripcion_plan'] ?? '');
    $selected_recetas_form = $_POST['selected_recetas'] ?? []; // Array de IDs de recetas seleccionadas

    // Validaciones
    if (empty($nombre_plan_form)) {
        $errores[] = "El nombre del plan es obligatorio.";
    }
    if (count($selected_recetas_form) === 0) {
        $errores[] = "Debes seleccionar al menos una receta para incluir en el plan.";
    } else {
        // Validar que todos los IDs de recetas sean enteros
        foreach ($selected_recetas_form as $rec_id) {
            if (!filter_var($rec_id, FILTER_VALIDATE_INT)) {
                $errores[] = "Se detectó una selección de receta inválida.";
                break; 
            }
        }
    }

    if (empty($errores)) {
        try {
            $conn->beginTransaction(); // Iniciar transacción

            // 2a. Insertar en lista_planes
            $stmt_plan = $conn->prepare("
                INSERT INTO lista_planes (usuario_id, nombre_plan, descripcion_plan, fecha_creacion_plan) 
                VALUES (:usuario_id, :nombre_plan, :descripcion_plan, NOW())
            ");
            $stmt_plan->bindParam(':usuario_id', $user_id, PDO::PARAM_INT);
            $stmt_plan->bindParam(':nombre_plan', $nombre_plan_form);
            $stmt_plan->bindParam(':descripcion_plan', $descripcion_plan_form); // PDO maneja NULL si está vacío y la columna permite NULL
            $stmt_plan->execute();

            $id_lista_plan_nuevo = $conn->lastInsertId(); // Obtener el ID del plan recién creado

            // 2b. Insertar en plan_contiene_recetas
            if ($id_lista_plan_nuevo) {
                $stmt_plan_receta = $conn->prepare("
                    INSERT INTO plan_contiene_recetas (lista_plan_id, receta_id) 
                    VALUES (:lista_plan_id, :receta_id)
                ");
                foreach ($selected_recetas_form as $receta_id_seleccionada) {
                    $stmt_plan_receta->bindParam(':lista_plan_id', $id_lista_plan_nuevo, PDO::PARAM_INT);
                    $stmt_plan_receta->bindParam(':receta_id', $receta_id_seleccionada, PDO::PARAM_INT);
                    $stmt_plan_receta->execute();
                }
                $conn->commit(); // Confirmar transacción
                $_SESSION['mensaje_exito_plan'] = "¡Plan de comida '".htmlspecialchars($nombre_plan_form)."' creado exitosamente!";
                // Redirigir a una página de "Mis Planes" o al detalle del plan recién creado
                header('Location: perfil.php'); // Asumiendo que tendrás una página mis_planes.php
                exit;

            } else {
                $conn->rollBack(); // Revertir si no se pudo obtener el ID del plan
                $errores[] = "Error al crear el plan: no se pudo obtener el ID del nuevo plan.";
            }

        } catch (PDOException $e) {
            $conn->rollBack(); // Revertir transacción en caso de error
            $errores[] = "Error al guardar el plan en la base de datos: " . $e->getMessage();
            error_log("Error creando plan de comida (Usuario: $user_id): " . $e->getMessage());
        }
    }
}

$base_url = '/'; // Ajusta según tu estructura
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Plan de Comida - Easy Foods</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_formularios.css"> 
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_header.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; color: #333; line-height: 1.6; margin:0; }
        .container-plan { max-width: 800px; margin: 30px auto; padding: 25px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #4CAF50; margin-bottom: 25px; font-size: 1.8em; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }
        .form-group textarea { min-height: 100px; resize: vertical; }

        .favorites-list { margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; }
        .favorites-list h2 { font-size: 1.4em; color: #333; margin-bottom: 15px; }
        .favorite-item { display: flex; align-items: center; margin-bottom: 15px; padding: 10px; background-color: #fff; border: 1px solid #e0e0e0; border-radius: 5px; }
        .favorite-item input[type="checkbox"] { margin-right: 15px; transform: scale(1.2); }
        .favorite-item img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; margin-right: 15px; }
        .favorite-item label { font-weight: normal; cursor: pointer; display: flex; align-items: center; flex-grow: 1; }
        .no-favorites { text-align: center; color: #777; padding: 20px; background-color: #f0f0f0; border-radius: 5px; }

        .btn-submit { display: inline-block; background-color: #4CAF50; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 1.1em; transition: background-color 0.3s ease; }
        .btn-submit:hover { background-color: #45a049; }
        .form-actions { text-align: center; margin-top: 30px; }

        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 5px; font-size: 0.9em;}
        .alert-danger { color: #a94442; background-color: #f2dede; border-color: #ebccd1; }
        .alert-danger ul { margin-top: 0; margin-bottom: 0; padding-left: 20px; list-style-position: inside; }
        /* No necesitas .alert-success si rediriges con mensaje de sesión */
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container-plan">
        <h1><i class="fas fa-calendar-plus"></i> Crear Nuevo Plan de Comida</h1>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <strong>¡Error!</strong> Por favor, corrige los siguientes problemas:
                <ul>
                    <?php foreach ($errores as $error_item): ?>
                        <li><?= htmlspecialchars($error_item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php // Mensaje de éxito de sesión (si vienes redirigido de otra acción en esta página, por ejemplo)
        if (isset($_SESSION['mensaje_exito_crear_plan'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['mensaje_exito_crear_plan']) . '</div>';
            unset($_SESSION['mensaje_exito_crear_plan']);
        }
        ?>

        <form action="crear_plan.php" method="POST">
            <div class="form-group">
                <label for="nombre_plan">Nombre del Plan:</label>
                <input type="text" id="nombre_plan" name="nombre_plan" value="<?= htmlspecialchars($nombre_plan_form) ?>" required>
            </div>

            <div class="form-group">
                <label for="descripcion_plan">Descripción (opcional):</label>
                <textarea id="descripcion_plan" name="descripcion_plan" rows="4"><?= htmlspecialchars($descripcion_plan_form) ?></textarea>
            </div>

            <div class="favorites-list">
                <h2><i class=></i> Selecciona Recetas de tus Favoritos</h2>
                <?php if (empty($recetas_favoritas)): ?>
                    <p class="no-favorites">
                        No tienes recetas favoritas para añadir. 
                        <a href="recetas.php">Explora recetas</a> y marca algunas como favoritas primero.
                    </p>
                <?php else: ?>
                    <?php foreach ($recetas_favoritas as $fav_receta): ?>
                        <div class="favorite-item">
                            <input type="checkbox" 
                                   name="selected_recetas[]" 
                                   value="<?= htmlspecialchars($fav_receta['id_receta']) ?>" 
                                   id="receta_<?= htmlspecialchars($fav_receta['id_receta']) ?>"
                                   <?= in_array($fav_receta['id_receta'], $selected_recetas_form) ? 'checked' : '' ?>
                                   >
                            <label for="receta_<?= htmlspecialchars($fav_receta['id_receta']) ?>">
                                <?php if(!empty($fav_receta['imagen'])): ?>
                                    <img src="<?= htmlspecialchars($fav_receta['imagen']) ?>" alt="<?= htmlspecialchars($fav_receta['titulo']) ?>">
                                <?php else: ?>
                                    <img src="imagenes/receta_default.jpg" alt="Imagen por defecto">
                                <?php endif; ?>
                                <?= htmlspecialchars($fav_receta['titulo']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit" <?= empty($recetas_favoritas) ? 'disabled title="Añade recetas a favoritos primero"' : '' ?>>
                    <i class="fas fa-save"></i> Crear Plan
                </button>
            </div>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>