<?php
session_start();
require_once 'funciones.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}

// Obtener el ID de la receta a eliminar
$receta_id = $_GET['id'] ?? 0;

if (!$receta_id) {
    $_SESSION['error_message'] = "ID de receta no proporcionado";
    header('Location: admin_recetas.php');
    exit;
}

// Obtener información de la receta para eliminar su imagen
try {
    global $conn;
    $stmt = $conn->prepare("SELECT imagen FROM recetas WHERE id_receta = ?");
    $stmt->execute([$receta_id]);
    $receta = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$receta) {
        $_SESSION['error_message'] = "Receta no encontrada";
        header('Location: admin_recetas.php');
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['error_message'] = "Error al obtener receta: " . $e->getMessage();
    header('Location: admin_recetas.php');
    exit;
}

// Procesar la eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Eliminar relaciones primero para evitar errores de clave foránea
        $conn->beginTransaction();
        
        // Eliminar de receta_etiquetas
        $conn->prepare("DELETE FROM receta_etiqueta WHERE receta_id = ?")->execute([$receta_id]);
        
        // Eliminar de receta_ingredientes
        $conn->prepare("DELETE FROM receta_ingrediente WHERE receta_id = ?")->execute([$receta_id]);
        
        // Eliminar de favoritos
        $conn->prepare("DELETE FROM favoritos WHERE receta_id = ?")->execute([$receta_id]);
        
        // Eliminar de comentarios
        $conn->prepare("DELETE FROM comentarios WHERE receta_id = ?")->execute([$receta_id]);
        
        // Eliminar de actividad_usuario (si existe)
        try {
            $conn->prepare("DELETE FROM actividad_usuarios WHERE receta_id = ?")->execute([$receta_id]);
        } catch (PDOException $e) {
            // Ignorar si la tabla no existe
        }
        
        // Finalmente eliminar la receta
        $stmt = $conn->prepare("DELETE FROM recetas WHERE id_receta = ?");
        $stmt->execute([$receta_id]);
        
        // Eliminar la imagen asociada si existe
        if (!empty($receta['imagen']) && file_exists($receta['imagen'])) {
            unlink($receta['imagen']);
        }
        
        $conn->commit();
        
        $_SESSION['success_message'] = "Receta eliminada exitosamente";
        header('Location: admin_recetas.php');
        exit;
        
    } catch(PDOException $e) {
        $conn->rollBack();
        $_SESSION['error_message'] = "Error al eliminar receta: " . $e->getMessage();
        header("Location: admin_recetas_edit.php?id=$receta_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Receta - Easy Foods Admin</title>
    <link rel="stylesheet" href="estilos/estilo_admin.css">
    <style>
        .delete-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .delete-message {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: #333;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
        }
        .btn-confirm {
            background-color: #d9534f;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-confirm:hover {
            background-color: #c9302c;
        }
        .btn-cancel {
            background-color: #5bc0de;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-cancel:hover {
            background-color: #46b8da;
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
            <div class="delete-container">
                <h1>Confirmar Eliminación</h1>
                
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                
                <p class="delete-message">
                    ¿Estás seguro que deseas eliminar esta receta permanentemente? 
                    Esta acción también eliminará todos los ingredientes, etiquetas, comentarios 
                    y favoritos asociados a esta receta.
                </p>
                
                <form method="POST">
                    <div class="btn-group">
                        <button type="submit" class="btn-confirm">Sí, Eliminar</button>
                        <a href="admin_recetas_edit.php?id=<?= htmlspecialchars($receta_id) ?>" class="btn-cancel">Cancelar</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>