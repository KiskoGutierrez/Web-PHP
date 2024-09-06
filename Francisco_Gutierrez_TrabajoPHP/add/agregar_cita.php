<?php
// Inicia la sesión para acceder a las variables de sesión.
session_start();

// Verifica si el usuario ha iniciado sesión; si no, redirige a la página de login.
if (!isset($_SESSION['username'])) {
    header("Location: ../views/login.php");
    exit(); // Termina la ejecución del script después de redirigir.
}

// Comprueba si el formulario se ha enviado utilizando el método POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Configura los detalles de la conexión a la base de datos.
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "noticias_db";

    // Crea una nueva conexión a la base de datos.
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica si hubo un error al conectarse a la base de datos.
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Detiene el script si la conexión falla.
    }

    // Obtiene el nombre de usuario de la sesión.
    $username = $_SESSION['username'];

    // Escapa los datos recibidos del formulario para proteger contra inyecciones SQL.
    $fecha = $conn->real_escape_string($_POST['fecha']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    // Busca el ID del usuario en la base de datos usando el nombre de usuario.
    $result = $conn->query("SELECT idUser FROM users_login WHERE usuario='$username'");

    // Verifica si se encontró exactamente un resultado.
    if ($result->num_rows == 1) {
        // Extrae el ID del usuario del resultado de la consulta.
        $row = $result->fetch_assoc();
        $idUser = $row['idUser'];

        // Prepara la consulta SQL para insertar la nueva cita en la base de datos.
        $sql = "INSERT INTO citas (idUser, fecha_cita, motivo_cita) VALUES ('$idUser', '$fecha', '$descripcion')";

        // Ejecuta la consulta e informa si fue exitosa o si ocurrió un error.
        if ($conn->query($sql) === TRUE) {
            echo "Cita agregada correctamente"; // Mensaje de éxito.
        } else {
            echo "Error agregando la cita: " . $conn->error; // Mensaje de error si la inserción falla.
        }
    } else {
        // Mensaje de error si no se encontró el usuario en la base de datos.
        echo "Error: Usuario no encontrado.";
    }

    // Cierra la conexión a la base de datos.
    $conn->close();
}

// Redirige al usuario a la página de citas después de completar la operación.
header("Location: ../views/citaciones.php");
