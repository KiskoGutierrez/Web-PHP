<?php
session_start();
include __DIR__ . '/../db.php'; // Incluye la conexión a la base de datos

// Verifica si el usuario es un administrador. Si no lo es, redirige a la página de inicio.
if ($_SESSION['rol'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Verifica si se ha pasado un ID válido para editar una noticia
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de noticia inválido.");
}

$idNoticia = intval($_GET['id']);

// Obtiene la noticia actual desde la base de datos
$query = "SELECT * FROM noticias WHERE idNoticia = $idNoticia";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Noticia no encontrada.");
}

$row = mysqli_fetch_assoc($result);

$titulo = $row['titulo'];
$texto = $row['texto'];
$imagen_actual = $row['imagen'];

// Si se ha enviado el formulario para actualizar la noticia
if (isset($_POST['update'])) {
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];
    $fecha = date('Y-m-d'); // Fecha actual o puedes pedir al usuario que la ingrese

    // Manejo de la carga de imágenes
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;
        $image = '';

        // Verifica si el archivo subido es una imagen
        if ($check = getimagesize($_FILES["imagen"]["tmp_name"]) === false) {
            $_SESSION['message'] = "El archivo no es una imagen.";
            $_SESSION['message_type'] = 'danger';
            $uploadOk = 0;
        }

        // Verifica si el archivo ya existe en el servidor
        if (file_exists($target_file)) {
            $_SESSION['message'] = "El archivo ya existe.";
            $_SESSION['message_type'] = 'danger';
            $uploadOk = 0;
        }

        // Verifica el tamaño del archivo (máximo 500 KB)
        if ($_FILES["imagen"]["size"] > 500000) {
            $_SESSION['message'] = "El archivo es demasiado grande.";
            $_SESSION['message_type'] = 'danger';
            $uploadOk = 0;
        }

        // Permite solo ciertos formatos de imagen
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            $_SESSION['message'] = "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
            $_SESSION['message_type'] = 'danger';
            $uploadOk = 0;
        }

        // Si hubo algún error, muestra un mensaje de error
        if ($uploadOk == 0) {
            $_SESSION['message'] = "El archivo no se ha subido.";
            $_SESSION['message_type'] = 'danger';
        } else {
            // Si todo está bien, mueve el archivo al directorio de destino
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["imagen"]["name"]);
            } else {
                $_SESSION['message'] = "Hubo un error al subir el archivo.";
                $_SESSION['message_type'] = 'danger';
            }
        }
    } else {
        // Si no se subió una nueva imagen, usa la imagen existente
        $image = $imagen_actual;
    }

    // Actualiza la noticia en la base de datos
    $titulo = mysqli_real_escape_string($conn, $titulo);
    $texto = mysqli_real_escape_string($conn, $texto);
    $image = mysqli_real_escape_string($conn, $image);

    $query = "UPDATE noticias SET titulo = '$titulo', texto = '$texto', imagen = '$image', fecha = '$fecha' WHERE idNoticia = $idNoticia";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        $_SESSION['message'] = 'Error al actualizar la noticia: ' . mysqli_error($conn);
        $_SESSION['message_type'] = 'danger';
    } else {
        $_SESSION['message_type'] = 'success';
        header('Location: ../views/noticias-administracion.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia</title>
    <link rel="stylesheet" href="../css/style_noticias_editar.css">
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['message_type']) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <!-- Formulario para editar la noticia -->
        <form action="../views/noticias-editar.php?id=<?php echo $_GET['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required>
            </div>
            <div class="form-group">
                <label for="texto">Contenido:</label>
                <textarea name="texto" required><?php echo htmlspecialchars($texto); ?></textarea>
            </div>
            <div class="form-group">
                <label for="imagen">Nueva Imagen:</label>
                <input type="file" name="imagen" accept="image/*">
            </div>
            <button type="submit" name="update">Actualizar Noticia</button>
        </form>
    </div>
</body>
</html>
