<?php
// register.php
session_start();
require_once 'conexion.php';
require_once 'funciones.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validaciones
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Por favor, complete todos los campos";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Por favor, ingrese un correo electrónico válido";
    } elseif (strlen($password) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden";
    } else {
        $result = registerUser($name, $email, $password);
        
        if ($result === true) {
            $_SESSION['success'] = "Registro exitoso. Por favor inicia sesión.";
            header('Location: login.php');
            exit;
        } else {
            $error = $result; // Mensaje de error de la función registerUser
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Foods - Crear Cuenta</title>
    <link rel="stylesheet" href="estilos/estilo_registro.css">
</head>
<body>
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Register Section -->
    <main>
        <section class="register-section">
            <div class="register-container">
                <h2>Crear Nueva Cuenta</h2>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                
                <form class="register-form" method="POST" action="register.php">
                    <div class="form-group">
                        <label for="name">Nombre Completo</label>
                        <input type="text" id="name" name="name" required value="<?= isset($name) ? htmlspecialchars($name) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>
                        <small class="password-hint">La contraseña debe tener al menos 8 caracteres</small>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="form-terms">
                        <label class="terms-checkbox">
                            <input type="checkbox" name="terms" required>
                            Acepto los <a href="terminos.php">términos y condiciones</a> y la <a href="privacidad.php">política de privacidad</a>
                        </label>
                    </div>
                    <button type="submit" class="btn primary">Crear Cuenta</button>
                </form>
                <div class="login-link">
                    ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>