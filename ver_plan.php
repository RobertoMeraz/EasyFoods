<?php
// ver_plan.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'conexion.php';
require_once 'funciones.php'; 

global $conn;

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id_actual = $_SESSION['user_id']; // Usuario logueado
$id_lista_plan = filter_input(INPUT_GET, 'id_plan', FILTER_VALIDATE_INT);

if (!$id_lista_plan) {
    $_SESSION['mensaje_error_perfil'] = "ID de plan no válido."; // Usar la misma variable de sesión que perfil.php
    header('Location: perfil.php#mis-planes');
    exit;
}

$plan = null;
$recetas_del_plan = [];
$error_pagina = '';

try {
    // 1. Obtener detalles del plan
    $stmt_plan = $conn->prepare("
        SELECT lp.id_lista_plan, lp.nombre_plan, lp.descripcion_plan, lp.fecha_creacion_plan, lp.usuario_id, u.nombre as nombre_creador
        FROM lista_planes lp
        JOIN usuarios u ON lp.usuario_id = u.id_usuario
        WHERE lp.id_lista_plan = :id_lista_plan
    ");
    $stmt_plan->bindParam(':id_lista_plan', $id_lista_plan, PDO::PARAM_INT);
    $stmt_plan->execute();
    $plan = $stmt_plan->fetch(PDO::FETCH_ASSOC);

    if (!$plan) {
        $_SESSION['mensaje_error_perfil'] = "Plan de comida no encontrado.";
        header('Location: perfil.php#mis-planes');
        exit;
    }

    // Opcional: Restringir vista solo al creador del plan (o administradores)
    // if ($plan['usuario_id'] != $user_id_actual && !isAdmin()) {
    //     $_SESSION['mensaje_error_perfil'] = "No tienes permiso para ver este plan.";
    //     header('Location: perfil.php#mis-planes');
    //     exit;
    // }

    // 2. Obtener las recetas asociadas a este plan
    // Asumiendo que tienes una columna 'orden' en 'plan_contiene_recetas'
    $stmt_recetas_plan = $conn->prepare("
        SELECT r.id_receta, r.titulo, r.imagen, r.descripcion as receta_descripcion_corta, r.tiempo_preparacion, r.calorias
        FROM recetas r
        JOIN plan_contiene_recetas pcr ON r.id_receta = pcr.receta_id
        WHERE pcr.lista_plan_id = :id_lista_plan
        ORDER BY pcr.orden ASC, r.titulo ASC 
    ");
    $stmt_recetas_plan->bindParam(':id_lista_plan', $id_lista_plan, PDO::PARAM_INT);
    $stmt_recetas_plan->execute();
    $recetas_del_plan = $stmt_recetas_plan->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error_pagina = "Error al cargar el plan de comida: " . $e->getMessage();
    error_log("Error viendo plan (ID: $id_lista_plan, Usuario: $user_id_actual): " . $e->getMessage());
}

$base_url = '/'; // Ajusta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Plan: <?= htmlspecialchars($plan['nombre_plan'] ?? 'Plan no encontrado') ?> - Easy Foods</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_main.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_header.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>estilos/estilo_footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; color: #333; line-height: 1.6; }
        .container-view-plan { max-width: 900px; margin: 30px auto; padding: 25px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .plan-header h1 { color: #4CAF50; margin-bottom: 10px; font-size: 2em; }
        .plan-header .plan-meta-info { font-size: 0.9em; color: #777; margin-bottom: 20px; }
        .plan-description-view { background-color: #f0f0f0; padding: 15px; border-radius: 5px; margin-bottom: 30px; font-style: italic; }
        
        .plan-actions-header { margin-bottom: 20px; text-align: right; }
        .plan-actions-header .btn { margin-left: 10px; }

        .recipes-in-plan-list h2 { font-size: 1.5em; color: #333; margin-bottom: 20px; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        .recipe-item-in-plan { display: flex; margin-bottom: 20px; padding: 15px; background-color: #fff; border: 1px solid #e0e0e0; border-radius: 5px; align-items: flex-start; }
        .recipe-item-in-plan img { width: 100px; height: 100px; object-fit: cover; border-radius: 4px; margin-right: 20px; }
        .recipe-item-in-plan-content h3 { margin-top: 0; margin-bottom: 5px; font-size: 1.3em; }
        .recipe-item-in-plan-content h3 a { color: #4CAF50; text-decoration: none; }
        .recipe-item-in-plan-content h3 a:hover { text-decoration: underline; }
        .recipe-item-in-plan-content .recipe-summary { font-size: 0.9em; color: #666; max-height: 60px; overflow: hidden; margin-bottom: 10px;}
        .recipe-item-in-plan-meta { font-size: 0.8em; color: #777; }
        .recipe-item-in-plan-meta span { margin-right: 15px; }
        .no-recipes-in-plan { text-align: center; color: #777; padding: 20px; background-color: #f0f0f0; border-radius: 5px; }
        .alert-danger { color: #a94442; background-color: #f2dede; border-color: #ebccd1; padding:15px; margin-bottom:20px; border-radius:4px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container-view-plan">
        <?php if ($error_pagina): ?>
            <p class="alert alert-danger"><?= htmlspecialchars($error_pagina) ?></p>
            <p><a href="perfil.php#mis-planes" class="btn">Volver a Mis Planes</a></p>
        <?php elseif ($plan): ?>
            <div class="plan-header">
                <h1><i class="fas fa-calendar-check"></i> <?= htmlspecialchars($plan['nombre_plan']) ?></h1>
                <p class="plan-meta-info">
                    Creado por: <?= htmlspecialchars($plan['nombre_creador']) ?> 
                    el <?= date('d/m/Y', strtotime($plan['fecha_creacion_plan'])) ?>
                </p>
            </div>

            <div class="plan-actions-header">
                <?php if ($plan['usuario_id'] == $user_id_actual || isAdmin()): // Solo el creador o admin puede editar ?>
                    <a href="editar_plan.php?id_plan=<?= $plan['id_lista_plan'] ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Editar Plan</a>
                <?php endif; ?>
                <a href="perfil.php#mis-planes" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a Mis Planes</a>
            </div>

            <?php if (!empty($plan['descripcion_plan'])): ?>
                <div class="plan-description-view">
                    <p><?= nl2br(htmlspecialchars($plan['descripcion_plan'])) ?></p>
                </div>
            <?php endif; ?>

            <section class="recipes-in-plan-list">
                <h2><i class="fas fa-utensils"></i> Recetas en este Plan</h2>
                <?php if (empty($recetas_del_plan)): ?>
                    <p class="no-recipes-in-plan">Este plan aún no tiene recetas asignadas.</p>
                <?php else: ?>
                    <?php foreach ($recetas_del_plan as $receta_item): ?>
                        <article class="recipe-item-in-plan">
                            <?php if(!empty($receta_item['imagen'])): ?>
                                <img src="<?= htmlspecialchars($receta_item['imagen']) ?>" alt="<?= htmlspecialchars($receta_item['titulo']) ?>">
                            <?php else: ?>
                                <img src="imagenes/receta_default.jpg" alt="Imagen por defecto">
                            <?php endif; ?>
                            <div class="recipe-item-in-plan-content">
                                <h3><a href="receta_detalle.php?id=<?= $receta_item['id_receta'] ?>"><?= htmlspecialchars($receta_item['titulo']) ?></a></h3>
                                <p class="recipe-summary">
                                    <?= htmlspecialchars(substr(strip_tags($receta_item['receta_descripcion_corta'] ?? ''), 0, 120)) ?>...
                                </p>
                                <div class="recipe-item-in-plan-meta">
                                    <span><i class="fas fa-clock"></i> <?= htmlspecialchars($receta_item['tiempo_preparacion'] ?? 0) ?> min</span>
                                    <span><i class="fas fa-fire"></i> <?= htmlspecialchars($receta_item['calorias'] ?? 0) ?> kcal</span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>

        <?php else: // Si $plan es null y no hay $error_pagina (ya manejado por redirect) ?>
            <p class="alert alert-danger">Plan no encontrado o no tienes permiso para verlo.</p>
            <p><a href="perfil.php#mis-planes" class="btn">Volver a Mis Planes</a></p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>