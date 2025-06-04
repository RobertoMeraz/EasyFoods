<?php
// perfil.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'conexion.php';
require_once 'funciones.php'; // Contiene isLoggedIn() y otras funciones necesarias

// Verificar si el usuario está logueado
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; 
$error_general = ''; 
$exito_general = ''; 

// Procesar eliminación de receta favorita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_favorito'])) {
    $receta_id = filter_input(INPUT_POST, 'receta_id', FILTER_VALIDATE_INT);
    
    if ($receta_id) {
        try {
            $stmt = $conn->prepare("DELETE FROM favoritos WHERE usuario_id = ? AND receta_id = ?");
            $stmt->execute([$user_id, $receta_id]);
            $_SESSION['mensaje_exito_perfil'] = "Receta eliminada de favoritos correctamente.";
        } catch(PDOException $e) {
            $_SESSION['mensaje_error_perfil'] = "Error al eliminar la receta de favoritos: " . $e->getMessage();
            error_log("Error al eliminar favorito (Usuario: $user_id, Receta: $receta_id): " . $e->getMessage());
        }
    } else {
        $_SESSION['mensaje_error_perfil'] = "ID de receta no válido para eliminar de favoritos.";
    }
    header("Location: perfil.php#favoritos"); 
    exit;
}

// Procesar DESVINCULACIÓN de receta creada ("plan")
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desvincular_receta_creada'])) {
    $receta_id = filter_input(INPUT_POST, 'receta_id', FILTER_VALIDATE_INT);
    
    if ($receta_id) {
        try {
            $stmt_check = $conn->prepare("SELECT usuario_id FROM recetas WHERE id_receta = ?");
            $stmt_check->execute([$receta_id]);
            $receta_creada = $stmt_check->fetch();
            
            if ($receta_creada && $receta_creada['usuario_id'] == $user_id) {
                // ¡¡¡ASEGÚRATE QUE LA COLUMNA recetas.usuario_id PERMITA VALORES NULL!!!
                $stmt_disassociate = $conn->prepare("UPDATE recetas SET usuario_id = NULL WHERE id_receta = ? AND usuario_id = ?");
                $stmt_disassociate->execute([$receta_id, $user_id]);
                
                if ($stmt_disassociate->rowCount() > 0) {
                    $_SESSION['mensaje_exito_perfil'] = "Plan/receta desvinculado de tu perfil correctamente.";
                } else {
                    $_SESSION['mensaje_error_perfil'] = "No se pudo desvincular el plan/receta. Puede que ya no te pertenezca, no exista, o la columna 'usuario_id' no permita NULLs.";
                }
            } else {
                $_SESSION['mensaje_error_perfil'] = "No tienes permiso para modificar este plan/receta o no existe.";
            }
        } catch(PDOException $e) {
            $_SESSION['mensaje_error_perfil'] = "Error al desvincular el plan/receta: " . $e->getMessage() . ". Verifica si la columna 'usuario_id' en 'recetas' permite NULLs.";
            error_log("Error al desvincular receta creada (Usuario: $user_id, Receta: $receta_id): " . $e->getMessage());
        }
    } else {
        $_SESSION['mensaje_error_perfil'] = "ID de receta no válido para desvincular.";
    }
    header("Location: perfil.php#mis-recetas");
    exit;
}

// Obtener información del usuario
$stmt_user = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch();

if (!$user) {
    session_destroy(); 
    header('Location: login.php?error=usuario_no_encontrado');
    exit;
}

