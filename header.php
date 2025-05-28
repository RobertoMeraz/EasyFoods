<?php
// header.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<header class="header">
    <nav class="nav-container">
        <a href="main.php" class="logo">🌱 Easy Foods</a>
        <ul class="nav-menu">
            <li><a href="main.php" class="nav-link">Inicio</a></li>
            <li><a href="recetas.php" class="nav-link">Recetas</a></li>
            <li><a href="sobre_nosotros.php" class="nav-link">Sobre Nosotros</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="perfil.php" class="nav-link">Mi Perfil</a></li>
                <li><a href="logout.php" class="nav-link">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="login.php" class="nav-link">Iniciar Sesión</a></li>
                <li><a href="register.php" class="nav-link">Registrarse</a></li>
            <?php endif; ?>
            <li><a href="contacto.php" class="nav-link">Contáctenos</a></li>
        </ul>
    </nav>
</header>