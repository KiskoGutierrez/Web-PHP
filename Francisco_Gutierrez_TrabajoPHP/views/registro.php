<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Vincula las hojas de estilo para el diseño de la página -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style_register.css">
</head>

<body>

    <header>
        <h1 class="text_header">Bienvenidos a Forza Motors</h1>
        <nav>
            <ul>
                <!-- Enlaces de navegación principales -->
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../views/noticias.php">Noticias</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="../views/citaciones.php">Citaciones</a></li>
                    <li><a href="../views/perfil.php">Perfil</a></li>
                    <?php if ($_SESSION['rol'] === 'admin'): ?>
                        <li><a href="../views/usuarios-administracion.php">Administración</a></li>
                    <?php endif; ?>
                    <li><a href="../add/logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="../views/registro.php" class="active">Registro</a></li>
                    <li><a href="../views/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <div class="signin">
            <div class="content">
                <h2>Registro</h2>

                <!-- Mostrar mensajes de error o éxito si existen -->
                <?php
                if (isset($_GET['error'])) {
                    echo '<div class="error">' . htmlspecialchars($_GET['error']) . '</div>';
                } elseif (isset($_GET['message'])) {
                    echo '<div class="message">' . htmlspecialchars($_GET['message']) . '</div>';
                }
                ?>

                <!-- Formulario de registro -->
                <form class="form" action="../views/procesar_registro.php" method="post">
                    <div class="inputBox">
                        <input type="text" id="name" name="name" required>
                        <i>Nombre</i>
                    </div>
                    <div class="inputBox">
                        <input type="text" id="apellidos" name="apellidos" required>
                        <i>Apellidos</i>
                    </div>
                    <div class="inputBox">
                        <input type="email" id="email" name="email" required>
                        <i>Correo electrónico</i>
                    </div>
                    <div class="inputBox">
                        <input type="tel" id="phone" name="phone" required>
                        <i>Teléfono</i>
                    </div>
                    <div class="inputBox">
                        <input class="input-1" type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                        <i>Fecha de nacimiento</i>
                    </div>
                    <div class="inputBox">
                        <input type="text" id="address" name="address" required>
                        <i>Dirección</i>
                    </div>
                    <div class="inputBox">
                        <input type="text" id="sexo" name="sexo" required>
                        <i>Sexo</i>
                    </div>
                    <div class="inputBox">
                        <input type="text" id="username" name="username" required>
                        <i>Nombre de usuario</i>
                    </div>
                    <div class="inputBox">
                        <input type="password" id="password" name="password" required>
                        <i>Contraseña</i>
                    </div>
                    <div class="inputBox">
                        <input type="submit" name="register" value="Registrarse">
                    </div>
                </form>
                <!-- Enlace para redirigir a los usuarios que ya tienen cuenta -->
                <div class="links">
                    <a href="../views/login.php">¿Ya tienes una cuenta? Inicia sesión aquí</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Sección de pie de página -->
    <footer>
        <p>Siguenos en nuestras redes sociales: </p>
        <div class="social-ft">
            <div class="social">
                <a href="https://twitter.com" target="_blank">
                    <img src="../images/Twitter.svg" alt="logo-twitter" />
                </a>
            </div>
            <div class="social">
                <a href="https://youtube.com" target="_blank">
                    <img src="../images/youtube.svg" alt="logo-youtube" />
                </a>
            </div>
            <div class="social">
                <a href="https://facebook.com" target="_blank">
                    <img src="../images/FAcebook.svg" alt="logo-facebook" />
                </a>
            </div>
            <div class="social">
                <a href="https://instagram.com" target="_blank">
                    <img src="../images/Instagram.svg" alt="logo-instagram" />
                </a>
            </div>
        </div>
        <p class="ft-1">&copy; 2024 Todos los derechos reservados</p>
    </footer>

    <!-- Enlace al archivo de JavaScript -->
    <script src="../js/script.js"></script>
</body>

</html>