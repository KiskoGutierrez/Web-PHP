<?php
// Inicia la sesión para acceder a las variables de sesión.
session_start();

// Verifica si el usuario ha iniciado sesión; si no, lo redirige a la página de login.
if (!isset($_SESSION['username'])) {
    header("Location: ../views/login.php");
    exit(); // Termina la ejecución del script después de la redirección.
}

// Verifica si el formulario se envió mediante el método POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Configura los detalles de la conexión a la base de datos.
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "noticias_db";

    // Crea una nueva conexión a la base de datos.
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica si hubo algún error al conectarse a la base de datos.
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Detiene el script si la conexión falla.
    }

    // Escapa el ID de la cita recibido del formulario para proteger contra inyecciones SQL.
    $idCita = $conn->real_escape_string($_POST['idCita']);

    // Verifica si la cita existe y si su fecha es en el futuro.
    $result = $conn->query("SELECT fecha_cita FROM citas WHERE idCita='$idCita'");
    if ($result->num_rows == 1) {
        // Extrae la fecha de la cita.
        $row = $result->fetch_assoc();
        // Comprueba si la cita es para una fecha futura.
        if (strtotime($row['fecha_cita']) >= time()) {
            // Si la cita es en el futuro, procede a borrarla.
            $sql = "DELETE FROM citas WHERE idCita='$idCita'";
            if ($conn->query($sql) === TRUE) {
                echo "Cita borrada correctamente"; // Mensaje de éxito.
            } else {
                echo "Error borrando la cita: " . $conn->error; // Mensaje de error si ocurre un fallo al borrar.
            }
        } else {
            // Si la cita ya ocurrió, no permite borrarla.
            echo "No se pueden borrar citas pasadas.";
        }
    } else {
        // Si no se encuentra la cita, muestra un mensaje de error.
        echo "Error: Cita no encontrada.";
    }

    // Cierra la conexión a la base de datos.
    $conn->close();
}

// Redirige al usuario de nuevo a la página de citaciones después de la operación.
header("Location: ../views/citaciones.php");
