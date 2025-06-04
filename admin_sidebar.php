<aside class="admin-sidebar">
    <h2>Easy Foods Admin</h2>
    <nav>
        <ul>
            <li><a href="admin.php" <?= basename($_SERVER['PHP_SELF']) === 'admin.php' ? 'class="active"' : '' ?>>Dashboard</a></li>
            <li><a href="admin_recetas.php" <?= basename($_SERVER['PHP_SELF']) === 'admin_recetas.php' ? 'class="active"' : '' ?>>Gestión de Recetas</a></li>
            <li><a href="admin_categorias.php" <?= basename($_SERVER['PHP_SELF']) === 'admin_categorias.php' ? 'class="active"' : '' ?>>Categorías</a></li>
            <li><a href="admin_etiquetas.php" <?= basename($_SERVER['PHP_SELF']) === 'admin_etiquetas.php' ? 'class="active"' : '' ?>>Etiquetas</a></li>
            <li><a href="admin_ingredientes.php" <?= basename($_SERVER['PHP_SELF']) === 'admin_ingredientes.php' ? 'class="active"' : '' ?>>Ingredientes</a></li>
            <li><a href="main.php">Volver al sitio</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </nav>
</aside>