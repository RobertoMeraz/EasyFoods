<?php
require_once 'funciones.php';

// Obtener parámetros de filtrado
$categoria = $_GET['categoria'] ?? '';
$busqueda = $_GET['busqueda'] ?? '';

// Construir la consulta SQL
try {
    global $conn;
    
    $sql = "SELECT r.*, c.nombre as categoria_nombre 
            FROM recetas r 
            LEFT JOIN categorias c ON r.categoria_id = c.id";
    
    $params = [];
    
    // Aplicar filtros si existen
    if (!empty($categoria)) {
        $sql .= " WHERE c.nombre = :categoria";
        $params[':categoria'] = $categoria;
    }
    
    if (!empty($busqueda)) {
        $sql .= (empty($categoria) ? " WHERE" : " AND") . " (r.titulo LIKE :busqueda OR r.description LIKE :busqueda)";
        $params[':busqueda'] = "%$busqueda%";
    }
    
    $sql .= " ORDER BY r.fecha_creacion DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener categorías para los filtros
    $stmt = $conn->query("SELECT id, nombre FROM categorias ORDER BY nombre");
    $categorias_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $error = "Error al obtener recetas: " . $e->getMessage();
    $recetas = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recetas - Easy Foods</title>
    <link rel="stylesheet" href="estilos/estilo_recetas.css">
</head>

<body>
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Recipes Header -->
    <section class="recipes-header">
        <h1>Explora Nuestras Recetas</h1>
        <div class="search-section">
            <form method="GET" action="">
                <input type="text" name="busqueda" class="search-bar" placeholder="Buscar recetas..." 
                       value="<?= htmlspecialchars($busqueda) ?>">
                <div class="filters">
                    <button type="submit" name="categoria" value="" class="filter-btn <?= empty($categoria) ? 'active' : '' ?>">Todas</button>
                    <?php foreach ($categorias_db as $cat): ?>
                        <button type="submit" name="categoria" value="<?= htmlspecialchars($cat['nombre']) ?>" 
                                class="filter-btn <?= $categoria == $cat['nombre'] ? 'active' : '' ?>">
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>
    </section>

    <!-- Recipes Grid -->
    <main class="recipes-grid">
        <?php if (empty($recetas)): ?>
            <div class="no-recipes">
                <p>No se encontraron recetas. Intenta con otros criterios de búsqueda.</p>
            </div>
        <?php else: ?>
            <?php foreach ($recetas as $receta): ?>
                <article class="recipe-card">
                    <?php if (!empty($receta['imagen'])): ?>
                        <img src="<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>" class="recipe-image">
                    <?php else: ?>
                        <img src="imagenes/receta_default.jpg" alt="Receta sin imagen" class="recipe-image">
                    <?php endif; ?>
                    
                    <div class="recipe-content">
                        <h2 class="recipe-title"><?= htmlspecialchars($receta['titulo']) ?></h2>
                        <div class="recipe-meta">
                            <span><?= htmlspecialchars($receta['tiempo_preparacion'] ?? 0) ?> min</span>
                            <span><?= htmlspecialchars($receta['calorias'] ?? 0) ?> kcal por porción</span>
                        </div>
                        <p class="recipe-description"><?= htmlspecialchars($receta['description'] ?? '') ?></p>
                        
                        <!-- Ingredientes (podrías obtenerlos de otra tabla) -->
                        <div class="ingredients">
                            <h3>Ingredientes:</h3>
                            <p>Consulta la receta completa para ver la lista de ingredientes.</p>
                        </div>
                        
                        <div class="recipe-tags">
                            <?php if (!empty($receta['categoria_nombre'])): ?>
                                <span class="tag"><?= htmlspecialchars($receta['categoria_nombre']) ?></span>
                            <?php endif; ?>
                            <span class="tag"><?= htmlspecialchars($receta['dificultad'] ?? 'facil') ?></span>
                            <span class="tag"><?= htmlspecialchars($receta['porciones'] ?? 1) ?> porciones</span>
                        </div>
                        
                        <a href="receta_detalle.php?id=<?= $receta['id_receta'] ?>" class="btn-ver-receta">Ver Receta Completa</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>