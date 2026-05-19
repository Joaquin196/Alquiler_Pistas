<?php
session_start(); // Abrimos la sesión actual
session_destroy(); // La eliminamos por completo borrando los datos del usuario

// Lo mandamos a la pantalla de logeo
header("Location: logeo.php");
exit();
?>