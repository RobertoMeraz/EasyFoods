<?php
session_start();
require_once 'funciones.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$success = '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    
    if (empty($nombre)) {
        $error = "El nombre es requerido";
    } else {
        global $conn;
        try {
            if ($action === 'add') {
                $stmt = $conn->prepare("INSERT INTO etiquetas (nombre) VALUES (?)");
                $stmt->execute([$nombre]);
                $success = "Etiqueta agregada correctamente";
                $action = 'list';
            } elseif ($action === 'edit') {
                $stmt = $conn->prepare("UPDATE etiquetas SET nombre = ? WHERE id_etiqueta = ?");
                $stmt->execute([$nombre, $id]);
                $success = "Etiqueta actualizada correctamente";
                $action = 'list';
            }
        } catch(PDOException $e) {
            $error = "Error al guardar la etiqueta: " . $e->getMessage();
        }
    }
}

// Procesar eliminación
if ($action === 'delete' && $id > 0) {
    try {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM etiquetas WHERE id_etiqueta = ?");
        $stmt->execute([$id]);
        $success = "Etiqueta eliminada correctamente";
    } catch(PDOException $e) {
        $error = "Error al eliminar la etiqueta: " . $e->getMessage();
    }
    $action = 'list';
}

// Obtener datos para edición
$etiqueta = [];
if ($action === 'edit' && $id > 0) {
    try {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM etiquetas WHERE id_etiqueta = ?");
        $stmt->execute([$id]);
        $etiqueta = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$etiqueta) {
            $error = "Etiqueta no encontrada";
            $action = 'list';
        }
    } catch(PDOException $e) {
        $error = "Error al obtener etiqueta: " . $e->getMessage();
        $action = 'list';
    }
}

// Obtener todas las etiquetas
try {
    global $conn;
    $stmt = $conn->query("SELECT id_etiqueta as id, nombre FROM etiquetas");
    $etiquetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener etiquetas: " . $e->getMessage();
    $etiquetas = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Etiquetas - Easy Foods Admin</title>
    <link rel="stylesheet" href="estilos/estilo_admin.css">
</head>
<body>
    <div class="admin-container">
    <?php include 'header.php'; ?>    
    <!-- Sidebar -->
        <?php include 'admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="admin-main">
            <h1>Gestión de Etiquetas</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <?php if ($action === 'list'): ?>
                <div class="admin-actions">
                    <a href="admin_etiquetas.php?action=add" class="btn-add">Agregar Etiqueta</a>
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
                        <?php foreach ($etiquetas as $et): ?>
                        <tr>
                            <td><?= htmlspecialchars($et['id']) ?></td>
                            <td><?= htmlspecialchars($et['nombre']) ?></td>
                            <td>
                                <a href="admin_etiquetas.php?action=edit&id=<?= htmlspecialchars($et['id']) ?>" class="btn-edit">Editar</a>
                                <a href="admin_etiquetas.php?action=delete&id=<?= htmlspecialchars($et['id']) ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de eliminar esta etiqueta?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
            <?php elseif ($action === 'add' || $action === 'edit'): ?>
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($etiqueta['nombre'] ?? '') ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Guardar</button>
                        <a href="admin_etiquetas.php" class="btn-cancel">Cancelar</a>
                    </div>
                </form>
            <?php endif; ?>
        </main>
    </div>
</body>
</html> 