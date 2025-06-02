<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burrito de Desayuno Proteico - Easy Foods</title>
    <link rel="stylesheet" href="../estilos/estilo_recetas.css">
    <link rel="stylesheet" href="../estilos/estilo_detalle.css">
</head>

<body>
    <!-- Header -->
<?php include 'header.php'; ?>

    <!-- Recipe Header -->
    <section class="recipe-header">
        <div class="site-title">EASY FOODS</div>
        <h1 class="recipe-main-title">Burrito de Desayuno Proteico</h1>
        <p class="recipe-intro">Un burrito sustancioso perfecto para deportistas, cargado de proteínas y energía para empezar el día con fuerza.</p>
        
        <div class="recipe-meta">
            <div class="meta-item">
                <span class="meta-icon">⏱️</span>
                <span class="meta-label">Tiempo</span>
                <span class="meta-value">20 min</span>
            </div>
            <div class="meta-item">
                <span class="meta-icon">🔥</span>
                <span class="meta-label">Calorías</span>
                <span class="meta-value">520 kcal</span>
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
    <img src="../imagenes/burrito_proteico.jpg" alt="Burrito de desayuno cortado por la mitad mostrando su relleno proteico" class="recipe-hero-image">

    <!-- Sección de ingredientes -->
    <div class="ingredients-section">
        <h2 class="section-title">Ingredientes</h2>
        <ul class="ingredients-list">
            <li>2 tortillas de trigo integral grandes</li>
            <li>4 huevos grandes revueltos</li>
            <li>1/2 taza de frijoles negros cocidos</li>
            <li>1/4 taza de queso cheddar rallado</li>
            <li>1 pimiento rojo mediano picado</li>
            <li>1 aguacate maduro en rodajas</li>
            <li>2 cucharadas de salsa picante (al gusto)</li>
            <li>2 cucharadas de cilantro fresco picado</li>
            <li>1 cucharada de aceite de oliva</li>
            <li>1/4 cucharadita de comino molido</li>
            <li>Sal y pimienta al gusto</li>
        </ul>
    </div>

    <!-- Sección de macros nutricionales -->
    <div class="macros-section">
        <h2 class="section-title">Información Nutricional</h2>
        <div class="macros-grid">
            <div class="macro-item">
                <div class="macro-value">520</div>
                <div class="macro-label">Calorías</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">32g</div>
                <div class="macro-label">Proteínas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">45g</div>
                <div class="macro-label">Carbohidratos</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">25g</div>
                <div class="macro-label">Grasas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">12g</div>
                <div class="macro-label">Fibra</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">5g</div>
                <div class="macro-label">Azúcares</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">8g</div>
                <div class="macro-label">Grasas saturadas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">780mg</div>
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