<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tostadas de Aguacate y Huevo - Easy Foods</title>
    <link rel="stylesheet" href="../estilos/estilo_recetas.css">
    <link rel="stylesheet" href="../estilos/estilo_detalle.css">
</head>

<body>
    <!-- Header -->
<?php include 'header.php'; ?>

    <!-- Recipe Header -->
    <section class="recipe-header">
        <div class="site-title">EASY FOODS</div>
        <h1 class="recipe-main-title">Tostadas de Aguacate y Huevo</h1>
        <p class="recipe-intro">El clásico desayuno saludable con grasas buenas y proteínas, perfecto para una mañana nutritiva y deliciosa.</p>
        
        <div class="recipe-meta">
            <div class="meta-item">
                <span class="meta-icon">⏱️</span>
                <span class="meta-label">Tiempo</span>
                <span class="meta-value">15 min</span>
            </div>
            <div class="meta-item">
                <span class="meta-icon">🔥</span>
                <span class="meta-label">Calorías</span>
                <span class="meta-value">340 kcal</span>
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
    <img src="../imagenes/tostadas_huevo.jpg" alt="Tostadas de aguacate y huevo servidas en plato con tomates cherry y brotes" class="recipe-hero-image">

    <!-- Sección de ingredientes -->
    <div class="ingredients-section">
        <h2 class="section-title">Ingredientes</h2>
        <ul class="ingredients-list">
            <li>2 rebanadas de pan integral tostado</li>
            <li>1 aguacate maduro (pelado y sin hueso)</li>
            <li>2 huevos frescos</li>
            <li>8-10 tomates cherry cortados por la mitad</li>
            <li>1/2 taza de brotes de alfalfa</li>
            <li>1 cucharadita de semillas de sésamo</li>
            <li>Sal marina y pimienta negra al gusto</li>
            <li>1/2 cucharadita de chile en hojuelas (opcional)</li>
            <li>1 cucharadita de aceite de oliva virgen extra</li>
            <li>Jugo de limón fresco (opcional)</li>
        </ul>
    </div>

    <!-- Sección de macros nutricionales -->
    <div class="macros-section">
        <h2 class="section-title">Información Nutricional</h2>
        <div class="macros-grid">
            <div class="macro-item">
                <div class="macro-value">340</div>
                <div class="macro-label">Calorías</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">14g</div>
                <div class="macro-label">Proteínas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">28g</div>
                <div class="macro-label">Carbohidratos</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">21g</div>
                <div class="macro-label">Grasas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">10g</div>
                <div class="macro-label">Fibra</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">3g</div>
                <div class="macro-label">Azúcares</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">4g</div>
                <div class="macro-label">Grasas saturadas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">420mg</div>
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