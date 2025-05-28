<?php
// login.php
session_start();
require_once 'conexion.php';
require_once 'funciones.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Por favor, complete todos los campos";
    } else {
        if (loginUser($email, $password)) {
            header('Location: main.php');
            exit;
        } else {
            $error = "Credenciales incorrectas. Por favor, inténtelo de nuevo.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Foods - Iniciar Sesión</title>
    <link rel="stylesheet" href="estilos/estilo_login.css">
</head>
<body>
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Login Section -->
    <main>
        <section class="login-section">
            <div class="login-container">
                <h2>Iniciar Sesión</h2>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form class="login-form" method="POST" action="login.php">
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            Recordarme
                        </label>
                        <a href="recuperar_password.php" class="forgot-password">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button type="submit" class="btn primary">Iniciar Sesión</button>
                </form>
                <div class="register-link">
                    ¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>