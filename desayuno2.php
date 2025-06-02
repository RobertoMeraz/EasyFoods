<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huevos Rancheros Mexicanos - Easy Foods</title>
    <link rel="stylesheet" href="../estilos/estilo_recetas.css">
    <link rel="stylesheet" href="../estilos/estilo_detalle.css">
</head>

<body>
    <!-- Header -->
<?php include 'header.php'; ?>

    <!-- Recipe Header -->
    <section class="recipe-header">
        <div class="site-title">EASY FOODS</div>
        <h1 class="recipe-main-title">Huevos Rancheros Mexicanos</h1>
        <p class="recipe-intro">Un desayuno mexicano tradicional lleno de sabor y proteínas, perfecto para empezar el día con energía auténtica.</p>
        
        <div class="recipe-meta">
            <div class="meta-item">
                <span class="meta-icon">⏱️</span>
                <span class="meta-label">Tiempo</span>
                <span class="meta-value">25 min</span>
            </div>
            <div class="meta-item">
                <span class="meta-icon">🔥</span>
                <span class="meta-label">Calorías</span>
                <span class="meta-value">450 kcal</span>
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
    <img src="../imagenes/huevos_rancheros.jpg" alt="Huevos Rancheros servidos con salsa, frijoles y aguacate" class="recipe-hero-image">

    <!-- Sección de ingredientes -->
    <div class="ingredients-section">
        <h2 class="section-title">Ingredientes</h2>
        <ul class="ingredients-list">
            <li>4 huevos</li>
            <li>4 tortillas de maíz</li>
            <li>2 tomates maduros</li>
            <li>1 cebolla mediana</li>
            <li>2 chiles verdes (jalapeños o serranos)</li>
            <li>1 taza de frijoles refritos</li>
            <li>1 aguacate en rodajas</li>
            <li>1/4 taza de queso fresco rallado</li>
            <li>2 cucharadas de aceite vegetal</li>
            <li>Sal y pimienta al gusto</li>
            <li>Cilantro fresco para decorar</li>
        </ul>
    </div>

    <!-- Sección de macros nutricionales -->
    <div class="macros-section">
        <h2 class="section-title">Información Nutricional</h2>
        <div class="macros-grid">
            <div class="macro-item">
                <div class="macro-value">450</div>
                <div class="macro-label">Calorías</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">22g</div>
                <div class="macro-label">Proteínas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">35g</div>
                <div class="macro-label">Carbohidratos</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">25g</div>
                <div class="macro-label">Grasas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">8g</div>
                <div class="macro-label">Fibra</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">4g</div>
                <div class="macro-label">Azúcares</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">5g</div>
                <div class="macro-label">Grasas saturadas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">620mg</div>
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