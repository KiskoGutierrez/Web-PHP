<?php
session_start(); // Inicia la sesión del usuario
include __DIR__ . '/../db.php'; // Incluye la conexión a la base de datos

// Verifica si el usuario es un administrador. Si no lo es, redirige a la página principal.
if ($_SESSION['rol'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Agrega una nueva cita si se ha enviado el formulario
if (isset($_POST['agregar'])) {
    // Recibir y sanitizar entradas
    $idUsuario = trim($_POST['usuario']);
    $fecha = trim($_POST['fecha']);
    $motivo = trim($_POST['motivo']);

    // Inicializa un array para almacenar los errores
    $errores = [];

    // Validación del ID de usuario (debe estar seleccionado)
    if (empty($idUsuario)) {
        $errores[] = "Debe seleccionar un usuario.";
    }

    // Validación de la fecha (debe ser una fecha válida y no estar vacía)
    if (empty($fecha)) {
        $errores[] = "La fecha es obligatoria.";
    } elseif (!preg_match("/\d{4}-\d{2}-\d{2}/", $fecha)) {
        $errores[] = "Formato de fecha inválido.";
    }

    // Validación del motivo de la cita (debe ser un texto no vacío)
    if (empty($motivo)) {
        $errores[] = "El motivo de la cita es obligatorio.";
    } elseif (strlen($motivo) < 5) {
        $errores[] = "El motivo de la cita debe tener al menos 5 caracteres.";
    }

    // Si no hay errores, inserta la cita en la base de datos
    if (empty($errores)) {
        $idUsuario = mysqli_real_escape_string($conn, $idUsuario);
        $fecha = mysqli_real_escape_string($conn, $fecha);
        $motivo = mysqli_real_escape_string($conn, $motivo);
        $query = "INSERT INTO citas(idUser, fecha_cita, motivo_cita) VALUES ('$idUsuario', '$fecha', '$motivo')";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Error en la consulta: " . mysqli_error($conn)); // Muestra un error si la consulta falla
        }

        // Mensaje de éxito y redirección
        $_SESSION['message'] = 'Cita agregada exitosamente';
        $_SESSION['message_type'] = 'success';
        header('Location: ../views/citas-administracion.php');
        exit();
    } else {
        // Si hay errores, guárdalos en la sesión para mostrarlos
        $_SESSION['errores'] = $errores;
    }
}

// Elimina una cita si se ha pasado un ID por GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convierte a entero para evitar inyecciones de SQL
    $query = "DELETE FROM citas WHERE idCita=$id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error en la consulta: " . mysqli_error($conn)); // Muestra un error si la consulta falla
    }

    // Mensaje de éxito y redirección
    $_SESSION['message'] = 'Cita eliminada exitosamente';
    $_SESSION['message_type'] = 'danger';
    header('Location: ../views/citas-administracion.php');
    exit();
}

// Obtiene todas las citas y datos de usuarios para mostrarlas
$query = "
    SELECT citas.idCita, users_data.nombre, citas.fecha_cita, citas.motivo_cita
    FROM citas
    JOIN users_data ON citas.idUser=users_data.idUser";
$result = mysqli_query($conn, $query);

// Obtiene todos los usuarios para mostrarlos en el formulario de agregar cita
$queryUsuarios = "SELECT * FROM users_data";
$resultUsuarios = mysqli_query($conn, $queryUsuarios);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Citas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style_citas_administracion.css">
</head>

<body>
    <header>
        <h1>Administrar Citas</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../views/noticias.php">Noticias</a></li>
                <li><a href="../views/usuarios-administracion.php">Usuarios-Administración</a></li>
                <li><a href="../views/citas-administracion.php" class="active">Citas-Administración</a></li>
                <li><a href="../views/noticias-administracion.php">Noticias-Administración</a></li>
                <li><a href="../views/perfil.php">Perfil</a></li>
                <li><a href="../add/logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container p-4">
            <div class="row">
                <div class="col-md-4">
                    <!-- Muestra los errores si existen -->
                    <?php if (isset($_SESSION['errores']) && count($_SESSION['errores']) > 0) { ?>
                        <div class="alert alert-danger">
                            <?php foreach ($_SESSION['errores'] as $error) { ?>
                                <p><?= htmlspecialchars($error) ?></p>
                            <?php } ?>
                        </div>
                        <?php unset($_SESSION['errores']); ?>
                    <?php } ?>

                    <!-- Muestra un mensaje si existe, luego lo limpia -->
                    <?php if (isset($_SESSION['message'])) { ?>
                        <div class="alert alert-<?= htmlspecialchars($_SESSION['message_type']) ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['message']) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                        ?>
                    <?php } ?>

                    <!-- Formulario para agregar una nueva cita -->
                    <div class="card card-body">
                        <form action="../views/citas-administracion.php" method="POST">
                            <div class="form-group">
                                <select name="usuario" class="form-control">
                                    <!-- Opciones de usuarios para seleccionar -->
                                    <?php while ($rowUsuarios = mysqli_fetch_assoc($resultUsuarios)) { ?>
                                        <option value="<?= htmlspecialchars($rowUsuarios['idUser']) ?>">
                                            <?= htmlspecialchars($rowUsuarios['nombre']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="date" name="fecha" class="form-control" placeholder="Fecha" value="<?= isset($fecha) ? htmlspecialchars($fecha) : '' ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" name="motivo" class="form-control" placeholder="Motivo de la cita" value="<?= isset($motivo) ? htmlspecialchars($motivo) : '' ?>">
                            </div>
                            <input type="submit" class="btn btn-success btn-block" name="agregar" value="Agregar Cita">
                        </form>
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- Tabla para mostrar todas las citas -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Motivo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                                    <td><?= htmlspecialchars($row['fecha_cita']) ?></td>
                                    <td><?= htmlspecialchars($row['motivo_cita']) ?></td>
                                    <td>
                                        <a href="../views/citas-editar.php?id=<?= $row['idCita'] ?>" class="btn btn-secondary">
                                            Editar cita
                                            <i class="fas fa-marker"></i>
                                        </a>
                                        <a href="../views/citas-administracion.php?id=<?= $row['idCita'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
                                            Borrar cita
                                            <i class="far fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
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
