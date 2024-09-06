<?php
include __DIR__ . '/../db.php'; // Incluye la conexión a la base de datos

// Consulta SQL para obtener noticias y datos del autor
$sql = "SELECT noticias.titulo, noticias.fecha, noticias.texto, noticias.imagen, users_data.nombre, users_data.apellidos 
        FROM noticias 
        JOIN users_data ON noticias.idUser = users_data.idUser";
$result = $conn->query($sql);

// Si hay un error en la consulta, muestra un mensaje y detiene el script
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Noticias</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style_notice.css">
</head>

<body>
    <!-- Encabezado con el título del sitio y el menú de navegación -->
    <header>
        <h1 class="text_header">Bienvenidos a Forza Motors</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../views/noticias.php" class="active">Noticias</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="../views/citaciones.php">Citaciones</a></li>
                    <li><a href="../views/perfil.php">Perfil</a></li>
                    <?php if ($_SESSION['rol'] === 'admin'): ?>
                        <li><a href="../views/usuarios-administracion.php">Administración</a></li>
                    <?php endif; ?>
                    <li><a href="../add/logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="../views/registro.php">Registro</a></li>
                    <li><a href="../views/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <h1>Noticias</h1>
    <main>
        <?php
        // Verifica si hay noticias en la base de datos
        if ($result->num_rows > 0) {
            // Muestra cada noticia
            while ($row = $result->fetch_assoc()) {
                echo "<div class='noticia'>";
                echo "<h2>" . htmlspecialchars($row['titulo']) . "</h2>";
                echo "<p><strong>Fecha de Publicación:</strong> " . htmlspecialchars($row['fecha']) . "</p>";
                echo "<p><strong>Autor:</strong> " . htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellidos']) . "</p>";
                echo "<p>" . htmlspecialchars($row['texto']) . "</p>";
                // Muestra la imagen si existe
                if (!empty($row['imagen'])) {
                    echo "<img src='../uploads/" . htmlspecialchars($row['imagen']) . "' alt='Foto de la noticia'>";
                }
                echo "</div>";
                echo "<hr>";
            }
        } else {
            // Mensaje si no hay noticias
            echo "<p>No hay noticias disponibles.</p>";
        }
        $conn->close(); // Cierra la conexión a la base de datos
        ?>
        <button id="scrollToTopBtn">↑</button>
    </main>

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

    <script src="../js/script.js"></script>
</body>

</html>