<?php
include __DIR__ . '/../db.php'; // Incluye la conexión a la base de datos
session_start();

// Verificar que todos los campos del formulario han sido enviados
if (isset($_POST['name'], $_POST['apellidos'], $_POST['email'], $_POST['phone'], $_POST['fecha_nacimiento'], $_POST['address'], $_POST['sexo'], $_POST['username'], $_POST['password'])) {

    // Escapar caracteres especiales para evitar inyecciones SQL
    $name = $conn->real_escape_string($_POST['name']);
    $apellidos = $conn->real_escape_string($_POST['apellidos']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $fecha_nacimiento = $conn->real_escape_string($_POST['fecha_nacimiento']);
    $address = $conn->real_escape_string($_POST['address']);
    $sexo = $conn->real_escape_string($_POST['sexo']);
    $username = $conn->real_escape_string($_POST['username']);

    // Encriptar la contraseña antes de almacenarla
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT);

    // Asignar el rol 'user' por defecto a los nuevos registros
    $rol = 'user';

    // Iniciar una transacción para asegurar la integridad de los datos
    $conn->begin_transaction();

    try {
        // Insertar los datos del usuario en la tabla users_data
        $sql1 = "INSERT INTO users_data (nombre, apellidos, email, telefono, fecha_nacimiento, direccion, sexo) 
                VALUES ('$name', '$apellidos', '$email', '$phone', '$fecha_nacimiento', '$address', '$sexo')";
        $conn->query($sql1);

        // Obtener el ID del usuario recién insertado
        $idUser = $conn->insert_id;

        // Insertar los datos de acceso del usuario en la tabla users_login
        $sql2 = "INSERT INTO users_login (idUser, usuario, password, rol) 
                VALUES ('$idUser', '$username', '$password', '$rol')";
        $conn->query($sql2);

        // Confirmar la transacción si todo ha ido bien
        $conn->commit();

        // Redirigir al usuario al login con un mensaje de éxito
        header("Location: ../views/login.php?message=Registro exitoso, por favor inicia sesión.");
    } catch (Exception $e) {
        // Revertir los cambios si ocurre un error
        $conn->rollback();

        // Redirigir de vuelta al formulario de registro con un mensaje de error
        header("Location: ../views/registro.php?error=No se pudo completar el registro. Inténtelo de nuevo.");
    }
} else {
    // Redirigir al formulario de registro si faltan campos
    header("Location: ../views/registro.php?error=Por favor complete todos los campos.");
}

// Cerrar la conexión a la base de datos
$conn->close();
