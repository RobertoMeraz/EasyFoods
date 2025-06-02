<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parfait de Yogur y Granola - Easy Foods</title>
    <link rel="stylesheet" href="../estilos/estilo_recetas.css">
    <link rel="stylesheet" href="../estilos/estilo_detalle.css">
</head>

<body>
    <!-- Header -->
<?php include 'header.php'; ?>

    <!-- Recipe Header -->
    <section class="recipe-header">
        <div class="site-title">EASY FOODS</div>
        <h1 class="recipe-main-title">Parfait de Yogur y Granola</h1>
        <p class="recipe-intro">Un desayuno fresco y crujiente rico en probióticos, perfecto para comenzar el día con energía y salud intestinal.</p>
        
        <div class="recipe-meta">
            <div class="meta-item">
                <span class="meta-icon">⏱️</span>
                <span class="meta-label">Tiempo</span>
                <span class="meta-value">15 min</span>
            </div>
            <div class="meta-item">
                <span class="meta-icon">🔥</span>
                <span class="meta-label">Calorías</span>
                <span class="meta-value">380 kcal</span>
            </div>
            <div class="meta-item">
                <span class="meta-icon">🍽️</span>
                <span class="meta-label">Porciones</span>
                <span class="meta-value">2</span>
            </div>
        </div>
        
        <button class="add-plan-btn">
            Añadir al plan semanal
        </button>
    </section>

    <!-- Imagen principal -->
    <img src="../imagenes/parfait.jpg" alt="Parfait de yogur y granola en vaso transparente mostrando capas de ingredientes" class="recipe-hero-image">

    <!-- Sección de ingredientes -->
    <div class="ingredients-section">
        <h2 class="section-title">Ingredientes</h2>
        <ul class="ingredients-list">
            <li>2 tazas de yogur griego natural</li>
            <li>1/2 taza de granola casera (sin azúcar añadido)</li>
            <li>1 taza de mezcla de frutos rojos (fresas, frambuesas, arándanos)</li>
            <li>2 cucharadas de miel orgánica</li>
            <li>1/4 taza de almendras fileteadas</li>
            <li>1 cucharada de semillas de chía</li>
            <li>2 cucharadas de coco rallado sin azúcar</li>
            <li>Esencia de vainilla (opcional)</li>
            <li>Canela en polvo para decorar</li>
        </ul>
    </div>

    <!-- Sección de macros nutricionales -->
    <div class="macros-section">
        <h2 class="section-title">Información Nutricional</h2>
        <div class="macros-grid">
            <div class="macro-item">
                <div class="macro-value">380</div>
                <div class="macro-label">Calorías</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">18g</div>
                <div class="macro-label">Proteínas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">45g</div>
                <div class="macro-label">Carbohidratos</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">16g</div>
                <div class="macro-label">Grasas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">7g</div>
                <div class="macro-label">Fibra</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">28g</div>
                <div class="macro-label">Azúcares</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">4g</div>
                <div class="macro-label">Grasas saturadas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">85mg</div>
                <div class="macro-label">Sodio</div>
            </div>
        </div>
    </div>

    <!-- Botón de volver -->
    <div class="back-button-container">
        <a href="../recetas.php" class="back-btn">← Volver a Recetas</a>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Sobre Nosotros</h3>
                <p>Easy Foods te ayuda a preparar comidas deliciosas, saludables y accesibles para cada momento del día.</p>
            </div>
            <div class="footer-section">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="../main.php">Inicio</a></li>
                    <li><a href="../recetas.php">Recetas</a></li>
                    <li><a href="../contacto.php">Contacto</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contacto</h3>
                <p>info@easyfoods.com</p>
                <p>+1 234 567 890</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Easy Foods. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>