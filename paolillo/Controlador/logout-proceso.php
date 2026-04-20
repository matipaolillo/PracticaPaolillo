<?php
session_start();

// Limpiar todas las variables de sesión
$_SESSION = array();

// Borrar la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

session_destroy();
header("Location: ../Vista/sesion.php");
exit();
?>