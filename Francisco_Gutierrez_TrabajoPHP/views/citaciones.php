<?php
session_start(); // Inicia la sesión del usuario

// Redirige al usuario a la página de inicio de sesión si no está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: ../views/login.php");
    exit();
}

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "noticias_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica si la conexión a la base de datos fue exitosa
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username']; // Obtiene el nombre de usuario desde la sesión

// Consulta para obtener el ID del usuario basado en el nombre de usuario
$result = $conn->query("SELECT idUser FROM users_login WHERE usuario='$username'");
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $idUser = $row['idUser'];
} else {
    echo "Error: Usuario no encontrado.";
    exit();
}

// Consulta para obtener las citas del usuario
$sql = "SELECT * FROM citas WHERE idUser='$idUser'";
$result = $conn->query($sql);

$citas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $citas[] = $row; // Almacena las citas en un array
    }
}

$conn->close(); // Cierra la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citaciones</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Enlaza el archivo de estilos principal -->
    <link rel="stylesheet" href="../css/style_citaciones.css"> <!-- Enlaza el archivo de estilos específicos para citas -->
</head>

<body>
    <header>
        <h1>Gestión de Citaciones</h1> <!-- Título principal -->
        <nav>
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../views/noticias.php">Noticias</a></li>
                <li><a href="../views/citaciones.php" class="active">Citaciones</a></li>
                <li><a href="../views/perfil.php">Perfil</a></li>
                <li><a href="../add/logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Solicitar Nueva Cita</h2>
        <form class="form-1" action="../add/agregar_cita.php" method="post">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>

            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" required>

            <input class="btn" type="submit" value="Solicitar Cita">
        </form>
    </section>

    <section>
        <h2>Mis Citaciones</h2>
        <?php if (!empty($citas)) : ?>
            <ul>
                <?php foreach ($citas as $cita) : ?>
                    <li class="form-2">
                        <?php echo htmlspecialchars($cita['fecha_cita']) . ' - ' . htmlspecialchars($cita['motivo_cita']); ?>
                        <?php if (strtotime($cita['fecha_cita']) >= time()) : ?>
                            <form action="../add/modificar_cita.php" method="post" style="display:inline;">
                                <input type="hidden" name="idCita" value="<?php echo $cita['idCita']; ?>">
                                <input type="date" name="fecha" value="<?php echo htmlspecialchars($cita['fecha_cita']); ?>" required>
                                <input type="text" name="descripcion" value="<?php echo htmlspecialchars($cita['motivo_cita']); ?>" required>
                                <input type="submit" value="Modificar">
                            </form>
                            <form action="../add/borrar_cita.php" method="post" style="display:inline;">
                                <input type="hidden" name="idCita" value="<?php echo $cita['idCita']; ?>">
                                <input type="submit" value="Borrar">
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>No tienes citas programadas.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>Síguenos en nuestras redes sociales:</p>
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
        <p class="ft-1">&copy; 2024 Todos los derechos reservados</p>
    </footer>
</body>

</html>