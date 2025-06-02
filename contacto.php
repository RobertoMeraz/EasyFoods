<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Foods - Contacto</title>
    <link rel="stylesheet" href="estilos/estilo_contacto.css">
</head>
<body>
<?php include 'header.php'; ?>
    <!-- Contact Section -->
    <main>
        <section class="contact-section">
            <div class="contact-container">
                <div class="contact-info">
                    <h2>Contacta con Nosotros</h2>
                    <p class="contact-description">¿Tienes alguna pregunta o sugerencia? Nos encantaría escucharte. Completa el formulario y nos pondremos en contacto contigo lo antes posible.</p>
                    
                    <div class="contact-methods">
                        <div class="contact-method">
                            <i class="contact-icon">📍</i>
                            <div class="method-details">
                                <h3>Ubicacion</h3>
                                <p>Universidad Autonoma de Baja California (FIAD)<br>Ensenda, Mexico</p>
                            </div>
                        </div>
                        <div class="contact-method">
                            <i class="contact-icon">📧</i>
                            <div class="method-details">
                                <h3>Email</h3>
                                <p>easyfoods_support@gmail.com</p>
                            </div>
                        </div>
                        <div class="contact-method">
                            <i class="contact-icon">📞</i>
                            <div class="method-details">
                                <h3>Teléfono</h3>
                                <p>+52 646 666 6666</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-form-container">
                    <form class="contact-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <input type="text" id="apellido" name="apellido" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="asunto">Asunto</label>
                            <select id="asunto" name="asunto" required>
                                <option value="">Selecciona un asunto</option>
                                <option value="consulta">Consulta General</option>
                                <option value="sugerencia">Sugerencia</option>
                                <option value="recetas">Propuesta de Recetas</option>
                                <option value="soporte">Soporte Técnico</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mensaje">Mensaje</label>
                            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                        </div>
                        <div class="form-terms">
                            <label class="terms-checkbox">
                                <input type="checkbox" name="terms" required>
                                Acepto la <a href="/privacidad">política de privacidad</a>
                            </label>
                        </div>
                        <button type="submit" class="btn primary">Enviar Mensaje</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Easy Foods</h3>
                <p>Haciendo la cocina fácil y accesible para todos.</p>
            </div>
            <div class="footer-section">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="recetas.php">Recetas</a></li>
                    <li><a href="sobre_nosotros.php">Sobre Nosotros</a></li>
                    <li><a href="contacto.php">Contacto</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Síguenos</h3>
                <div class="social-links">
                    <a href="#" class="social-link">Twitter</a>
                    <a href="#" class="social-link">LinkedIn</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Easy Foods. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>