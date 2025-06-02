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
            <input type="text" class="search-bar" placeholder="Buscar recetas...">
            <div class="filters">
                <button class="filter-btn active">Todas</button>
                <a href="desayunos.php"><button class="filter-btn">Desayunos</button></a>
                <a href="almuerzos.php"><button class="filter-btn">Almuerzos</button></a>
                <a href="cenas.php"><button class="filter-btn">Cenas</button></a>
                <a href="vegetarianas.php"><button class="filter-btn">Vegetarianas</button></a>
                <a href="rapidas.php"><button class="filter-btn">Rápidas</button></a>
            </div>
        </div>
    </section>

    <!-- Recipes Grid -->
    <main class="recipes-grid">
        <!-- Recetas generales -->
        <article class="recipe-card">
            <img src="imagenes/bowl_avena.jpg" alt="Bowl de avena" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Bowl de Avena con Frutas</h2>
                <div class="recipe-meta">
                    <span>15 min</span>
                    <span>350 kcal por porción</span>
                </div>
                <p class="recipe-description">Un desayuno nutritivo y energético.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>1 taza de avena</li>
                        <li>2 tazas de leche o agua</li>
                        <li>1 plátano</li>
                        <li>1/2 taza de fresas</li>
                        <li>1 cucharada de miel</li>
                        <li>Almendras al gusto</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Desayuno</span>
                    <span class="tag">Saludable</span>
                    <span class="tag">Vegetariano</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/ensalada_mediterranea.jpg" alt="Ensalada de Quinoa" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Ensalada de Quinoa Mediterránea</h2>
                <div class="recipe-meta">
                    <span>25 min</span>
                    <span>420 kcal por porción</span>
                </div>
                <p class="recipe-description">Una ensalada completa llena de proteínas y vegetales.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>1 taza de quinoa</li>
                        <li>2 tomates medianos</li>
                        <li>1 pepino</li>
                        <li>1/2 taza de aceitunas</li>
                        <li>200g de queso feta</li>
                        <li>Aceite de oliva</li>
                        <li>Jugo de limón</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Almuerzo</span>
                    <span class="tag">Vegetariano</span>
                    <span class="tag">Saludable</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/pollo_verduras.jpg" alt="Pollo con Verduras" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Pollo al Horno con Verduras</h2>
                <div class="recipe-meta">
                    <span>45 min</span>
                    <span>480 kcal por porción</span>
                </div>
                <p class="recipe-description">Una cena saludable y completa rica en proteínas.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>2 pechugas de pollo</li>
                        <li>2 zanahorias</li>
                        <li>2 papas medianas</li>
                        <li>1 pimiento rojo</li>
                        <li>1 cebolla</li>
                        <li>3 dientes de ajo</li>
                        <li>Aceite de oliva</li>
                        <li>Hierbas provenzales</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Cena</span>
                    <span class="tag">Saludable</span>
                    <span class="tag">Alto en proteína</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/curry.jpg" alt="Curry de Garbanzos" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Curry de Garbanzos con Espinacas</h2>
                <div class="recipe-meta">
                    <span>30 min</span>
                    <span>380 kcal por porción</span>
                </div>
                <p class="recipe-description">Un plato vegetariano rico en proteínas y fibra.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>2 latas de garbanzos</li>
                        <li>300g de espinacas frescas</li>
                        <li>1 lata de leche de coco</li>
                        <li>1 cebolla</li>
                        <li>3 dientes de ajo</li>
                        <li>2 cucharadas de curry en polvo</li>
                        <li>1 lata de tomate triturado</li>
                        <li>Jengibre fresco</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Cena</span>
                    <span class="tag">Vegetariano</span>
                    <span class="tag">Económico</span>
                </div>
            </div>
        </article>

        <!-- Desayunos -->
        <article class="recipe-card">
            <a href="desayunos/desayuno1.php"><img src="imagenes/pancakes.jpg" alt="Pancakes de Plátano" class="recipe-image"></a>
            <div class="recipe-content">
                <h2 class="recipe-title">Pancakes de Plátano y Avena</h2>
                <div class="recipe-meta">
                    <span>20 min</span>
                    <span>320 kcal por porción</span>
                </div>
                <p class="recipe-description">Pancakes esponjosos y saludables, perfectos para empezar el día.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>2 plátanos maduros</li>
                        <li>1 taza de avena molida</li>
                        <li>2 huevos</li>
                        <li>1/2 taza de leche</li>
                        <li>1 cdta de canela</li>
                        <li>1 cdta de polvo para hornear</li>
                        <li>Miel para decorar</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Desayuno</span>
                    <span class="tag">Saludable</span>
                    <span class="tag">Sin Azúcar</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <a href="desayunos/desayuno2.php"><img src="imagenes/huevos_rancheros.jpg" alt="Huevos Rancheros" class="recipe-image"></a>
            <div class="recipe-content">
                <h2 class="recipe-title">Huevos Rancheros Mexicanos</h2>
                <div class="recipe-meta">
                    <span>25 min</span>
                    <span>450 kcal por porción</span>
                </div>
                <p class="recipe-description">Un desayuno mexicano tradicional lleno de sabor y proteínas.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>4 huevos</li>
                        <li>4 tortillas de maíz</li>
                        <li>2 tomates</li>
                        <li>1 cebolla</li>
                        <li>2 chiles verdes</li>
                        <li>Frijoles refritos</li>
                        <li>Aguacate</li>
                        <li>Queso rallado</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Desayuno</span>
                    <span class="tag">Mexicano</span>
                    <span class="tag">Alto en proteína</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <a href="desayunos/desayuno3.php"><img src="imagenes/licuado_verde.png" alt="Smoothie Verde" class="recipe-image"></a>
            <div class="recipe-content">
                <h2 class="recipe-title">Smoothie Verde Energético</h2>
                <div class="recipe-meta">
                    <span>10 min</span>
                    <span>250 kcal por porción</span>
                </div>
                <p class="recipe-description">Bebida nutritiva llena de vitaminas y minerales.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>2 tazas de espinacas</li>
                        <li>1 plátano</li>
                        <li>1 manzana verde</li>
                        <li>1 taza de piña</li>
                        <li>1 cdta de jengibre</li>
                        <li>1 taza de agua de coco</li>
                        <li>1 cdta de semillas de chía</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Desayuno</span>
                    <span class="tag">Bebida</span>
                    <span class="tag">Detox</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <a href="desayunos/desayuno4.php"><img src="imagenes/parfait.jpg" alt="Parfait de Yogur" class="recipe-image"></a>
            <div class="recipe-content">
                <h2 class="recipe-title">Parfait de Yogur y Granola</h2>
                <div class="recipe-meta">
                    <span>15 min</span>
                    <span>380 kcal por porción</span>
                </div>
                <p class="recipe-description">Un desayuno fresco y crujiente rico en probióticos.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>2 tazas de yogur griego</li>
                        <li>1/2 taza de granola casera</li>
                        <li>1 taza de frutos rojos</li>
                        <li>2 cdas de miel</li>
                        <li>1/4 taza de almendras</li>
                        <li>Semillas de chía</li>
                        <li>Coco rallado</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Desayuno</span>
                    <span class="tag">Sin Cocción</span>
                    <span class="tag">Rico en Proteínas</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <a href="desayunos/desayuno5.php"><img src="imagenes/tostadas_huevo.jpg" alt="Tostadas de Aguacate" class="recipe-image"></a>
            <div class="recipe-content">
                <h2 class="recipe-title">Tostadas de Aguacate y Huevo</h2>
                <div class="recipe-meta">
                    <span>15 min</span>
                    <span>340 kcal por porción</span>
                </div>
                <p class="recipe-description">El clásico desayuno saludable con grasas buenas y proteínas.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>2 rebanadas de pan integral</li>
                        <li>1 aguacate maduro</li>
                        <li>2 huevos</li>
                        <li>Tomates cherry</li>
                        <li>Brotes de alfalfa</li>
                        <li>Semillas de sésamo</li>
                        <li>Sal y pimienta</li>
                        <li>Chile en hojuelas</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Desayuno</span>
                    <span class="tag">Saludable</span>
                    <span class="tag">Vegetariano</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <a href="desayunos/desayuno6.php"><img src="imagenes/burrito_proteico.jpg" alt="Burrito de Desayuno" class="recipe-image"></a>
            <div class="recipe-content">
                <h2 class="recipe-title">Burrito de Desayuno Proteico</h2>
                <div class="recipe-meta">
                    <span>20 min</span>
                    <span>520 kcal por porción</span>
                </div>
                <p class="recipe-description">Un burrito sustancioso perfecto para deportistas.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>2 tortillas de trigo integral</li>
                        <li>4 huevos revueltos</li>
                        <li>1/2 taza de frijoles negros</li>
                        <li>Queso cheddar rallado</li>
                        <li>1 pimiento rojo</li>
                        <li>1 aguacate</li>
                        <li>Salsa picante</li>
                        <li>Cilantro fresco</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Desayuno</span>
                    <span class="tag">Alto en Proteína</span>
                    <span class="tag">Para Deportistas</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <a href="desayunos/desayuno7.php"><img src="imagenes/bowl_avena.jpg" alt="Bowl de avena" class="recipe-image"></a>
            <div class="recipe-content">
                <h2 class="recipe-title">Bowl de Avena con Frutas</h2>
                <div class="recipe-meta">
                    <span>15 min</span>
                    <span>350 kcal por porción</span>
                </div>
                <p class="recipe-description">Un desayuno nutritivo y energético.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>1 taza de avena</li>
                        <li>2 tazas de leche o agua</li>
                        <li>1 plátano</li>
                        <li>1/2 taza de fresas</li>
                        <li>1 cucharada de miel</li>
                        <li>Almendras al gusto</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Desayuno</span>
                    <span class="tag">Saludable</span>
                    <span class="tag">Vegetariano</span>
                </div>
            </div>
        </article>

        <!-- Almuerzos -->
        <article class="recipe-card">
            <img src="imagenes/pollo_limon.jpg" alt="Pollo con Arroz" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Pollo al Limón con Arroz</h2>
                <div class="recipe-meta">
                    <span>30 min</span>
                    <span>450 kcal por porción</span>
                </div>
                <p class="recipe-description">Muslo de pollo jugoso con arroz y vegetales.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>1 muslo de pollo</li>
                        <li>1/2 taza de arroz</li>
                        <li>1 zanahoria</li>
                        <li>1/2 cebolla</li>
                        <li>1 limón</li>
                        <li>2 dientes de ajo</li>
                        <li>Sal y pimienta</li>
                        <li>Hierbas secas</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Almuerzo</span>
                    <span class="tag">Económico</span>
                    <span class="tag">Proteína</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/pasta_atun.jpg" alt="Pasta con Atún" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Pasta con Atún y Verduras</h2>
                <div class="recipe-meta">
                    <span>20 min</span>
                    <span>420 kcal por porción</span>
                </div>
                <p class="recipe-description">Pasta con atún, perfecta para un almuerzo rápido y nutritivo.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>1 lata de atún en agua</li>
                        <li>100g de pasta (fusilli o penne)</li>
                        <li>1 tomate</li>
                        <li>1/4 cebolla</li>
                        <li>1 diente de ajo</li>
                        <li>Aceite de oliva</li>
                        <li>Orégano seco</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Almuerzo</span>
                    <span class="tag">Económico</span>
                    <span class="tag">Rápido</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/pescado_horno.jpg" alt="Pescado al Horno" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Filete de Pescado al Horno</h2>
                <div class="recipe-meta">
                    <span>25 min</span>
                    <span>380 kcal por porción</span>
                </div>
                <p class="recipe-description">Pescado blanco al horno con verduras de temporada.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>1 filete de tilapia (150g)</li>
                        <li>2 papas medianas</li>
                        <li>1 zanahoria</li>
                        <li>1/2 cebolla</li>
                        <li>1 diente de ajo</li>
                        <li>Limón</li>
                        <li>Aceite y perejil</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Almuerzo</span>
                    <span class="tag">Económico</span>
                    <span class="tag">Saludable</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/burgir.jpg" alt="Hamburguesa Casera" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Hamburguesa Casera con Papas</h2>
                <div class="recipe-meta">
                    <span>25 min</span>
                    <span>520 kcal por porción</span>
                </div>
                <p class="recipe-description">Hamburguesa casera con papas al horno.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>150g de carne molida</li>
                        <li>1 pan de hamburguesa</li>
                        <li>2 papas medianas</li>
                        <li>Queso americano</li>
                        <li>Lechuga y tomate</li>
                        <li>1/4 cebolla</li>
                        <li>Condimentos</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Almuerzo</span>
                    <span class="tag">Económico</span>
                    <span class="tag">Casero</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/ensalada_atun.jpg" alt="Ensalada de Atún" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Ensalada Completa de Atún</h2>
                <div class="recipe-meta">
                    <span>15 min</span>
                    <span>350 kcal por porción</span>
                </div>
                <p class="recipe-description">Ensalada fresca y nutritiva con atún.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>1 lata de atún en agua</li>
                        <li>2 huevos cocidos</li>
                        <li>1 tomate</li>
                        <li>Lechuga</li>
                        <li>1/2 taza de maíz</li>
                        <li>1/4 cebolla</li>
                        <li>Aceite de oliva</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Almuerzo</span>
                    <span class="tag">Económico</span>
                    <span class="tag">Sin Cocción</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/salteado_pollo.jpg" alt="Pollo con Verduras" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Salteado de Pollo con Verduras</h2>
                <div class="recipe-meta">
                    <span>20 min</span>
                    <span>400 kcal por porción</span>
                </div>
                <p class="recipe-description">Salteado rápido y nutritivo de pollo con verduras.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>150g de pechuga de pollo</li>
                        <li>1 zanahoria</li>
                        <li>1 pimiento</li>
                        <li>1/2 cebolla</li>
                        <li>Brócoli pequeño</li>
                        <li>Salsa de soja</li>
                        <li>Aceite vegetal</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Almuerzo</span>
                    <span class="tag">Económico</span>
                    <span class="tag">Saludable</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/ensalada_mediterranea.jpg" alt="Ensalada de Quinoa" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Ensalada de Quinoa Mediterránea</h2>
                <div class="recipe-meta">
                    <span>25 min</span>
                    <span>420 kcal por porción</span>
                </div>
                <p class="recipe-description">Una ensalada completa llena de proteínas y vegetales.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>1 taza de quinoa</li>
                        <li>2 tomates medianos</li>
                        <li>1 pepino</li>
                        <li>1/2 taza de aceitunas</li>
                        <li>200g de queso feta</li>
                        <li>Aceite de oliva</li>
                        <li>Jugo de limón</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Almuerzo</span>
                    <span class="tag">Vegetariano</span>
                    <span class="tag">Saludable</span>
                </div>
            </div>
        </article>

        <!-- Cenas -->
        <article class="recipe-card">
            <img src="imagenes/pollo_limon2.jpg" alt="Pechuga de pollo al limón" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Pechuga de Pollo al Limón con Vegetales</h2>
                <div class="recipe-meta">
                    <span>30 min</span>
                    <span>350 kcal por porción</span>
                </div>
                <p class="recipe-description">Pollo jugoso con una salsa cítrica y acompañamiento de vegetales al vapor.</p>
                <div class="ingredients">
                    <h3>Ingredientes (2 porciones):</h3>
                    <ul>
                        <li>2 pechugas de pollo</li>
                        <li>1 limón (jugo y ralladura)</li>
                        <li>2 dientes de ajo picados</li>
                        <li>1 cda de miel</li>
                        <li>1 cda de aceite de oliva</li>
                        <li>1 taza de brócoli</li>
                        <li>1 zanahoria mediana</li>
                        <li>1/2 pimiento rojo</li>
                        <li>Sal y pimienta al gusto</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Cena</span>
                    <span class="tag">Pollo</span>
                    <span class="tag">Bajo en carbohidratos</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/pescado_ajijo.jpg" alt="Tilapia al ajillo" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Filete de Tilapia al Ajillo</h2>
                <div class="recipe-meta">
                    <span>25 min</span>
                    <span>380 kcal por porción</span>
                </div>
                <p class="recipe-description">Pescado blanco suave con sabrosa salsa de ajo y perejil.</p>
                <div class="ingredients">
                    <h3>Ingredientes:</h3>
                    <ul>
                        <li>2 filetes de tilapia</li>
                        <li>3 dientes de ajo picados</li>
                        <li>2 cdas de mantequilla</li>
                        <li>1 cda de aceite de oliva</li>
                        <li>1/2 taza de vino blanco (opcional)</li>
                        <li>1/4 taza de perejil fresco</li>
                        <li>1 taza de arroz blanco cocido</li>
                        <li>Limón para servir</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Cena</span>
                    <span class="tag">Pescado</span>
                    <span class="tag">Rápida</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/cesar.jpg" alt="Ensalada César de pollo" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Ensalada César con Pollo a la Parrilla</h2>
                <div class="recipe-meta">
                    <span>25 min</span>
                    <span>450 kcal por porción</span>
                </div>
                <p class="recipe-description">Clásica ensalada César con crujientes croutons y jugoso pollo asado.</p>
                <div class="ingredients">
                    <h3>Ingredientes (2 porciones):</h3>
                    <ul>
                        <li>1 pechuga de pollo</li>
                        <li>1 cabeza de lechuga romana</li>
                        <li>1/2 taza de croutons caseros</li>
                        <li>1/4 taza de queso parmesano rallado</li>
                        <li>2 cdas de aceite de oliva</li>
                        <li>1 diente de ajo picado</li>
                        <li>1 cda de mostaza Dijon</li>
                        <li>1 huevo (para aderezo opcional)</li>
                        <li>2 filetes de anchoas (opcional)</li>
                        <li>Jugo de limón al gusto</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Cena</span>
                    <span class="tag">Pollo</span>
                    <span class="tag">Ensalada</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/omellet.jpeg" alt="Omelette de vegetales" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Omelette de Vegetales con Queso</h2>
                <div class="recipe-meta">
                    <span>20 min</span>
                    <span>320 kcal por porción</span>
                </div>
                <p class="recipe-description">Tortilla francesa rellena de vegetales frescos y queso derretido.</p>
                <div class="ingredients">
                    <h3>Ingredientes (2 porciones):</h3>
                    <ul>
                        <li>4 huevos</li>
                        <li>1/4 taza de leche</li>
                        <li>1/2 pimiento rojo picado</li>
                        <li>1/2 cebolla picada</li>
                        <li>1/2 taza de champiñones</li>
                        <li>1/4 taza de queso rallado</li>
                        <li>1 cda de mantequilla</li>
                        <li>Sal, pimienta y orégano al gusto</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Cena</span>
                    <span class="tag">Huevo</span>
                    <span class="tag">Vegetales</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/wrap.png" alt="Wrap de pollo" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Wrap de Pollo a la Parrilla</h2>
                <div class="recipe-meta">
                    <span>25 min</span>
                    <span>400 kcal por porción</span>
                </div>
                <p class="recipe-description">Opción rápida y versátil con proteína magra y salsa de yogur.</p>
                <div class="ingredients">
                    <h3>Ingredientes (2 wraps):</h3>
                    <ul>
                        <li>1 pechuga de pollo</li>
                        <li>2 tortillas integrales grandes</li>
                        <li>1 diente de ajo picado</li>
                        <li>1/2 pepino rallado</li>
                        <li>1 taza de lechuga</li>
                        <li>1 tomate en rodajas</li>
                        <li>1 cda de aceite de oliva</li>
                        <li>Especias para el pollo (pimentón, comino)</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Cena</span>
                    <span class="tag">Pollo</span>
                    <span class="tag">Rápida</span>
                </div>
            </div>
        </article>

        <article class="recipe-card">
            <img src="imagenes/salmon.jpg" alt="Salmón con verduras" class="recipe-image">
            <div class="recipe-content">
                <h2 class="recipe-title">Salmón con Verduras al Horno</h2>
                <div class="recipe-meta">
                    <span>35 min</span>
                    <span>450 kcal por porción</span>
                </div>
                <p class="recipe-description">Opción premium rica en omega-3 con acompañamiento de vegetales asados.</p>
                <div class="ingredients">
                    <h3>Ingredientes (2 porciones):</h3>
                    <ul>
                        <li>2 filetes de salmón (150g cada uno)</li>
                        <li>1 calabacín en rodajas</li>
                        <li>1 pimiento rojo en tiras</li>
                        <li>1/2 cebolla morada en rodajas</li>
                        <li>2 cdas de aceite de oliva</li>
                        <li>Jugo de 1 limón</li>
                        <li>Sal, pimienta y eneldo al gusto</li>
                        <li>1 taza de arroz integral cocido (opcional)</li>
                    </ul>
                </div>
                <div class="recipe-tags">
                    <span class="tag">Cena</span>
                    <span class="tag">Salmón</span>
                    <span class="tag">Saludable</span>
                </div>
            </div>
        </article>
        