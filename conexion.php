<?php
$conexion = mysqli_connect("localhost", "root", "", "novasport");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>