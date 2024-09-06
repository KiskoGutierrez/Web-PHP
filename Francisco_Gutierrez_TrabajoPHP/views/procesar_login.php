<?php
// Iniciar la sesión para manejar variables de sesión
session_start();

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "noticias_db";

// Crear una conexión con la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si hubo un error al conectar a la base de datos
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

// Recoger los datos enviados por el formulario de login
$username = $_POST['username'];
$password = $_POST['password'];

// Consultar la base de datos para verificar si el usuario existe
$sql = "SELECT u.idUser, l.usuario, l.password, l.rol 
        FROM users_login l 
        JOIN users_data u ON l.idUser = u.idUser 
        WHERE l.usuario='$username'";
$result = $conn->query($sql);

// Comprobar si la consulta SQL ha tenido algún error
if (!$result) {
    die("Error en la consulta SQL: " . $conn->error);
}

// Si el usuario existe, verificar la contraseña
if ($result->num_rows > 0) {
    // Obtener los datos del usuario
    $row = $result->fetch_assoc();
    // Comparar la contraseña ingresada con la contraseña almacenada
    if (password_verify($password, $row['password'])) {
        // Si la contraseña es correcta, guardar la información en la sesión
        $_SESSION['username'] = $username;
        $_SESSION['rol'] = $row['rol'];
        $_SESSION['idUser'] = $row['idUser']; // Guardar el ID del usuario en la sesión

        // Redirigir al usuario a la página principal
        header("Location: ../index.php");
        exit();
    } else {
        // Si la contraseña es incorrecta, mostrar un mensaje de error
        echo "Error: Contraseña incorrecta. <a href='../views/login.php'>Intente de nuevo</a>";
    }
} else {
    // Si el usuario no existe, mostrar un mensaje de error
    echo "Error: Usuario no encontrado. <a href='../views/login.php'>Intente de nuevo</a>";
}

// Cerrar la conexión a la base de datos
$conn->close();
