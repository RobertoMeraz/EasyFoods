<?php
// perfil.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'conexion.php';
require_once 'funciones.php'; 

global $conn; // Asegurar que $conn esté disponible

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; 
$error_general = ''; 
$exito_general = ''; 

// Procesar eliminación de receta favorita (sin cambios)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_favorito'])) {
    $receta_id = filter_input(INPUT_POST, 'receta_id', FILTER_VALIDATE_INT);
    if ($receta_id) {
        try {
            $stmt = $conn->prepare("DELETE FROM favoritos WHERE usuario_id = :usuario_id AND receta_id = :receta_id");
            $stmt->execute([':usuario_id' => $user_id, ':receta_id' => $receta_id]);
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

// Procesar ELIMINACIÓN de un PLAN DE COMIDA (lista_planes)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_lista_plan'])) {
    $id_lista_plan_a_eliminar = filter_input(INPUT_POST, 'id_lista_plan', FILTER_VALIDATE_INT);
    
    if ($id_lista_plan_a_eliminar) {
        try {
            // Verificar que el plan pertenece al usuario actual
            $stmt_check_owner = $conn->prepare("SELECT usuario_id FROM lista_planes WHERE id_lista_plan = :id_lista_plan");
            $stmt_check_owner->bindParam(':id_lista_plan', $id_lista_plan_a_eliminar, PDO::PARAM_INT);
            $stmt_check_owner->execute();
            $plan_owner = $stmt_check_owner->fetch();

            if ($plan_owner && $plan_owner['usuario_id'] == $user_id) {
                // Eliminar de lista_planes. Las entradas en plan_contiene_recetas
                // se borrarán en cascada si la FK está configurada con ON DELETE CASCADE.
                $stmt_delete_plan = $conn->prepare("DELETE FROM lista_planes WHERE id_lista_plan = :id_lista_plan");
                $stmt_delete_plan->bindParam(':id_lista_plan', $id_lista_plan_a_eliminar, PDO::PARAM_INT);
                $stmt_delete_plan->execute();
                $_SESSION['mensaje_exito_perfil'] = "Plan de comida eliminado correctamente.";
            } else {
                $_SESSION['mensaje_error_perfil'] = "No tienes permiso para eliminar este plan o el plan no existe.";
            }
        } catch (PDOException $e) {
            $_SESSION['mensaje_error_perfil'] = "Error al eliminar el plan de comida: " . $e->getMessage();
            error_log("Error eliminando lista_plan (ID: $id_lista_plan_a_eliminar, Usuario: $user_id): " . $e->getMessage());
        }
    } else {
        $_SESSION['mensaje_error_perfil'] = "ID de plan no válido para eliminar.";
    }
    header("Location: perfil.php#mis-planes"); // Cambiado el ancla a #mis-planes
    exit;
}


// Obtener información del usuario (sin cambios)
$stmt_user = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = :user_id");
$stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_user->execute();
$user = $stmt_user->fetch();

if (!$user) {
    session_destroy(); 
    header('Location: login.php?error=usuario_no_encontrado');
    exit;
}

// Obtener recetas favoritas del usuario (sin cambios)
$stmt_fav = $conn->prepare("
    SELECT r.* FROM recetas r 
    JOIN favoritos f ON r.id_receta = f.receta_id 
    WHERE f.usuario_id = :user_id 
    ORDER BY f.fecha_agregado DESC
");
$stmt_fav->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_fav->execute();
$favoritos = $stmt_fav->fetchAll();

// --- OBTENER LOS PLANES DE COMIDA DEL USUARIO (NUEVA CONSULTA) ---
$stmt_lista_planes = $conn->prepare("
    SELECT id_lista_plan, nombre_plan, descripcion_plan, fecha_creacion_plan 
    FROM lista_planes 
    WHERE usuario_id = :user_id 
    ORDER BY fecha_creacion_plan DESC
");
$stmt_lista_planes->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_lista_planes->execute();
$mis_lista_planes = $stmt_lista_planes->fetchAll();


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
        .delete-form { display: inline-block; margin: 0 2px; } /* Ajuste de margen */

        /* Estilos para las tarjetas de planes */
        .plans-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .plan-card { background-color: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between; }
        .plan-card h3 { font-size: 1.4em; color: #4CAF50; margin-top: 0; margin-bottom: 10px; }
        .plan-card .plan-description { font-size: 0.9em; color: #555; margin-bottom: 15px; flex-grow: 1; max-height: 100px; overflow-y: auto; }
        .plan-card .plan-meta { font-size: 0.8em; color: #777; margin-bottom: 15px; }
        .plan-card .plan-actions { margin-top: auto; display: flex; gap: 10px; justify-content: flex-start; } /* Alineación de botones */
        .plan-actions .btn { padding: 6px 10px; font-size: 0.85em; } /* Botones más pequeños */
        .plan-actions .btn-view { background-color: #2196F3; color:white; }
        .plan-actions .btn-edit { background-color: #FFC107; color:black; }
        .plan-actions .btn-delete-plan { background-color: #f44336; color:white; }
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
                        <span class="stat-number"><?= count($mis_lista_planes) ?></span> 
                        <span class="stat-label">Planes de Comida</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= count($favoritos) ?></span>
                        <span class="stat-label">Recetas Favoritas</span>
                    </div>
                </div>
            </div>
            <div class="profile-actions">
                <a href="editar_perfil.php" class="btn edit-profile-btn">
                    <i class="fas fa-user-edit"></i> Editar Perfil
                </a>
                 <a href="crear_plan.php" class="btn primary-btn">
                    <i class="fas fa-plus"></i> Crear Plan de Comida
                </a>
            </div>
        </section>

        <nav class="profile-nav">
            <ul>
                <li class="active"><a href="#mis-planes"><i class="fas fa-calendar-alt"></i> Mis Planes de Comida</a></li>
                <li><a href="#favoritos"><i class="fas fa-heart"></i> Recetas Favoritas</a></li>
                <li><a href="#configuracion"><i class="fas fa-cog"></i> Configuración</a></li>
            </ul>
        </nav>

        <section id="mis-planes" class="profile-section">
            <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                 <h2><i class="fas fa-calendar-alt"></i> Mis Planes de Comida</h2>
                 <a href="crear_plan.php" class="btn primary-btn"><i class="fas fa-plus"></i> Crear Nuevo Plan</a>
            </div>

            <?php if (empty($mis_lista_planes)): ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times fa-3x"></i>
                    <p>Aún no has creado ningún plan de comida.</p>
                    <a href="crear_plan.php" class="btn primary-btn">Crear mi primer plan de comida</a> 
                </div>
            <?php else: ?>
                <div class="plans-grid"> 
                    <?php foreach ($mis_lista_planes as $plan): ?>
                        <div class="plan-card">
                            <div>
                                <h3><?= htmlspecialchars($plan['nombre_plan']) ?></h3>
                                <?php if(!empty($plan['descripcion_plan'])): ?>
                                    <p class="plan-description"><?= nl2br(htmlspecialchars($plan['descripcion_plan'])) ?></p>
                                <?php endif; ?>
                                <p class="plan-meta">Creado: <?= date('d/m/Y', strtotime($plan['fecha_creacion_plan'])) ?></p>
                            </div>
                            <div class="plan-actions">
                                <a href="ver_plan.php?id_plan=<?= $plan['id_lista_plan'] ?>" class="btn btn-view" title="Ver Plan"><i class="fas fa-eye"></i> Ver</a>
                                <a href="editar_plan.php?id_plan=<?= $plan['id_lista_plan'] ?>" class="btn btn-edit" title="Editar Plan"><i class="fas fa-edit"></i> Editar</a>
                                <form method="POST" action="perfil.php#mis-planes" class="delete-form">
                                    <input type="hidden" name="id_lista_plan" value="<?= $plan['id_lista_plan'] ?>">
                                    <button type="submit" name="eliminar_lista_plan" class="btn btn-delete-plan" title="Eliminar Plan" 
                                            onclick="return confirm('¿Estás seguro de eliminar este plan de comida? Esta acción no se puede deshacer y eliminará todas las recetas asociadas a él.')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
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
                // Ajustar el enlace activo para #mis-planes
                const linkHref = link.getAttribute('href').substring(1);
                link.parentElement.classList.toggle('active', linkHref === targetId);
            });
        }

        // El ID de ancla para "Mis Planes" ahora es "mis-planes"
        let initialSectionId = window.location.hash ? window.location.hash.substring(1) : 'mis-planes';
        if (!document.getElementById(initialSectionId) || !['mis-planes', 'favoritos', 'configuracion'].includes(initialSectionId)) {
            initialSectionId = 'mis-planes'; // Por defecto a mis-planes
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
                    alert("Funcionalidad de eliminar cuenta (eliminar_cuenta_procesar.php) aún no implementada o necesita revisión.");
                }
            }
        }
    });
    </script>
</body>
</html> 