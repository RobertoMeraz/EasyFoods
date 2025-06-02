<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recetas Rápidas - Easy Foods</title>
    <link rel="stylesheet" href="estilos/estilo_recetas.css">
</head>

<body>
    <!-- Header -->
<?php include 'header.php'; ?>
    <!-- Recipes Header -->
    <section class="recipes-header">
        <h1>Explora Nuestras Recetas</h1>
        <div class="search-section">
            <input type="text" class="search-bar" placeholder="Buscar recetas...">
            <div class="filters">
                <a href="recetas.php"><button class="filter-btn">Todas</button></a>
                <a href="desayunos.php"><button class="filter-btn">Desayunos</button></a>
                <a href="almuerzos.php"><button class="filter-btn">Almuerzos</button></a>
                <a href="cenas.php"><button class="filter-btn">Cenas</button></a>
                <a href="vegetarianas.php"><button class="filter-btn">Vegetarianas</button></a>
                <button class="filter-btn active">Rápidas</button>
            </div>
        </div>
    </section>

    <!-- Recipes Grid -->
    <main class="recipes-grid">
        <!-- Recipe Card 1 -->
        <article class="recipe-card">
            <img src="/imagenes/huevo_pan.jpeg" alt="Pan con huevo" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Huevos Revueltos con Pan Tostado</h2>
                <div class="recipe-meta">
                    <span>10 min</span>
                    <span>350 kcal por porción</span>
                </div>
                <p class="recipe-description">Clásico desayuno proteico listo en minutos.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>2 huevos</li>
                        <li>1 cda de mantequilla</li>
                        <li>2 rebanadas de pan</li>
                        <li>Sal y pimienta al gusto</li>
                        <li>1 cda de leche (opcional para esponjar)</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Rápida</span>
                    <span class="tag">Proteínas</span>
                    <span class="tag">Económica</span>
                </div>
            </div>
        </article>

        <!-- Recipe Card 2 -->
        <article class="recipe-card">
            <img src="/imagenes/ensalada_atun2.jpg" alt="Ensalada de atún" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Ensalada de Atún Express</h2>
                <div class="recipe-meta">
                    <span>5 min</span>
                    <span>300 kcal por porción</span>
                </div>
                <p class="recipe-description">Preparación ultra rápida para cuando tienes prisa.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>1 lata de atún en agua</li>
                        <li>1/2 taza de maíz dulce</li>
                        <li>1 tomate picado</li>
                        <li>lechuga picada</li>
                        <li>1 cda de mayonesa ligera</li>
                        <li>Jugo de limón al gusto</li>
                        <li>Galletas saladas para acompañar</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Rápida</span>
                    <span class="tag">Sin cocción</span>
                    <span class="tag">Proteínas</span>
                </div>
            </div>
        </article>

        <!-- Recipe Card 3 -->
        <article class="recipe-card">
            <img src="/imagenes/pasta_ajo.jpg" alt="Pasta al ajo" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Pasta al Ajo y Aceite</h2>
                <div class="recipe-meta">
                    <span>15 min</span>
                    <span>400 kcal por porción</span>
                </div>
                <p class="recipe-description">Receta italiana clásica con solo 4 ingredientes.</p>
                <div class="ingredients">
                    <h3>Ingredientes (2 porciones):</h3>
                    <ul>
                        <li>200g de espagueti</li>
                        <li>4 dientes de ajo picados</li>
                        <li>3 cdas de aceite de oliva</li>
                        <li>Perejil fresco picado</li>
                        <li>Sal y pimienta al gusto</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Rápida</span>
                    <span class="tag">Italiana</span>
                    <span class="tag">Vegetariana</span>
                </div>
            </div>
        </article>

        <!-- Recipe Card 4 -->
        <article class="recipe-card">
            <img src="/imagenes/chanwi_queso.jpg" alt="Sándwich de queso" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Sándwich de Qeso a la Plancha</h2>
                <div class="recipe-meta">
                    <span>10 min</span>
                    <span>380 kcal por porción</span>
                </div>
                <p class="recipe-description">Versión mejorada del clásico sándwich de queso.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>2 rebanadas de pan de molde</li>
                        <li>2 rebanadas de queso cheddar o americano</li>
                        <li>1 cda de mantequilla</li>
                        <li>1 cdta de mostaza (opcional)</li>
                        <li>1 rebanada de jamón (opcional)</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Rápida</span>
                    <span class="tag">Comfort food</span>
                    <span class="tag">Fácil</span>
                </div>
            </div>
        </article>

        <!-- Recipe Card 5 -->
        <article class="recipe-card">
            <img src="/imagenes/tostadas_frijol.jpg" alt="Tostadas de frijol" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Tostadas de Frijol con Queso</h2>
                <div class="recipe-meta">
                    <span>10 min</span>
                    <span>350 kcal por porción</span>
                </div>
                <p class="recipe-description">Snack mexicano rápido y satisfactorio, perfecto para una cena ligera.</p>
                <div class="ingredients">
                    <h3>Ingredientes (2 porciones):</h3>
                    <ul>
                        <li>4 tostadas de maíz</li>
                        <li>1/2 taza de frijoles refritos</li>
                        <li>1/4 taza de queso fresco desmenuzado</li>
                        <li>2 cdas de crema ácida</li>
                        <li>Lechuga picada</li>
                        <li>Salsa verde o roja al gusto</li>
                        <li>Rodajas de rábano (opcional)</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Rápida</span>
                    <span class="tag">Mexicana</span>
                    <span class="tag">Vegetariana</span>
                </div>
            </div>
        </article>

        <!-- Recipe Card 6 -->
        <article class="recipe-card">
            <img src="/imagenes/tacos_tinga.jpg" alt="Tacos de tinga saludables" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Tacos de Tinga de Pollo Rápidos</h2>
                <div class="recipe-meta">
                    <span>15 min</span>
                    <span>380 kcal por porción</span>
                </div>
                <p class="recipe-description">Versión saludable del clásico mexicano, lista en minutos con ingredientes
                    sencillos.</p>
                <div class="ingredients">
                    <h3>Ingredientes (2 porciones):</h3>
                    <ul>
                        <li>1 pechuga de pollo cocida y desmenuzada</li>
                        <li>4 tortillas de maíz azul</li>
                        <li>1/2 taza de salsa de tomate natural</li>
                        <li>1 cebolla fileteada</li>
                        <li>1 chile chipotle en adobo (o 1/2 cdta de polvo de chipotle)</li>
                        <li>1 cdta de aceite de aguacate</li>
                        <li>1/2 aguacate en rebanadas</li>
                        <li>1 taza de repollo morado rallado</li>
                        <li>Limón y cilantro al gusto</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Rápida</span>
                    <span class="tag">Mexicana</span>
                    <span class="tag">Proteínas</span>
                </div>
            </div>
        </article>
    </main>
</body>

</html>