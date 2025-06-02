<?php
session_start();
require_once 'funciones.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}

// Obtener todas las recetas (sin dificultad)
try {
    global $conn;
    $stmt = $conn->query("
        SELECT 
            r.id_receta, 
            r.titulo, 
            c.nombre as categoria, 
            r.porciones, 
            r.fecha_creacion,
            r.imagen,
            r.tiempo_preparacion,
            r.calorias
        FROM recetas r
        LEFT JOIN categorias c ON r.categoria_id = c.id
        ORDER BY r.fecha_creacion DESC
    ");
    $recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener recetas: " . $e->getMessage();
    $recetas = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Recetas - Easy Foods Admin</title>
    <link rel="stylesheet" href="estilos/estilo_admin.css">
    <style>
        .receta-imagen {
            max-width: 100px;
            max-height: 60px;
            object-fit: cover;
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
            <h1>Gestión de Recetas</h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <div class="admin-actions">
                <a href="admin_recetas_add.php" class="btn-add">Agregar Receta</a>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Porciones</th>
                        <th>Tiempo (min)</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recetas)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay recetas registradas</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recetas as $receta): ?>
                        <tr>
                            <td><?= htmlspecialchars($receta['id_receta']) ?></td>
                            <td>
                                <?php if (!empty($receta['imagen'])): ?>
                                    <img src="<?= htmlspecialchars($receta['imagen']) ?>" alt="Imagen receta" class="receta-imagen">
                                <?php else: ?>
                                    <span>Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($receta['titulo']) ?></td>
                            <td><?= htmlspecialchars($receta['categoria'] ?? 'Sin categoría') ?></td>
                            <td><?= htmlspecialchars($receta['porciones']) ?></td>
                            <td><?= htmlspecialchars($receta['tiempo_preparacion']) ?></td>
                            <td><?= date('d/m/Y', strtotime($receta['fecha_creacion'])) ?></td>
                            <td>
                                <a href="admin_recetas_edit.php?id=<?= htmlspecialchars($receta['id_receta']) ?>" class="btn-edit">Editar</a>
                                <a href="admin_recetas_delete.php?id=<?= htmlspecialchars($receta['id_receta']) ?>" class="btn-delete" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>