<?php
// perfil.php - Versión corregida
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'conexion.php';
require_once 'funciones.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Obtener información del usuario
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Obtener recetas favoritas del usuario
$stmt = $conn->prepare("SELECT r.* FROM recetas r JOIN favoritos f ON r.id_receta = f.receta_id WHERE f.usuario_id = ?");
$stmt->execute([$user_id]);
$favoritos = $stmt->fetchAll();

// Obtener recetas creadas por el usuario
$stmt = $conn->prepare("SELECT * FROM recetas WHERE usuario_id = ?");
$stmt->execute([$user_id]);
$mis_recetas = $stmt->fetchAll();

// Definir la ruta base para los recursos
$base_url = '/'; // Ajusta esto según tu estructura de directorios
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Easy Foods</title>
    
    <!-- Estilos principales usando ruta absoluta -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>estilos/estilo_perfil.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>estilos/estilo_header.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>estilos/estilo_footer.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo $base_url; ?>assets/img/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php 
    // Incluir header con variable base_url
    $include_header = true;
    include 'header.php'; 
    ?>

    <main class="profile-container">
        <!-- Sección de cabecera del perfil -->
        <section class="profile-header">
            <div class="profile-avatar">
                <img src="<?= !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : $base_url.'assets/img/avatar-default.png' ?>" alt="Avatar">
                <button class="edit-avatar-btn" onclick="document.getElementById('avatar-upload').click()">
                    <i class="fas fa-camera"></i>
                </button>
                <input type="file" id="avatar-upload" accept="image/*" style="display: none;">
            </div>
            
            <div class="profile-info">
                <h1><?= htmlspecialchars($user['nombre']) ?></h1>
                <p class="member-since">Miembro desde <?= date('F Y', strtotime($user['fecha_registro'])) ?></p>
                
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= count($mis_recetas) ?></span>
                        <span class="stat-label">Recetas</span>
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
                <a href="nueva_receta.php" class="btn primary-btn">
                    <i class="fas fa-plus"></i> Nuevo plan
                </a>
            </div>
        </section>

        <!-- Menú de navegación del perfil -->
        <nav class="profile-nav">
            <ul>
                <li class="active"><a href="#mis-recetas"><i class="fas fa-book"></i> Mis Recetas</a></li>
                <li><a href="#favoritos"><i class="fas fa-heart"></i> Favoritos</a></li>
                <li><a href="#configuracion"><i class="fas fa-cog"></i> Configuración</a></li>
            </ul>
        </nav>

        <!-- Sección de Mis Recetas -->
        <section id="mis-recetas" class="profile-section">
            <h2><i class="fas fa-book"></i> Mis planes</h2>
            
            <?php if (empty($mis_recetas)): ?>
                <div class="empty-state">
                    <i class="fas fa-utensils"></i>
                    <p>Aún no has creado ningun plan</p>
                    <a href="nueva_receta.php" class="btn primary-btn">Crear plan</a>
                </div>
            <?php else: ?>
                <div class="recipes-grid">
                    <?php foreach ($mis_recetas as $receta): ?>
                        <div class="recipe-card">
                            <img src="<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>">
                            <div class="recipe-info">
                                <h3><?= htmlspecialchars($receta['titulo']) ?></h3>
                                <div class="recipe-meta">
                                    <span><i class="fas fa-clock"></i> <?= htmlspecialchars($receta['tiempo_preparacion']) ?> min</span>
                                    <span><i class="fas fa-fire"></i> <?= htmlspecialchars($receta['calorias']) ?> kcal</span>
                                </div>
                                <div class="recipe-actions">
                                    <a href="editar_receta.php?id=<?= $receta['id_receta'] ?>" class="btn small-btn"><i class="fas fa-edit"></i></a>
                                    <a href="receta.php?id=<?= $receta['id_receta'] ?>" class="btn small-btn"><i class="fas fa-eye"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Sección de Favoritos -->
        <section id="favoritos" class="profile-section" style="display: none;">
            <h2><i class="fas fa-heart"></i> Recetas Favoritas</h2>
            
            <?php if (empty($favoritos)): ?>
                <div class="empty-state">
                    <i class="fas fa-heart-broken"></i>
                    <p>Aún no tienes recetas favoritas</p>
                    <a href="recetas.php" class="btn primary-btn">Explorar recetas</a>
                </div>
            <?php else: ?>
                <div class="recipes-grid">
                    <?php foreach ($favoritos as $receta): ?>
                        <div class="recipe-card">
                            <img src="<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>">
                            <div class="recipe-info">
                                <h3><?= htmlspecialchars($receta['titulo']) ?></h3>
                                <div class="recipe-meta">
                                    <span><i class="fas fa-clock"></i> <?= htmlspecialchars($receta['tiempo_preparacion']) ?> min</span>
                                    <span><i class="fas fa-fire"></i> <?= htmlspecialchars($receta['calorias']) ?> kcal</span>
                                </div>
                                <div class="recipe-actions">
                                    <a href="receta.php?id=<?= $receta['id_receta'] ?>" class="btn small-btn"><i class="fas fa-eye"></i></a>
                                    <button class="btn small-btn remove-favorite" data-recipe="<?= $receta['id_receta'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Sección de Configuración -->
        <section id="configuracion" class="profile-section" style="display: none;">
            <h2><i class="fas fa-cog"></i> Configuración de la Cuenta</h2>
            
            <form class="settings-form">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="bio">Biografía</label>
                    <textarea id="bio" name="bio" rows="4" placeholder="Cuéntanos algo sobre ti..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Preferencias de Cocina</label>
                    <div class="preferences-grid">
                        <label class="preference-option">
                            <input type="checkbox" name="vegetariana" <?= $user['vegetariana'] ? 'checked' : '' ?>>
                            Vegetariana
                        </label>
                        <label class="preference-option">
                            <input type="checkbox" name="vegana" <?= $user['vegana'] ? 'checked' : '' ?>>
                            Vegana
                        </label>
                        <label class="preference-option">
                            <input type="checkbox" name="sin_gluten" <?= $user['sin_gluten'] ? 'checked' : '' ?>>
                            Sin Gluten
                        </label>
                        <label class="preference-option">
                            <input type="checkbox" name="sin_lactosa" <?= $user['sin_lactosa'] ? 'checked' : '' ?>>
                            Sin Lactosa
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn primary-btn">Guardar Cambios</button>
            </form>
            
            <div class="account-actions">
                <h3>Acciones de la Cuenta</h3>
                <button class="btn danger-btn" id="change-password-btn">
                    <i class="fas fa-key"></i> Cambiar Contraseña
                </button>
                <button class="btn danger-btn" id="delete-account-btn">
                    <i class="fas fa-trash"></i> Eliminar Cuenta
                </button>
            </div>
        </section>
    </main>

    <?php 
    // Incluir footer con variable base_url
    $include_footer = true;
    include 'footer.php'; 
    ?>

    <script src="<?php echo $base_url; ?>js/perfil.js"></script>
</body>
</html>