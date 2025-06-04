<?php
// editar_perfil.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'conexion.php';
require_once 'funciones.php'; // Para isLoggedIn()

global $conn; // Para la conexión PDO

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$nombre_actual = '';

$errores = [];
$mensaje_exito = '';

// Cargar nombre actual del usuario para prellenar el formulario
try {
    $stmt_current = $conn->prepare("SELECT nombre FROM usuarios WHERE id_usuario = ?");
    $stmt_current->execute([$user_id]);
    $currentUserData = $stmt_current->fetch(PDO::FETCH_ASSOC);

    if ($currentUserData) {
        $nombre_actual = $currentUserData['nombre'];
    } else {
        $errores[] = "No se pudieron cargar los datos del perfil actual.";
    }
} catch (PDOException $e) {
    $errores[] = "Error al cargar datos del perfil: " . $e->getMessage();
    error_log("Error cargando perfil para editar (ID: $user_id): " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_nuevo = trim($_POST['nombre'] ?? '');
    $password_actual = $_POST['password_actual'] ?? '';
    $nueva_password = $_POST['nueva_password'] ?? '';
    $confirmar_nueva_password = $_POST['confirmar_nueva_password'] ?? '';

    $actualizar_nombre = false;
    $actualizar_password = false;
    $campos_para_actualizar = [];
    $params_para_actualizar = [];

    // --- Validación y preparación para actualizar nombre ---
    if (empty($nombre_nuevo)) {
        $errores[] = "El nombre es obligatorio.";
    } elseif ($nombre_nuevo !== $nombre_actual) {
        $campos_para_actualizar[] = "nombre = :nombre";
        $params_para_actualizar[':nombre'] = $nombre_nuevo;
        $actualizar_nombre = true;
    }

    // --- Validación y preparación para actualizar contraseña ---
    // Solo procesar si alguno de los campos de contraseña se ha rellenado
    if (!empty($password_actual) || !empty($nueva_password) || !empty($confirmar_nueva_password)) {
        if (empty($password_actual)) {
            $errores[] = "Debes ingresar tu contraseña actual para cambiarla.";
        }
        if (empty($nueva_password)) {
            $errores[] = "La nueva contraseña no puede estar vacía si deseas cambiarla.";
        } elseif (strlen($nueva_password) < 6) { // Ejemplo de validación de longitud mínima
            $errores[] = "La nueva contraseña debe tener al menos 6 caracteres.";
        }
        if ($nueva_password !== $confirmar_nueva_password) {
            $errores[] = "La nueva contraseña y su confirmación no coinciden.";
        }

        if (empty($errores)) { // Si no hay errores hasta ahora relacionados con las contraseñas
            try {
                $stmt_pass_check = $conn->prepare("SELECT password FROM usuarios WHERE id_usuario = ?");
                $stmt_pass_check->execute([$user_id]);
                $userData = $stmt_pass_check->fetch(PDO::FETCH_ASSOC);

                if ($userData && password_verify($password_actual, $userData['password'])) {
                    // Contraseña actual verificada, proceder a hashear la nueva
                    $hashed_nueva_password = password_hash($nueva_password, PASSWORD_DEFAULT);
                    $campos_para_actualizar[] = "password = :password";
                    $params_para_actualizar[':password'] = $hashed_nueva_password;
                    $actualizar_password = true;
                } else {
                    $errores[] = "La contraseña actual ingresada es incorrecta.";
                }
            } catch (PDOException $e) {
                $errores[] = "Error al verificar la contraseña actual: " . $e->getMessage();
                error_log("Error verificando pass en editar_perfil (ID: $user_id): " . $e->getMessage());
            }
        }
    }


    // --- Ejecutar actualización si hay cambios y no hay errores ---
    if (empty($errores) && (!empty($campos_para_actualizar))) {
        $params_para_actualizar[':id_usuario'] = $user_id;
        
        try {
            $sql_update = "UPDATE usuarios SET " . implode(", ", $campos_para_actualizar) . " WHERE id_usuario = :id_usuario";
            $stmt_update = $conn->prepare($sql_update);
            
            if ($stmt_update->execute($params_para_actualizar)) {
                $mensaje_exito = "Perfil actualizado correctamente.";
                if ($actualizar_nombre) {
                    $nombre_actual = $nombre_nuevo; // Actualizar para el formulario
                    $_SESSION['user_name'] = $nombre_nuevo; // Actualizar nombre en sesión
                }
                // No es necesario recargar los datos de la contraseña aquí
            } else {
                $errores[] = "No se pudo actualizar el perfil. Inténtalo de nuevo.";
            }
        } catch (PDOException $e) {
            $errores[] = "Error al actualizar el perfil en la base de datos: " . $e->getMessage();
            error_log("Error actualizando perfil (ID: $user_id): " . $e->getMessage());
        }
    } elseif (empty($errores) && empty($campos_para_actualizar)) {
        // No se detectaron cambios válidos ni errores, pero se envió el formulario.
        // Podrías poner un mensaje tipo "No se realizaron cambios." o simplemente no hacer nada.
        $mensaje_exito = "No se detectaron cambios para actualizar.";
    }
}

$base_url = '/'; // Ajusta según tu estructura
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Easy Foods</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_formularios.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_header.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; line-height: 1.6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; padding: 25px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #4CAF50; margin-bottom: 25px; font-size: 1.8em; }
        .form-section { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .form-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .form-section h2 { font-size: 1.3em; color: #333; margin-bottom: 15px; border-bottom: 2px solid #4CAF50; padding-bottom: 5px; }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: bold; color: #555; font-size: 0.95em; }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 0.95em;
        }
        .form-group small { font-size: 0.8em; color: #777; display: block; margin-top: 5px; }

        .btn-submit {
            display: inline-block;
            background-color: #4CAF50; /* Verde Easy Foods */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
            text-align: center;
        }
        .btn-submit:hover { background-color: #45a049; }
        .btn-cancel {
            display: inline-block;
            background-color: #777; /* Gris para cancelar */
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        .btn-cancel:hover { background-color: #666; }
        .form-actions { text-align: center; margin-top: 30px; }

        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 5px; font-size: 0.9em;}
        .alert-danger { color: #a94442; background-color: #f2dede; border-color: #ebccd1; }
        .alert-danger ul { margin-top: 0; margin-bottom: 0; padding-left: 20px; list-style-position: inside; }
        .alert-success { color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; text-align: center; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h1><i class="fas fa-user-cog"></i> Editar Mi Perfil</h1>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <strong>¡Atención!</strong> Por favor, corrige los siguientes errores:
                <ul>
                    <?php foreach ($errores as $error_item): ?>
                        <li><?= htmlspecialchars($error_item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($mensaje_exito): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($mensaje_exito) ?>
            </div>
        <?php endif; ?>

        <form action="editar_perfil.php" method="POST">
            <div class="form-section">
                <h2><i class="fas fa-id-card"></i> Información Personal</h2>
                <div class="form-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre_actual) ?>" required>
                </div>
            </div>

            <div class="form-section">
                <h2><i class="fas fa-key"></i> Cambiar Contraseña</h2>
                <p><small>Deja los campos de contraseña en blanco si no deseas cambiarla.</small></p>
                <div class="form-group">
                    <label for="password_actual">Contraseña Actual:</label>
                    <input type="password" id="password_actual" name="password_actual">
                </div>

                <div class="form-group">
                    <label for="nueva_password">Nueva Contraseña:</label>
                    <input type="password" id="nueva_password" name="nueva_password">
                    <small>Mínimo 6 caracteres.</small>
                </div>

                <div class="form-group">
                    <label for="confirmar_nueva_password">Confirmar Nueva Contraseña:</label>
                    <input type="password" id="confirmar_nueva_password" name="confirmar_nueva_password">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Cambios</button>
                <a href="perfil.php" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>