<?php
session_start(); // Inicia la sesión del usuario
include __DIR__ . '/../db.php'; // Incluye la conexión a la base de datos

// Verifica si la sesión está activa y el usuario es un administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    // Si no es admin, redirige a la página de inicio
    header("Location: ../index.php");
    exit();
}

// Verifica que se haya proporcionado un ID de cita válido
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM citas WHERE idCita = $id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Si se encuentra la cita, guarda los datos para el formulario
        $row = mysqli_fetch_array($result);
        $usuario = $row['idUser'];
        $fecha = $row['fecha_cita'];
        $motivo = $row['motivo_cita'];
    } else {
        // Si no se encuentra la cita, muestra un mensaje y redirige
        $_SESSION['message'] = 'Cita no encontrada';
        $_SESSION['message_type'] = 'danger';
        header('Location: citas-administracion.php');
        exit();
    }
} else {
    // Si no se proporciona un ID, muestra un mensaje y redirige
    $_SESSION['message'] = 'ID de cita no proporcionado';
    $_SESSION['message_type'] = 'danger';
    header('Location: ../views/citas-administracion.php');
    exit();
}

// Obtiene la lista de usuarios para el formulario de edición
$queryUsuarios = "SELECT * FROM users_data";
$resultUsuarios = mysqli_query($conn, $queryUsuarios);

// Actualiza la cita si se envió el formulario
if (isset($_POST['update'])) {
    $id = $_GET['id'];
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $fecha = mysqli_real_escape_string($conn, $_POST['fecha']);
    $motivo = mysqli_real_escape_string($conn, $_POST['motivo']);

    $query = "UPDATE citas SET idUser = '$usuario', fecha_cita = '$fecha', motivo_cita = '$motivo' WHERE idCita = $id";
    if (mysqli_query($conn, $query)) {
        // Si la actualización es exitosa, muestra un mensaje de éxito y redirige
        $_SESSION['message'] = 'Cita actualizada exitosamente';
        $_SESSION['message_type'] = 'success';
        header('Location: ../views/citas-administracion.php');
        exit();
    } else {
        // Si hay un error, muestra un mensaje y redirige
        $_SESSION['message'] = 'Error al actualizar la cita';
        $_SESSION['message_type'] = 'danger';
        header('Location: ../views/citas-administracion.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita</title>
    <link rel="stylesheet" href="../css/style_citas_editar.css"> <!-- Estilo específico para la edición de citas -->
</head>

<body>
    <div class="container p-4">
        <h1>Editar Cita</h1>
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="card card-body">
                    <!-- Formulario para editar la cita -->
                    <form action="../views/citas-editar.php?id=<?= $_GET['id']; ?>" method="POST">
                        <div class="form-group">
                            <select name="usuario" class="form-control">
                                <!-- Rellena las opciones del formulario con los usuarios -->
                                <?php while ($rowUsuarios = mysqli_fetch_assoc($resultUsuarios)) { ?>
                                    <option value="<?= $rowUsuarios['idUser'] ?>" <?= $rowUsuarios['idUser'] == $usuario ? 'selected' : '' ?>>
                                        <?= $rowUsuarios['nombre'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="date" name="fecha" class="form-control" value="<?= htmlspecialchars($fecha, ENT_QUOTES); ?>" placeholder="Fecha">
                        </div>
                        <div class="form-group">
                            <input type="text" name="motivo" class="form-control" value="<?= htmlspecialchars($motivo, ENT_QUOTES); ?>" placeholder="Motivo de la cita">
                        </div>
                        <button class="btn btn-success" name="update">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>