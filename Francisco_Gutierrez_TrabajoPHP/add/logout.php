<?php
// Inicia la sesión actual para poder manipularla.
session_start();

// Elimina todas las variables de sesión.
session_unset();

// Destruye la sesión por completo, cerrando así la sesión del usuario.
session_destroy();

// Redirige al usuario a la página de inicio (index.php) después de cerrar la sesión.
header("Location: ../index.php");
exit(); // Detiene la ejecución del script para asegurarse de que la redirección se ejecute.
