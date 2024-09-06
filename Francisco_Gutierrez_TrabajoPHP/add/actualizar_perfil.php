<?php
session_start(); // Inicia la sesión para poder acceder a las variables de sesión

// Verifica si el usuario está logueado, si no lo está, redirige a la página de login
if (!isset($_SESSION['username'])) {
    header("Location: ../views/login.php");
    exit();
}

// Verifica si el formulario fue enviado con el método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Configuración de la conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "noticias_db";

    // Crea una nueva conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica si hubo un error al conectarse
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Obtiene el nombre de usuario desde la sesión
    $username = $_SESSION['username'];

    // Consulta para obtener el idUser basado en el nombre de usuario
    $sql = "SELECT idUser FROM users_login WHERE usuario='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idUser = $row['idUser'];

        // Escapa los datos recibidos del formulario para evitar inyecciones SQL
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $apellidos = $conn->real_escape_string($_POST['apellidos']);
        $email = $conn->real_escape_string($_POST['email']);
        $telefono = $conn->real_escape_string($_POST['telefono']);
        $fecha_nacimiento = $conn->real_escape_string($_POST['fecha_nacimiento']);
        $direccion = $conn->real_escape_string($_POST['direccion']);
        $sexo = $conn->real_escape_string($_POST['sexo']);

        // Actualiza los datos en la tabla users_data
        $sql_update_data = "UPDATE users_data SET nombre='$nombre', apellidos='$apellidos', email='$email',
                            telefono='$telefono', fecha_nacimiento='$fecha_nacimiento', direccion='$direccion', 
                            sexo='$sexo' WHERE idUser='$idUser'";

        if ($conn->query($sql_update_data) === TRUE) {
            // Si se envió una nueva contraseña, actualiza también la tabla users_login
            if (!empty($_POST['password'])) {
                $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);
                $sql_update_login = "UPDATE users_login SET password='$password' WHERE idUser='$idUser'";
                $conn->query($sql_update_login);
            }

            // Redirigir con mensaje de éxito
            echo "<script>
                    alert('Perfil actualizado correctamente');
                    window.location.href='../views/perfil.php';
                  </script>";
        } else {
            echo "Error actualizando el perfil: " . $conn->error;
        }
    } else {
        echo "Error: Usuario no encontrado.";
    }

    // Cierra la conexión a la base de datos
    $conn->close();
}
?>