// Obtener recetas favoritas del usuario
$stmt_fav = $conn->prepare("
    SELECT r.* FROM recetas r 
    JOIN favoritos f ON r.id_receta = f.receta_id 
    WHERE f.usuario_id = ? 
    ORDER BY f.fecha_agregado DESC
");
$stmt_fav->execute([$user_id]);
$favoritos = $stmt_fav->fetchAll();

// Obtener recetas creadas por el usuario ("planes")
$stmt_mis_recetas = $conn->prepare("SELECT * FROM recetas WHERE usuario_id = ? ORDER BY fecha_creacion DESC");
$stmt_mis_recetas->execute([$user_id]);
$mis_recetas = $stmt_mis_recetas->fetchAll();

$base_url = '/'; // Ajusta según tu estructura

if (isset($_SESSION['mensaje_exito_perfil'])) {
    $exito_general = htmlspecialchars($_SESSION['mensaje_exito_perfil']);
    unset($_SESSION['mensaje_exito_perfil']);
}
if (isset($_SESSION['mensaje_error_perfil'])) {
    $error_general = htmlspecialchars($_SESSION['mensaje_error_perfil']);
    unset($_SESSION['mensaje_error_perfil']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - <?= htmlspecialchars($user['nombre']) ?> - Easy Foods</title>
    
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_perfil.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_header.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_footer.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link rel="icon" href="<?= htmlspecialchars($base_url) ?>assets/img/favicon.ico" type="image/x-icon">
    <style>
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; text-align: center; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .delete-form { display: inline-block; margin: 0; }
        .btn.warning-btn { background-color: #ffc107; border-color: #ffc107; color: #212529; }
        .btn.warning-btn:hover { background-color: #e0a800; border-color: #d39e00; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <?php if ($exito_general): ?>
        <div class="alert alert-success"><?= $exito_general ?></div>
    <?php endif; ?>
    <?php if ($error_general): ?>
        <div class="alert alert-danger"><?= $error_general ?></div>
    <?php endif; ?>

    <main class="profile-container">
        <section class="profile-header">
            <div class="profile-info">
                <h1><?= htmlspecialchars($user['nombre']) ?></h1>
                <p class="member-since">Miembro desde <?= date('F Y', strtotime($user['fecha_registro'])) ?></p>
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= count($mis_recetas) ?></span>
                        <span class="stat-label">Planes Creados</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= count($favoritos) ?></span>
                        <span class="stat-label">Favoritos</span>
                    </div>
                </div>
            </div>
            <div class="profile-actions">
                <a href="editar_perfil.php" class="btn edit-profile-btn">
                    <i class="fas fa-user-edit"></i> Editar Perfil
                </a>
                </div>
        </section>

        <nav class="profile-nav">
            <ul>
                <li class="active"><a href="#mis-recetas"><i class="fas fa-book"></i> Mis Planes</a></li>
                <li><a href="#favoritos"><i class="fas fa-heart"></i> Favoritos</a></li>
                <li><a href="#configuracion"><i class="fas fa-cog"></i> Configuración</a></li>
            </ul>
        </nav>

        <section id="mis-recetas" class="profile-section">
            <h2><i class="fas fa-book"></i> Mis Planes</h2>
            <?php if (empty($mis_recetas)): ?>
                <div class="empty-state">
                    <i class="fas fa-utensils fa-3x"></i>
                    <p>Aún no has creado ningún plan.</p>
                    <a href="nueva_receta.php" class="btn primary-btn">Crear mi primer plan</a> 
                </div>
            <?php else: ?>
                <div class="recipes-grid">
                    <?php foreach ($mis_recetas as $mi_receta): ?>
                        <div class="recipe-card">
                            <img src="<?= htmlspecialchars($mi_receta['imagen'] ?: $base_url.'imagenes/receta_default.jpg') ?>" alt="<?= htmlspecialchars($mi_receta['titulo']) ?>">
                            <div class="recipe-info">
                                <h3><?= htmlspecialchars($mi_receta['titulo']) ?></h3>
                                <div class="recipe-meta">
                                    <span><i class="fas fa-clock"></i> <?= htmlspecialchars($mi_receta['tiempo_preparacion']) ?> min</span>
                                    <span><i class="fas fa-fire"></i> <?= htmlspecialchars($mi_receta['calorias']) ?> kcal</span>
                                </div>
                                <div class="recipe-actions">
                                    <a href="editar_receta.php?id=<?= $mi_receta['id_receta'] ?>" class="btn small-btn" title="Editar"><i class="fas fa-edit"></i></a>
                                    <a href="receta_detalle.php?id=<?= $mi_receta['id_receta'] ?>" class="btn small-btn" title="Ver"><i class="fas fa-eye"></i></a>
                                    <form method="POST" action="perfil.php#mis-recetas" class="delete-form">
                                        <input type="hidden" name="receta_id" value="<?= $mi_receta['id_receta'] ?>">
                                        <button type="submit" name="desvincular_receta_creada" class="btn small-btn warning-btn" title="Quitar de mi perfil" 
                                                onclick="return confirm('¿Estás seguro de quitar este plan de tu perfil? La receta seguirá existiendo pero ya no aparecerá como tuya en esta sección.')">
                                            <i class="fas fa-unlink"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section id="favoritos" class="profile-section" style="display: none;">
            <h2><i class="fas fa-heart"></i> Recetas Favoritas</h2>
            <?php if (empty($favoritos)): ?>
                <div class="empty-state">
                    <i class="fas fa-heart-broken fa-3x"></i>
                    <p>Aún no tienes recetas favoritas.</p>
                    <a href="recetas.php" class="btn primary-btn">Explorar recetas</a>
                </div>
            <?php else: ?>
                <div class="recipes-grid">
                    <?php foreach ($favoritos as $receta_fav): ?>
                        <div class="recipe-card">
                            <img src="<?= htmlspecialchars($receta_fav['imagen'] ?: $base_url.'imagenes/receta_default.jpg') ?>" alt="<?= htmlspecialchars($receta_fav['titulo']) ?>">
                            <div class="recipe-info">
                                <h3><?= htmlspecialchars($receta_fav['titulo']) ?></h3>
                                <div class="recipe-meta">
                                    <span><i class="fas fa-clock"></i> <?= htmlspecialchars($receta_fav['tiempo_preparacion']) ?> min</span>
                                    <span><i class="fas fa-fire"></i> <?= htmlspecialchars($receta_fav['calorias']) ?> kcal</span>
                                </div>
                                <div class="recipe-actions">
                                    <a href="receta_detalle.php?id=<?= $receta_fav['id_receta'] ?>" class="btn small-btn" title="Ver"><i class="fas fa-eye"></i></a>
                                    <form method="POST" action="perfil.php#favoritos" class="delete-form">
                                        <input type="hidden" name="receta_id" value="<?= $receta_fav['id_receta'] ?>">
                                        <button type="submit" name="eliminar_favorito" class="btn small-btn danger-btn" title="Eliminar de Favoritos" 
                                                onclick="return confirm('¿Estás seguro de eliminar esta receta de tus favoritos?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section id="configuracion" class="profile-section" style="display: none;">
            <h2><i class="fas fa-cog"></i> Configuración de la Cuenta</h2>
            <p>La edición de tus datos personales se realiza desde <a href="editar_perfil.php" class="btn">Editar Perfil</a>.</p> 
            
            <div class="account-actions" style="margin-top: 2rem;">
                <h3>Acciones de la Cuenta</h3>
                <button class="btn danger-btn" id="delete-account-btn" onclick="confirmarEliminarCuenta()">
                    <i class="fas fa-trash"></i> Eliminar Mi Cuenta
                </button>
                <form id="form-delete-account" action="eliminar_cuenta_procesar.php" method="POST" style="display:none;">
                    <input type="hidden" name="confirm_delete_user" value="true"> 
                </form>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.profile-nav a');
        const profileSections = document.querySelectorAll('.profile-section');

        function showSection(targetId) {
            profileSections.forEach(section => {
                section.style.display = (section.id === targetId) ? 'block' : 'none';
            });
            navLinks.forEach(link => {
                link.parentElement.classList.toggle('active', link.getAttribute('href').substring(1) === targetId);
            });
        }

        let initialSectionId = window.location.hash ? window.location.hash.substring(1) : 'mis-recetas';
        if (!document.getElementById(initialSectionId) || !['mis-recetas', 'favoritos', 'configuracion'].includes(initialSectionId)) {
            initialSectionId = 'mis-recetas';
        }
        showSection(initialSectionId);

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                showSection(targetId);
                try { 
                    history.pushState(null, null, '#' + targetId);
                } catch (err) {}
            });
        });

        window.confirmarEliminarCuenta = function() {
            if (confirm('ADVERTENCIA: ¿Estás ABSOLUTAMENTE SEGURO de que quieres eliminar tu cuenta? Esta acción es irreversible y todos tus datos (perfil, planes creados, favoritos) se perderán para siempre.')) {
                if(confirm('CONFIRMACIÓN FINAL: ¿Realmente quieres proceder con la eliminación PERMANENTE de la cuenta?')) {
                    // document.getElementById('form-delete-account').submit(); // Descomenta cuando tengas eliminar_cuenta_procesar.php
                    alert("Funcionalidad de eliminar cuenta (eliminar_cuenta_procesar.php) aún no implementada o necesita revisión.");
                }
            }
        }
    });
    </script>
</body>
</html>