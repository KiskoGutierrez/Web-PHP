<?php
// Configuración de los datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "noticias_db";

// Creación de una nueva conexión a la base de datos usando MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificación de si la conexión fue exitosa
if ($conn->connect_error) {
    // Si hay un error de conexión, se muestra un mensaje y se detiene el script
    die("Conexión fallida: " . $conn->connect_error);
}
?>
