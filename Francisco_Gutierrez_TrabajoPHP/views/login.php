<?php
session_start(); // Inicia la sesión para gestionar el estado del usuario
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style_login.css"> <!-- Estilo específico para la página de login -->
    <link rel="stylesheet" href="../css/style.css"> <!-- Estilo general para el sitio -->
</head>

<body>
    <!-- Cabecera del sitio con menú de navegación -->
    <header>
        <h1 class="text_header">Bienvenidos a Forza Motors</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../views/noticias.php">Noticias</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Menú para usuarios autenticados -->
                    <li><a href="../views/citaciones.php">Citaciones</a></li>
                    <li><a href="../views/perfil.php">Perfil</a></li>
                    <?php if ($_SESSION['rol'] === 'admin'): ?>
                        <li><a href="../views/usuarios-administracion.php">Administración</a></li>
                    <?php endif; ?>
                    <li><a href="../add/logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                    <!-- Menú para usuarios no autenticados -->
                    <li><a href="../views/registro.php">Registro</a></li>
                    <li><a href="../views/login.php" class="active">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <section>
        <div class="container">
            <div class="signin">
                <div class="content">
                    <h2>Login</h2>
                    <!-- Formulario de inicio de sesión -->
                    <form class="form" action="../views/procesar_login.php" method="post">
                        <div class="inputBox">
                            <input type="text" id="username" name="username" required>
                            <i>Nombre de usuario</i>
                        </div>
                        <div class="inputBox">
                            <input type="password" id="password" name="password" required>
                            <i>Contraseña</i>
                        </div>
                        <div class="links">
                            <!-- Enlaces para recuperar contraseña y registro -->
                            <a href="#">¿Olvidaste tu contraseña?</a>
                            <a href="../views/registro.php">Regístrate</a>
                        </div>
                        <div class="inputBox">
                            <input type="submit" value="Login">
                        </div>
                    </form>
                </div>
            </div>
    </section>

    <!-- Pie de página con enlaces a redes sociales -->
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
</body>

</html>