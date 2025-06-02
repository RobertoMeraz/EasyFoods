<?php
session_start();
require_once 'funciones.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Easy Foods</title>
    <link rel="stylesheet" href="estilos/estilo_admin.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <h2>Easy Foods Admin</h2>
            <nav>
                <ul>
                    <li><a href="admin.php" class="active">Dashboard</a></li>
                    <li><a href="admin_recetas.php">Gestión de Recetas</a></li>
                    <li><a href="admin_categorias.php">Categorías</a></li>
                    <li><a href="admin_etiquetas.php">Etiquetas</a></li>
                    <li><a href="main.php">Volver al sitio</a></li>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <h1>Panel de Administración</h1>
            <div class="admin-stats">
                <div class="stat-card">
                    <h3>Recetas</h3>
                    <p><?= count(getAllRecetas()) ?></p>
                </div>
                <div class="stat-card">
                    <h3>Categorías</h3>
                    <p><?= count(getCategorias()) ?></p>
                </div>
                <div class="stat-card">
                    <h3>Etiquetas</h3>
                    <p><?= count(getEtiquetas()) ?></p>
                </div>
            </div>
            
            <section class="recent-recipes">
                <h2>Recetas Recientes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (getRecetasWithDetails() as $receta): ?>
                        <tr>
                            <td><?= $receta['id'] ?></td>
                            <td><?= htmlspecialchars($receta['titulo']) ?></td>
                            <td><?= htmlspecialchars($receta['categoria_nombre']) ?></td>
                            <td><?= date('d/m/Y', strtotime($receta['fecha_creacion'])) ?></td>
                            <td>
                                <a href="admin_recetas.php?action=edit&id=<?= $receta['id'] ?>" class="btn-edit">Editar</a>
                                <a href="admin_recetas.php?action=delete&id=<?= $receta['id'] ?>" class="btn-delete" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>