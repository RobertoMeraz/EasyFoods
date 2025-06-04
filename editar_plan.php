<?php
// editar_plan.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'conexion.php';
require_once 'funciones.php';

global $conn;

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$id_lista_plan_a_editar = filter_input(INPUT_GET, 'id_plan', FILTER_VALIDATE_INT);

$errores = [];
$mensaje_exito = '';

$nombre_plan_form = '';
$descripcion_plan_form = '';
$recetas_actuales_en_plan_ids = []; // IDs de recetas actualmente en el plan
$recetas_favoritas_usuario = []; // Todas las favoritas del usuario para seleccionar

if (!$id_lista_plan_a_editar) {
    $_SESSION['mensaje_error_perfil'] = "ID de plan no válido para editar.";
    header('Location: perfil.php#mis-planes');
    exit;
}

// --- Cargar datos del plan y sus recetas actuales (para GET y para repoblar en POST con error) ---
try {
    $stmt_plan_actual = $conn->prepare("SELECT * FROM lista_planes WHERE id_lista_plan = :id_lista_plan AND usuario_id = :usuario_id");
    $stmt_plan_actual->bindParam(':id_lista_plan', $id_lista_plan_a_editar, PDO::PARAM_INT);
    $stmt_plan_actual->bindParam(':usuario_id', $user_id, PDO::PARAM_INT);
    $stmt_plan_actual->execute();
    $plan_actual_data = $stmt_plan_actual->fetch(PDO::FETCH_ASSOC);

    if (!$plan_actual_data) {
        $_SESSION['mensaje_error_perfil'] = "Plan no encontrado o no tienes permiso para editarlo.";
        header('Location: perfil.php#mis-planes');
        exit;
    }
    $nombre_plan_form = $plan_actual_data['nombre_plan'];
    $descripcion_plan_form = $plan_actual_data['descripcion_plan'];

    // Obtener IDs de recetas actualmente en este plan
    $stmt_recetas_actuales = $conn->prepare("SELECT receta_id FROM plan_contiene_recetas WHERE lista_plan_id = :id_lista_plan");
    $stmt_recetas_actuales->bindParam(':id_lista_plan', $id_lista_plan_a_editar, PDO::PARAM_INT);
    $stmt_recetas_actuales->execute();
    $recetas_actuales_en_plan_ids = $stmt_recetas_actuales->fetchAll(PDO::FETCH_COLUMN, 0); // Array de IDs

    // Obtener todas las recetas favoritas del usuario (para la selección)
    $stmt_fav = $conn->prepare("
        SELECT r.id_receta, r.titulo, r.imagen 
        FROM recetas r
        JOIN favoritos f ON r.id_receta = f.receta_id
        WHERE f.usuario_id = :usuario_id
        ORDER BY r.titulo ASC
    ");
    $stmt_fav->bindParam(':usuario_id', $user_id, PDO::PARAM_INT);
    $stmt_fav->execute();
    $recetas_favoritas_usuario = $stmt_fav->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $errores[] = "Error al cargar datos para la edición del plan: " . $e->getMessage();
    error_log("Error cargando plan para editar (PlanID: $id_lista_plan_a_editar, Usuario: $user_id): " . $e->getMessage());
}

// --- Procesar el formulario POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_plan_form = trim($_POST['nombre_plan'] ?? '');
    $descripcion_plan_form = trim($_POST['descripcion_plan'] ?? '');
    $selected_recetas_form = $_POST['selected_recetas'] ?? []; // Array de IDs de recetas seleccionadas

    // Validaciones
    if (empty($nombre_plan_form)) {
        $errores[] = "El nombre del plan es obligatorio.";
    }
    if (count($selected_recetas_form) === 0) {
        $errores[] = "Debes seleccionar al menos una receta para el plan.";
    } else {
        foreach ($selected_recetas_form as $rec_id) {
            if (!filter_var($rec_id, FILTER_VALIDATE_INT)) {
                $errores[] = "Selección de receta inválida detectada.";
                break;
            }
        }
    }

    if (empty($errores)) {
        try {
            $conn->beginTransaction();

            // 1. Actualizar lista_planes (nombre, descripción)
            $stmt_update_plan = $conn->prepare("
                UPDATE lista_planes 
                SET nombre_plan = :nombre_plan, descripcion_plan = :descripcion_plan 
                WHERE id_lista_plan = :id_lista_plan AND usuario_id = :usuario_id 
            ");
            $stmt_update_plan->bindParam(':nombre_plan', $nombre_plan_form);
            $stmt_update_plan->bindParam(':descripcion_plan', $descripcion_plan_form);
            $stmt_update_plan->bindParam(':id_lista_plan', $id_lista_plan_a_editar, PDO::PARAM_INT);
            $stmt_update_plan->bindParam(':usuario_id', $user_id, PDO::PARAM_INT);
            $stmt_update_plan->execute();

            // 2. Actualizar plan_contiene_recetas:
            //    Opción simple: borrar todas las existentes para este plan y luego reinsertar las seleccionadas.
            $stmt_delete_old_recipes = $conn->prepare("DELETE FROM plan_contiene_recetas WHERE lista_plan_id = :id_lista_plan");
            $stmt_delete_old_recipes->bindParam(':id_lista_plan', $id_lista_plan_a_editar, PDO::PARAM_INT);
            $stmt_delete_old_recipes->execute();

            // Reinsertar las recetas seleccionadas
            $stmt_insert_new_recipes = $conn->prepare("
                INSERT INTO plan_contiene_recetas (lista_plan_id, receta_id) 
                VALUES (:lista_plan_id, :receta_id)
            ");
            foreach ($selected_recetas_form as $receta_id_seleccionada) {
                $stmt_insert_new_recipes->bindParam(':lista_plan_id', $id_lista_plan_a_editar, PDO::PARAM_INT);
                $stmt_insert_new_recipes->bindParam(':receta_id', $receta_id_seleccionada, PDO::PARAM_INT);
                $stmt_insert_new_recipes->execute();
            }

            $conn->commit();
            $_SESSION['mensaje_exito_perfil'] = "Plan de comida '".htmlspecialchars($nombre_plan_form)."' actualizado exitosamente!";
            header('Location: ver_plan.php?id_plan=' . $id_lista_plan_a_editar); // Redirigir a ver el plan actualizado
            exit;

        } catch (PDOException $e) {
            $conn->rollBack();
            $errores[] = "Error al actualizar el plan en la base de datos: " . $e->getMessage();
            error_log("Error editando plan de comida (PlanID: $id_lista_plan_a_editar, Usuario: $user_id): " . $e->getMessage());
        }
    }
    // Si hay errores, $recetas_actuales_en_plan_ids ya se cargó antes del POST,
    // y $selected_recetas_form tiene los valores del POST para repoblar checkboxes.
    // Necesitamos asegurar que los checkboxes usen $selected_recetas_form para repoblar si POST falló.
    if(!empty($errores)){ // Si hubo errores en POST, los checkboxes deben reflejar el último intento de envío
        $recetas_actuales_en_plan_ids = $selected_recetas_form;
    }

}


$base_url = '/'; // Ajusta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Plan de Comida - Easy Foods</title>
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
        .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 1em; }
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
        .btn-cancel { display: inline-block; text-decoration: none; background-color: #777; color: white; padding: 12px 20px; border-radius:5px; margin-left:10px; }
        .btn-cancel:hover { background-color: #666;}
        .form-actions { text-align: center; margin-top: 30px; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 5px; font-size: 0.9em;}
        .alert-danger { color: #a94442; background-color: #f2dede; border-color: #ebccd1; }
        .alert-danger ul { margin-top: 0; margin-bottom: 0; padding-left: 20px; list-style-position: inside; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container-plan">
        <h1><i class="fas fa-edit"></i> Editar Plan de Comida</h1>

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
        
        <?php // El mensaje de éxito se mostrará en ver_plan.php o perfil.php después de la redirección
        if (isset($_SESSION['mensaje_exito_editar_plan'])) { // Si se decide no redirigir y mostrar aquí
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['mensaje_exito_editar_plan']) . '</div>';
            unset($_SESSION['mensaje_exito_editar_plan']);
        }
        ?>

        <?php if ($plan_actual_data): // Solo mostrar formulario si el plan se cargó ?>
        <form action="editar_plan.php?id_plan=<?= htmlspecialchars($id_lista_plan_a_editar) ?>" method="POST">
            <input type="hidden" name="id_lista_plan" value="<?= htmlspecialchars($id_lista_plan_a_editar) ?>">

            <div class="form-group">
                <label for="nombre_plan">Nombre del Plan:</label>
                <input type="text" id="nombre_plan" name="nombre_plan" value="<?= htmlspecialchars($nombre_plan_form) ?>" required>
            </div>

            <div class="form-group">
                <label for="descripcion_plan">Descripción (opcional):</label>
                <textarea id="descripcion_plan" name="descripcion_plan" rows="4"><?= htmlspecialchars($descripcion_plan_form) ?></textarea>
            </div>

            <div class="favorites-list">
                <h2><i class="fas fa-heart"></i> Modifica las Recetas Incluidas (de tus Favoritos)</h2>
                <?php if (empty($recetas_favoritas_usuario)): ?>
                    <p class="no-favorites">
                        No tienes recetas favoritas para seleccionar. 
                        <a href="recetas.php">Explora recetas</a> y marca algunas como favoritas.
                    </p>
                <?php else: ?>
                    <?php foreach ($recetas_favoritas_usuario as $fav_receta): ?>
                        <div class="favorite-item">
                            <input type="checkbox" 
                                   name="selected_recetas[]" 
                                   value="<?= htmlspecialchars($fav_receta['id_receta']) ?>" 
                                   id="receta_<?= htmlspecialchars($fav_receta['id_receta']) ?>"
                                   <?php // Marcar si la receta está en las actualmente seleccionadas para este plan
                                        // Si hubo un POST con errores, $recetas_actuales_en_plan_ids se actualizó con $selected_recetas_form
                                        if (in_array($fav_receta['id_receta'], $recetas_actuales_en_plan_ids)) { echo 'checked'; } 
                                   ?>
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
                <button type="submit" class="btn-submit" <?= empty($recetas_favoritas_usuario) ? 'disabled title="Necesitas tener recetas favoritas para seleccionar"' : '' ?>>
                    <i class="fas fa-save"></i> Guardar Cambios en el Plan
                </button>
                <a href="ver_plan.php?id_plan=<?= htmlspecialchars($id_lista_plan_a_editar) ?>" class="btn-cancel">Cancelar</a>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>