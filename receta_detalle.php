<?php
// receta_detalle.php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'funciones.php'; // Incluye conexion.php y funciones como isLoggedIn(), addRecetaToFavoritos()
global $conn; // Para la conexión PDO desde conexion.php

$mensaje_accion_favorito = ''; // Para mensajes de feedback de la acción de favorito
$error_db = null; // Para errores al cargar datos de la receta

// --- INICIO: Lógica para procesar la acción de añadir a favoritos ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_favorito']) && $_POST['accion_favorito'] === 'anadir') {
    if (!isLoggedIn()) {
        $redirect_url = 'receta_detalle.php';
        if (isset($_GET['id'])) { // Reconstruir URL para redirección de login
            $redirect_url .= '?id=' . urlencode($_GET['id']);
        }
        $mensaje_accion_favorito = '<p class="mensaje-feedback error">Debes <a href="login.php?redirect=' . urlencode($redirect_url) . '">iniciar sesión</a> para añadir a favoritos.</p>';
    } else {
        // Usar el ID del campo oculto del formulario
        $receta_id_a_favoritar = filter_input(INPUT_POST, 'id_receta_favorito', FILTER_VALIDATE_INT);
        $usuario_logueado_id = $_SESSION['user_id'];

        if ($receta_id_a_favoritar && $usuario_logueado_id) {
            $resultado_favorito = addRecetaToFavoritos($usuario_logueado_id, $receta_id_a_favoritar);
            if ($resultado_favorito === true) {
                $mensaje_accion_favorito = '<p class="mensaje-feedback success">Receta añadida a favoritos correctamente.</p>';
            } elseif ($resultado_favorito === "already_exists") {
                $mensaje_accion_favorito = '<p class="mensaje-feedback info">Esta receta ya está en tus favoritos.</p>';
            } else {
                // addRecetaToFavoritos devolvió false, el error detallado debería estar en el log de PHP
                $mensaje_accion_favorito = '<p class="mensaje-feedback error">Error al añadir la receta a favoritos. Revisa los logs del servidor para más detalles.</p>';
            }
        } else {
            $mensaje_accion_favorito = '<p class="mensaje-feedback error">ID de receta o de usuario no válido para la acción de favorito.</p>';
        }
    }
}
// --- FIN: Lógica para procesar la acción ---


// Obtener el ID de la receta desde la URL
$receta_id_get = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$receta_id_get) {
    header('Location: recetas.php?error=id_no_proporcionado');
    exit;
}

$receta = null;
$ingredientes = [];
$etiquetas = [];

