<?php
session_start(); // Iniciar sesión

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a index.php
header("Location: index.php");
exit();
?>
