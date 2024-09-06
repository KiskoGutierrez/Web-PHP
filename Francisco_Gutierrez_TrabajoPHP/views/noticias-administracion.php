<?php
session_start();
include __DIR__ . '/../db.php'; // Incluye la conexión a la base de datos

// Verifica si el usuario es un administrador. Si no lo es, redirige a la página de inicio.
if ($_SESSION['rol'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Asegúrate de que 'idUser' esté definido en la sesión y sea un número válido.
if (!isset($_SESSION['idUser']) || !is_numeric($_SESSION['idUser'])) {
    die("ID de usuario no definido o inválido.");
}

$idUser = $_SESSION['idUser']; // Obtiene el ID del usuario desde la sesión

// Inicializa variables para mensajes de estado
$message = '';
$message_type = '';

// Procesa el formulario cuando se envía una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar'])) {
        $titulo = trim($_POST['titulo']);
        $contenido = trim($_POST['contenido']);
        $fecha = trim($_POST['fecha']);

        $target_dir = "../uploads/";
        $image = 'default.jpg'; // Imagen por defecto si no se sube ninguna imagen

        // Inicializa un array para almacenar los errores
        $errores = [];

        // Validación del título (debe ser un texto no vacío)
        if (empty($titulo)) {
            $errores[] = "El título es obligatorio.";
        } elseif (strlen($titulo) < 5) {
            $errores[] = "El título debe tener al menos 5 caracteres.";
        }

        // Validación del contenido (debe ser un texto no vacío)
        if (empty($contenido)) {
            $errores[] = "El contenido es obligatorio.";
        } elseif (strlen($contenido) < 10) {
            $errores[] = "El contenido debe tener al menos 10 caracteres.";
        }

        // Validación de la fecha (debe ser una fecha válida y no estar vacía)
        if (empty($fecha)) {
            $errores[] = "La fecha es obligatoria.";
        } elseif (!preg_match("/\d{4}-\d{2}-\d{2}/", $fecha)) {
            $errores[] = "El formato de la fecha es inválido.";
        }

        // Validación de archivo de imagen
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == UPLOAD_ERR_OK) {
            $imageFileType = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
            $new_image_name = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $new_image_name;

            // Verifica el tamaño del archivo
            if ($_FILES["imagen"]["size"] > 500000) { // Tamaño máximo de 500 KB
                $errores[] = "El archivo es demasiado grande.";
            }

            // Permite solo ciertos formatos de imagen
            if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                $errores[] = "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
            }

            // Verifica si el archivo ya existe
            if (file_exists($target_file)) {
                $errores[] = "El archivo ya existe.";
            }

            // Intenta mover el archivo al directorio de destino
            if (empty($errores) && !move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                $errores[] = "Hubo un error al subir el archivo.";
            } else {
                $image = $new_image_name;
            }
        } else {
            // Maneja el caso en que no se sube ningún archivo
            if ($_FILES["imagen"]["error"] != UPLOAD_ERR_NO_FILE) {
                $errores[] = "Hubo un error al subir el archivo.";
            }
        }

        // Si hubo errores, muestra los mensajes
        if (!empty($errores)) {
            $message = implode("<br>", $errores);
            $message_type = 'danger';
        } else {
            // Inserta la noticia en la base de datos
            $titulo = mysqli_real_escape_string($conn, $titulo);
            $contenido = mysqli_real_escape_string($conn, $contenido);
            $fecha = mysqli_real_escape_string($conn, $fecha);
            $query = "INSERT INTO noticias (titulo, texto, fecha, imagen, idUser) VALUES ('$titulo', '$contenido', '$fecha', '$image', $idUser)";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                $message = 'Error al agregar la noticia: ' . mysqli_error($conn);
                $message_type = 'danger';
            } else {
                $message = 'Noticia agregada exitosamente';
                $message_type = 'success';
            }
        }
    }
}

// Elimina una noticia si se ha proporcionado un ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convierte a entero para evitar inyecciones de SQL
    if ($id > 0) {
        $query = "DELETE FROM noticias WHERE idNoticia = $id";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            $message = 'Error al eliminar la noticia: ' . mysqli_error($conn);
            $message_type = 'danger';
        } elseif (mysqli_affected_rows($conn) === 0) {
            $message = 'No se encontró la noticia para eliminar.';
            $message_type = 'warning';
        } else {
            $message = 'Noticia eliminada exitosamente';
            $message_type = 'success';
        }
    } else {
        $message = 'ID de noticia inválido.';
        $message_type = 'danger';
    }
}

// Obtiene todas las noticias de la base de datos
$query = "SELECT * FROM noticias";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Noticias</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style_noticias_administracion.css">
</head>
<body>
    <header>
        <h1>Administrar Noticias</h1>
        <nav>
            <ul>
                <!-- Enlaces de navegación -->
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../views/noticias.php">Noticias</a></li>
                <li><a href="../views/usuarios-administracion.php">Usuarios-Administración</a></li>
                <li><a href="../views/citas-administracion.php">Citas-Administracion</a></li>
                <li><a href="../views/noticias-administracion.php" class="active">Noticias-Administracion</a></li>
                <li><a href="../views/perfil.php">Perfil</a></li>
                <li><a href="../add/logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container p-4">
            <div class="row">
                <div class="col-md-4">
                    <!-- Muestra mensajes de éxito o error -->
                    <?php if (!empty($message)) { ?>
                        <div class="alert alert-<?= htmlspecialchars($message_type) ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                    <div class="card card-body">
                        <!-- Formulario para agregar noticias -->
                        <form action="../views/noticias-administracion.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" name="titulo" class="form-control" placeholder="Título" required>
                            </div>
                            <div class="form-group">
                                <textarea name="contenido" class="form-control" placeholder="Contenido" required></textarea>
                            </div>
                            <div class="form-group">
                                <input type="date" name="fecha" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                            </div>
                            <input type="submit" class="btn btn-success btn-block" name="agregar" value="Agregar Noticia">
                        </form>
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- Tabla para mostrar noticias existentes -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Contenido</th>
                                <th>Fecha</th>
                                <th>Imagen</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['titulo']) ?></td>
                                    <td><?= htmlspecialchars($row['texto']) ?></td>
                                    <td><?= htmlspecialchars($row['fecha']) ?></td>
                                    <td>
                                        <?php if (!empty($row['imagen']) && $row['imagen'] != 'default.jpg'): ?>
                                            <img src="../uploads/<?= htmlspecialchars($row['imagen']) ?>" alt="Imagen" style="max-width: 100px;">
                                        <?php else: ?>
                                            <img src="../uploads/default.jpg" alt="Imagen por defecto" style="max-width: 100px;">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="../views/noticias-editar.php?id=<?= $row['idNoticia'] ?>" class="btn btn-secondary">Editar</a>
                                        <a href="../views/noticias-administracion.php?id=<?= $row['idNoticia'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta noticia?');">Eliminar</a>
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