try {
    // Obtener información básica de la receta
    // El LEFT JOIN con usuarios manejará correctamente si recetas.usuario_id es NULL (autor será NULL)
    $stmt_receta = $conn->prepare("
        SELECT r.*, c.nombre as categoria_nombre, u.nombre as autor
        FROM recetas r
        LEFT JOIN categorias c ON r.categoria_id = c.id
        LEFT JOIN usuarios u ON r.usuario_id = u.id_usuario
        WHERE r.id_receta = :id_receta
    ");
    $stmt_receta->bindParam(':id_receta', $receta_id_get, PDO::PARAM_INT);
    $stmt_receta->execute();
    $receta = $stmt_receta->fetch(PDO::FETCH_ASSOC);

    if (!$receta) {
        header('Location: recetas.php?error=receta_no_encontrada');
        exit;
    }

    // Obtener ingredientes de la receta (usando el nombre de tabla corregido 'receta_ingrediente')
    $stmt_ing = $conn->prepare("
        SELECT ri.cantidad, ri.notas, i.nombre as ingrediente
        FROM receta_ingrediente ri
        LEFT JOIN ingredientes i ON ri.ingrediente_id = i.id_ingrediente
        WHERE ri.receta_id = :id_receta
        ORDER BY ri.id 
    ");
    $stmt_ing->bindParam(':id_receta', $receta_id_get, PDO::PARAM_INT);
    $stmt_ing->execute();
    $ingredientes = $stmt_ing->fetchAll(PDO::FETCH_ASSOC);

    // Obtener etiquetas de la receta (usando el nombre de tabla corregido 'receta_etiqueta')
    $stmt_eti = $conn->prepare("
        SELECT e.nombre as etiqueta
        FROM receta_etiqueta re
        LEFT JOIN etiquetas e ON re.etiqueta_id = e.id_etiqueta
        WHERE re.receta_id = :id_receta
    ");
    $stmt_eti->bindParam(':id_receta', $receta_id_get, PDO::PARAM_INT);
    $stmt_eti->execute();
    $etiquetas = $stmt_eti->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    error_log("Error al cargar detalles de receta (ID: $receta_id_get): " . $e->getMessage());
    $error_db = "Error crítico al cargar la información de la receta. Por favor, inténtalo más tarde.";
    // $receta podría ser null si la consulta principal falla.
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($receta['titulo'] ?? 'Detalle de Receta') ?> - Easy Foods</title>
    <link rel="stylesheet" href="estilos/estilo_recetas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilos originales proporcionados anteriormente */
        .recipe-detail { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; } /* Margen superior ajustado */
        .recipe-header { display: flex; gap: 2rem; margin-bottom: 2rem; }
        .recipe-image-large { width: 50%; max-height: 400px; object-fit: cover; border-radius: 8px; }
        .recipe-info { width: 50%; }
        .recipe-title { font-size: 2.2rem; margin-bottom: 1rem; color: #333; }
        .recipe-meta { display: flex; flex-wrap: wrap; gap: 1rem 1.5rem; margin-bottom: 1.5rem; color: #666; font-size: 0.9em; }
        .recipe-meta span { display: inline-flex; align-items: center; }
        .recipe-meta i { margin-right: 0.5em; color: #4CAF50; } /* Iconos con color */
        .recipe-descripcion { margin-bottom: 2rem; line-height: 1.7; color: #555; }
        .ingredients-list { background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 3rem; }
        .ingredients-list h3 { margin-top:0; margin-bottom: 1.5rem; color: #4a9f45; font-size: 1.4em; border-bottom: 1px solid #e0e0e0; padding-bottom: 0.5rem;}
        .ingredients-list ul { list-style: none; padding-left: 0; }
        .ingredient-item { margin-bottom: 0.8rem; padding-left: 1.5rem; position: relative; line-height: 1.5; }
        .ingredient-item::before { content: "›"; position: absolute; left: 0; color: #4a9f45; font-weight: bold; font-size: 1.2em; }
        .ingredient-quantity { font-weight: 600; margin-right: 0.5em; }
        .ingredient-notes { font-style: italic; color: #777; margin-left: 0.5em; font-size: 0.9em; }
        .recipe-tags-section strong { font-weight: 600; margin-right: 0.5em;}
        .recipe-tags { display: inline-flex; gap: 0.5rem; flex-wrap: wrap; }
        .tag { background-color: #e9e9e9; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.85rem; color: #555; }
        .btn-favorite { background-color: #4a9f45; color: white; padding: 0.75rem 1.25rem; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; transition: background-color 0.2s; }
        .btn-favorite:hover { background-color: #388e3c; }
        .form-favorito { margin-top: 1.5rem; }
        .info-login-favorito { margin-top: 1.5rem; font-size: 0.95em; color: #555; }
        .info-login-favorito a { color: #4a9f45; text-decoration: underline; font-weight: bold; }

        @media (max-width: 768px) {
            .recipe-header { flex-direction: column; }
            .recipe-image-large, .recipe-info { width: 100%; }
            .recipe-detail { margin-top: 1rem; }
        }
        /* Estilos para los mensajes de feedback */
        .mensaje-feedback { padding: 12px 15px; margin: 20px 0; border-radius: 5px; font-size: 0.95em; border: 1px solid transparent; text-align: center; }
        .mensaje-feedback.success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .mensaje-feedback.error { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .mensaje-feedback.info { color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="recipe-detail">
        <?php
        if (!empty($mensaje_accion_favorito)) {
            echo $mensaje_accion_favorito;
        }
        if (!empty($error_db) && empty($mensaje_accion_favorito)) { // Mostrar error_db si no hay otro mensaje más específico
            echo '<p class="mensaje-feedback error">' . htmlspecialchars($error_db) . '</p>';
        }
        ?>

        <?php if ($receta): // Solo proceder si la receta se cargó correctamente ?>
            <div class="recipe-header">
                <?php if (!empty($receta['imagen'])): ?>
                    <img src="<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>" class="recipe-image-large">
                <?php else: ?>
                    <img src="imagenes/receta_default.jpg" alt="Imagen por defecto para <?= htmlspecialchars($receta['titulo']) ?>" class="recipe-image-large">
                <?php endif; ?>

                <div class="recipe-info">
                    <h1 class="recipe-title"><?= htmlspecialchars($receta['titulo']) ?></h1>
                    
                    <?php
                    // --- BLOQUE DE DEBUG TEMPORAL (DESCOMENTAR PARA VERIFICAR VALORES) ---
                    /*
                    echo "<div style='background:lightgoldenrodyellow; color: #333; padding:10px; margin:15px 0; border:1px solid #ccc; font-size:0.9em; line-height:1.4;'>";
                    echo "<strong>DEBUG INFO RECETA (desde receta_detalle.php):</strong><br>";
                    echo "ID Receta (de URL, \$receta_id_get): <strong>" . htmlspecialchars($receta_id_get) . "</strong><br>";
                    echo "ID Receta (de array \$receta['id_receta']): <strong>" . htmlspecialchars($receta['id_receta'] ?? 'NO DEFINIDO EN ARRAY') . "</strong><br>";
                    echo "Usuario ID Creador (de array \$receta['usuario_id']): <strong>" . (isset($receta['usuario_id']) ? htmlspecialchars($receta['usuario_id']) : 'NO DEFINIDO o NULL') . "</strong><br>";
                    echo "Autor (nombre de array \$receta['autor']): <strong>" . (isset($receta['autor']) ? htmlspecialchars($receta['autor']) : 'NO DEFINIDO o NULL') . "</strong><br>";
                    echo "isLoggedIn(): " . (isLoggedIn() ? '<strong>true</strong>' : '<strong>false</strong>') . "<br>";
                    $puedeMostrarFormFavoritos = isLoggedIn() && $receta && $receta_id_get > 0;
                    echo "Condición para mostrar form favoritos (isLoggedIn && \$receta && \$receta_id_get > 0): " . ($puedeMostrarFormFavoritos ? '<strong>CUMPLE (Formulario debería mostrarse)</strong>' : '<strong>NO CUMPLE (Formulario NO debería mostrarse)</strong>') . "<br>";
                    echo "</div>";
                    */
                    // --- FIN BLOQUE DE DEBUG TEMPORAL ---
                    ?>

                    <div class="recipe-meta">
                        <span><i class="fas fa-clock"></i> <?= htmlspecialchars($receta['tiempo_preparacion'] ?? 0) ?> min</span>
                        <span><i class="fas fa-fire"></i> <?= htmlspecialchars($receta['calorias'] ?? 0) ?> kcal</span>
                        <span><i class="fas fa-users"></i> <?= htmlspecialchars($receta['porciones'] ?? 1) ?> porciones</span>
                        <span><i class="fas fa-chart-bar"></i> <?= htmlspecialchars($receta['dificultad'] ?? 'No especificada') ?></span>
                    </div>

                    <p class="recipe-descripcion"><?= nl2br(htmlspecialchars($receta['descripcion'] ?? 'Descripción no disponible.')) ?></p>

                    <?php if (!empty($receta['categoria_nombre'])): ?>
                        <p><strong>Categoría:</strong> <?= htmlspecialchars($receta['categoria_nombre']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($receta['autor'])): // Si hay un autor (usuario_id no era NULL y se encontró el nombre) ?>
                        <p><strong>Autor:</strong> <?= htmlspecialchars($receta['autor']) ?></p>
                    <?php else: // Si no hay autor (porque recetas.usuario_id era NULL o no se encontró el usuario) ?>
                        <p><strong>Autor:</strong> <em>No especificado</em></p>
                    <?php endif; ?>

                    <?php if (!empty($etiquetas)): ?>
                        <div class="recipe-tags-section">
                            <strong>Etiquetas:</strong>
                            <div class="recipe-tags">
                                <?php foreach ($etiquetas as $etiqueta): ?>
                                    <span class="tag"><?= htmlspecialchars($etiqueta['etiqueta']) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php // Condición para mostrar el botón: usuario logueado Y la receta existe Y tenemos un ID válido desde la URL. ?>
                    <?php if (isLoggedIn() && $receta && $receta_id_get > 0): ?>
                        <form method="POST" action="receta_detalle.php?id=<?= htmlspecialchars($receta_id_get) ?>" class="form-favorito">
                            <input type="hidden" name="id_receta_favorito" value="<?= htmlspecialchars($receta_id_get) ?>">
                            <input type="hidden" name="accion_favorito" value="anadir">
                            <button type="submit" class="btn-favorite">
                                <i class="fas fa-heart"></i> Añadir a favoritos
                            </button>
                        </form>
                    <?php elseif ($receta && $receta_id_get > 0): // Si no está logueado pero la receta es válida ?>
                        <p class="info-login-favorito">
                            <a href="login.php?redirect=<?= urlencode('receta_detalle.php?id='.$receta_id_get) ?>">Inicia sesión</a> para añadir esta receta a tus favoritos.
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="ingredients-list">
                <h3><i class="fas fa-list-ul"></i> Ingredientes</h3>
                <?php if (!empty($ingredientes)): ?>
                    <ul>
                        <?php foreach ($ingredientes as $ing): ?>
                            <li class="ingredient-item">
                                <span class="ingredient-quantity"><?= htmlspecialchars($ing['cantidad'] ?? '') ?></span>
                                <span class="ingredient-name"><?= htmlspecialchars($ing['ingrediente'] ?? 'Ingrediente no especificado') ?></span>
                                <?php if (!empty($ing['notas'])): ?>
                                    <span class="ingredient-notes">(<?= htmlspecialchars($ing['notas']) ?>)</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No se han especificado ingredientes para esta receta.</p>
                <?php endif; ?>
            </div>

        <?php elseif (empty($error_db)): // Si $receta es null/false pero no fue por un error de DB capturado (podría ser $receta_id_get inválido ya manejado) ?>
            <p class="mensaje-feedback error">La receta solicitada no se pudo cargar o no existe.</p>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>