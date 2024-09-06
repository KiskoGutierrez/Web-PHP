<?php
session_start();
include __DIR__ . '/../db.php'; // Incluye la conexión a la base de datos

// Verifica si el usuario tiene el rol de administrador
if ($_SESSION['rol'] !== 'admin') {
    // Si no es admin, redirige al inicio
    header("Location: ../index.php");
    exit();
}

// Obtiene la lista de usuarios desde la base de datos
$usuarios = $conn->query("SELECT users_data.idUser, nombre, apellidos, email, rol 
    FROM users_data 
    INNER JOIN users_login ON users_data.idUser = users_login.idUser");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear'])) {
        // Procesa la creación de un nuevo usuario
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $apellidos = $conn->real_escape_string($_POST['apellidos']);
        $email = $conn->real_escape_string($_POST['email']);
        $telefono = $conn->real_escape_string($_POST['telefono']);
        $fecha_nacimiento = $conn->real_escape_string($_POST['fecha_nacimiento']);
        $direccion = $conn->real_escape_string($_POST['direccion']);
        $sexo = $conn->real_escape_string($_POST['sexo']);
        $username = $conn->real_escape_string($_POST['username']);
        $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT); // Encripta la contraseña
        $rol = $conn->real_escape_string($_POST['rol']);

        // Inserta los datos del nuevo usuario en la base de datos
        $conn->query("INSERT INTO users_data (nombre, apellidos, email, telefono, fecha_nacimiento, direccion, sexo) 
                      VALUES ('$nombre', '$apellidos', '$email', '$telefono', '$fecha_nacimiento', '$direccion', '$sexo')");
        $idUser = $conn->insert_id; // Obtiene el ID del nuevo usuario
        $conn->query("INSERT INTO users_login (idUser, usuario, password, rol) 
                      VALUES ('$idUser', '$username', '$password', '$rol')");

        $_SESSION['status'] = 'created'; // Establece el estado de la sesión para notificar al usuario
        header("Location: ../views/usuarios-administracion.php");
        exit();
    } elseif (isset($_POST['idUser'])) {
        // Procesa la actualización del rol de un usuario existente
        $idUser = $conn->real_escape_string($_POST['idUser']);
        $nuevoRol = $conn->real_escape_string($_POST['rol']);
        $conn->query("UPDATE users_login SET rol = '$nuevoRol' WHERE idUser = '$idUser'");

        $_SESSION['status'] = 'updated'; // Establece el estado de la sesión para notificar al usuario
        header("Location: ../views/usuarios-administracion.php");
        exit();
    }
} elseif (isset($_GET['delete'])) {
    // Procesa la eliminación de un usuario
    $idUser = $conn->real_escape_string($_GET['delete']);
    $conn->query("DELETE FROM users_login WHERE idUser = '$idUser'");
    $conn->query("DELETE FROM users_data WHERE idUser = '$idUser'");

    $_SESSION['status'] = 'deleted'; // Establece el estado de la sesión para notificar al usuario
    header("Location: ../views/usuarios-administracion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <!-- Vincula las hojas de estilo para el diseño de la página -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style_usuarios_administracion.css">
</head>

<body>
    <header>
        <h1>Administrar Usuarios</h1>
        <nav>
            <ul>
                <!-- Enlaces de navegación para la administración -->
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../views/noticias.php">Noticias</a></li>
                <li><a href="../views/usuarios-administracion.php" class="active">Usuarios-Administración</a></li>
                <li><a href="../views/citas-administracion.php">Citas-Administración</a></li>
                <li><a href="../views/noticias-administracion.php">Noticias-Administración</a></li>
                <li><a href="../views/perfil.php">Perfil</a></li>
                <li><a href="../add/logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Formulario para crear nuevos usuarios -->
        <div class="container_p-4">
            <h2>Crear Nuevo Usuario</h2>
            <form action="../views/usuarios-administracion.php" method="POST">
                <div class="form-group">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="form-group">
                    <input type="text" name="apellidos" class="form-control" placeholder="Apellidos" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" name="telefono" class="form-control" placeholder="Teléfono" required>
                </div>
                <div class="form-group">
                    <input type="date" name="fecha_nacimiento" class="form-control" placeholder="Fecha de Nacimiento" required>
                </div>
                <div class="form-group">
                    <input type="text" name="direccion" class="form-control" placeholder="Dirección" required>
                </div>
                <div class="form-group">
                    <select name="sexo" class="form-control" required>
                        <option value="" disabled selected>Sexo</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Nombre de Usuario" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
                <div class="form-group">
                    <select name="rol" class="form-control" required>
                        <option value="user">Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <input type="submit" name="crear" class="btn btn-primary" value="Crear Usuario">
            </form>
        </div>

        <!-- Tabla que muestra la lista de usuarios -->
        <div class="container_p-5">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acción</th>
                </tr>
                <?php while ($usuario = $usuarios->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $usuario['idUser']; ?></td>
                        <td><?php echo $usuario['nombre']; ?></td>
                        <td><?php echo $usuario['apellidos']; ?></td>
                        <td><?php echo $usuario['email']; ?></td>
                        <td><?php echo $usuario['rol']; ?></td>
                        <td>
                            <!-- Formulario para actualizar el rol del usuario -->
                            <form action="../views/usuarios-administracion.php" method="post" style="display:inline;">
                                <input type="hidden" name="idUser" value="<?php echo $usuario['idUser']; ?>">
                                <select name="rol" required>
                                    <option value="user" <?php if ($usuario['rol'] == 'user') echo 'selected'; ?>>Usuario</option>
                                    <option value="admin" <?php if ($usuario['rol'] == 'admin') echo 'selected'; ?>>Administrador</option>
                                </select>
                                <input type="submit" value="Actualizar">
                            </form>
                            <!-- Enlace para eliminar el usuario con confirmación -->
                            <a href="../views/usuarios-administracion.php?delete=<?php echo $usuario['idUser']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>

    <!-- Pie de página con enlaces a redes sociales -->
    <footer>
        <p>Síguenos en nuestras redes sociales: </p>
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

    <!-- Script para mostrar alertas basadas en el estado de la sesión -->

    <script>
        <?php if (isset($_SESSION['status'])) : ?>
            let status = "<?php echo $_SESSION['status']; ?>";
            if (status === "created") {
                alert("Usuario creado exitosamente.");
            } else if (status === "updated") {
                alert("Rol del usuario actualizado exitosamente.");
            } else if (status === "deleted") {
                alert("Usuario eliminado exitosamente.");
            }
            <?php unset($_SESSION['status']); // Limpiar el estado después de mostrarlo 
            ?>
        <?php endif; ?>
    </script>
</body>

</html>