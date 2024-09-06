<?php
// Inicia la sesión para poder usar las variables de sesión.
session_start();

// Verifica si el usuario ha iniciado sesión; si no, redirige a la página de login.
if (!isset($_SESSION['username'])) {
    header("Location: ../views/login.php");
    exit(); // Termina la ejecución del script después de la redirección.
}

// Comprueba si el formulario se envió utilizando el método POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Configura los detalles de la conexión a la base de datos.
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "noticias_db";

    // Crea una nueva conexión a la base de datos.
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica si hay un error al conectarse a la base de datos.
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Detiene el script si hay un error en la conexión.
    }

    // Escapa los datos recibidos del formulario para proteger contra inyecciones SQL.
    $idCita = $conn->real_escape_string($_POST['idCita']);
    $fecha = $conn->real_escape_string($_POST['fecha']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    // Verifica si la cita existe y si su fecha es en el futuro.
    $result = $conn->query("SELECT fecha_cita FROM citas WHERE idCita='$idCita'");
    if ($result->num_rows == 1) {
        // Extrae la fecha de la cita.
        $row = $result->fetch_assoc();
        // Comprueba si la cita es para una fecha futura.
        if (strtotime($row['fecha_cita']) >= time()) {
            // Si la cita es en el futuro, procede a actualizarla con los nuevos datos.
            $sql = "UPDATE citas SET fecha_cita='$fecha', motivo_cita='$descripcion' WHERE idCita='$idCita'";
            if ($conn->query($sql) === TRUE) {
                echo "Cita modificada correctamente"; // Mensaje de éxito si la actualización es exitosa.
            } else {
                echo "Error modificando la cita: " . $conn->error; // Mensaje de error si ocurre un fallo al actualizar.
            }
        } else {
            // Si la cita ya pasó, no permite modificarla.
            echo "No se pueden modificar citas pasadas.";
        }
    } else {
        // Si no se encuentra la cita, muestra un mensaje de error.
        echo "Error: Cita no encontrada.";
    }

    // Cierra la conexión a la base de datos.
    $conn->close();
}

// Redirige al usuario de nuevo a la página de citaciones una vez completada la operación.
header("Location: ../views/citaciones.php");
