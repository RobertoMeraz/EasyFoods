<?php
session_start();
require_once 'funciones.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$error = '';
$success = '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    
    if (empty($nombre)) {
        $error = "El nombre es requerido";
    } else {
        global $conn;
        try {
            if ($action === 'add') {
                $stmt = $conn->prepare("INSERT INTO categorias (nombre) VALUES (?)");
                $stmt->execute([$nombre]);
                $success = "Categoría agregada correctamente";
                $action = 'list';
            } else {
                $stmt = $conn->prepare("UPDATE categorias SET nombre = ? WHERE id = ?");
                $stmt->execute([$nombre, $id]);
                $success = "Categoría actualizada correctamente";
                $action = 'list';
            }
        } catch(PDOException $e) {
            $error = "Error al guardar la categoría: " . $e->getMessage();
        }
    }
}

// Procesar eliminación
if ($action === 'delete') {
    try {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Categoría eliminada correctamente";
    } catch(PDOException $e) {
        $error = "Error al eliminar la categoría: " . $e->getMessage();
    }
    $action = 'list';
}

// Obtener datos para edición
$categoria = [];
if ($action === 'edit') {
    try {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$categoria) {
            $error = "Categoría no encontrada";
            $action = 'list';
        }
    } catch(PDOException $e) {
        $error = "Error al obtener categoría: " . $e->getMessage();
        $action = 'list';
    }
}

// Obtener todas las categorías
$categorias = getCategorias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías - Easy Foods Admin</title>
    <link rel="stylesheet" href="estilos/estilo_admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'header.php'; ?> 
        <!-- Sidebar -->
        <?php include 'admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="admin-main">
            <h1>Gestión de Categorías</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <?php if ($action === 'list'): ?>
                <div class="admin-actions">
                    <a href="admin_categorias.php?action=add" class="btn-add">Agregar Categoría</a>
                </div>
                
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td><?= $cat['id'] ?></td>
                            <td><?= htmlspecialchars($cat['nombre']) ?></td>
                            <td>
                                <a href="admin_categorias.php?action=edit&id=<?= $cat['id'] ?>" class="btn-edit">Editar</a>
                                <a href="admin_categorias.php?action=delete&id=<?= $cat['id'] ?>" class="btn-delete" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
            <?php elseif ($action === 'add' || $action === 'edit'): ?>
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($categoria['nombre'] ?? '') ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Guardar</button>
                        <a href="admin_categorias.php" class="btn-cancel">Cancelar</a>
                    </div>
                </form>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>