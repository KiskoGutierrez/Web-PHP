<?php
session_start(); // Inicia la sesión para acceder a las variables de sesión

// Redirige al usuario a la página de login si no está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: ../views/login.php");
    exit();
}

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "noticias_db";

// Crear una nueva conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica si la conexión ha fallado
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['username'];

// Consultar la tabla users_login para obtener el idUser
$sql = "SELECT idUser, usuario FROM users_login WHERE usuario='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $login_data = $result->fetch_assoc();
    $idUser = $login_data['idUser'];

    // Ahora consultamos la tabla users_data usando idUser
    $sql_data = "SELECT nombre, apellidos, email, telefono, fecha_nacimiento, direccion, sexo 
                 FROM users_data WHERE idUser='$idUser'";
    $result_data = $conn->query($sql_data);

    if ($result_data->num_rows > 0) {
        $user_data = $result_data->fetch_assoc();
    } else {
        echo "Error: Datos de usuario no encontrados.";
        $conn->close();
        exit();
    }
} else {
    echo "Error: Usuario no encontrado.";
    $conn->close();
    exit();
}

$conn->close(); // Cierra la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style_perfil.css">
</head>
<body>
    <header>
        <h1>Perfil de Usuario</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../views/noticias.php">Noticias</a></li>
                <li><a href="../views/citaciones.php">Citaciones</a></li>
                <li><a href="../views/perfil.php" class="active">Perfil</a></li>
                <li><a href="../add/logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <form action="../add/actualizar_perfil.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $user_data['nombre']; ?>" required>

            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" value="<?php echo $user_data['apellidos']; ?>" required>

            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo $user_data['telefono']; ?>" required>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $user_data['fecha_nacimiento']; ?>" required>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo $user_data['direccion']; ?>">

            <label for="sexo">Sexo:</label>
            <input type="text" id="sexo" name="sexo" value="<?php echo $user_data['sexo']; ?>">

            <label for="usuario">Nombre de Usuario:</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo $login_data['usuario']; ?>" disabled>

            <label for="password">Nueva Contraseña (opcional):</label>
            <input type="password" id="password" name="password">

            <input type="submit" value="Actualizar Perfil">
        </form>
    </main>
</body>
</html>
