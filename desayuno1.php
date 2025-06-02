<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pancakes de Plátano y Avena - Easy Foods</title>
    <link rel="stylesheet" href="/estilos/estilo_recetas.css">
    <link rel="stylesheet" href="/estilos/estilo_detalle.css">
</head>

<body>
    <!-- Header -->
<?php include '/xampp/htdocs/easy foods/header.php'; ?>

    <!-- Recipe Header -->
    <section class="recipe-header">
        <div class="site-title">EASY FOODS</div>
        <h1 class="recipe-main-title">Pancakes de Plátano y Avena</h1>
        <p class="recipe-intro">Pancakes esponjosos y saludables, perfectos para empezar el día con energía. Una opción deliciosa sin azúcar añadido que combina la dulzura natural del plátano con los beneficios nutricionales de la avena.</p>
        
        <div class="recipe-meta">
            <div class="meta-item">
                <span class="meta-icon">⏱️</span>
                <span class="meta-label">Tiempo</span>
                <span class="meta-value">20 min</span>
            </div>
            <div class="meta-item">
                <span class="meta-icon">🔥</span>
                <span class="meta-label">Calorías</span>
                <span class="meta-value">320 kcal</span>
            </div>
            <div class="meta-item">
                <span class="meta-icon">🍽️</span>
                <span class="meta-label">Porciones</span>
                <span class="meta-value">4</span>
            </div>
        </div>
        
        <button class="add-plan-btn">
            Añadir al plan semanal
        </button>
    </section>

    <!-- Imagen principal -->
    <img src="../imagenes/pancakes.jpg" alt="Pancakes de plátano y avena servidos con miel y frutas" class="recipe-hero-image">

    <!-- Sección de ingredientes -->
    <div class="ingredients-section">
        <h2 class="section-title">Ingredientes</h2>
        <ul class="ingredients-list">
            <li>2 plátanos maduros</li>
            <li>1 taza de avena molida</li>
            <li>2 huevos</li>
            <li>1/2 taza de leche (o leche vegetal)</li>
            <li>1 cucharadita de canela</li>
            <li>1 cucharadita de polvo para hornear</li>
            <li>1 pizca de sal</li>
            <li>1 cucharadita de extracto de vainilla (opcional)</li>
            <li>Miel o jarabe de arce para servir</li>
            <li>Fruta fresca para decorar</li>
            <li>Nueces o almendras picadas (opcional)</li>
            <li>Aceite de coco o mantequilla para cocinar</li>
        </ul>
    </div>

    <!-- Sección de macros nutricionales -->
    <div class="macros-section">
        <h2 class="section-title">Información Nutricional</h2>
        <div class="macros-grid">
            <div class="macro-item">
                <div class="macro-value">320</div>
                <div class="macro-label">Calorías</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">12g</div>
                <div class="macro-label">Proteínas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">45g</div>
                <div class="macro-label">Carbohidratos</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">10g</div>
                <div class="macro-label">Grasas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">5g</div>
                <div class="macro-label">Fibra</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">18g</div>
                <div class="macro-label">Azúcares</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">2g</div>
                <div class="macro-label">Grasas saturadas</div>
            </div>
            <div class="macro-item">
                <div class="macro-value">180mg</div>
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