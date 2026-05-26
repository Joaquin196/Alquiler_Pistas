<?php
session_start();

if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mensaje'])) {
    $conexion = mysqli_connect("localhost", "root", "", "novasport");

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $mensaje = mysqli_real_escape_string($conexion, $_POST['mensaje']);

    // Insertamos la consulta de ayuda en la base de datos
    $sql_insert = "INSERT INTO mensajes_ayuda (nombre, correo, mensaje) VALUES ('$nombre', '$correo', '$mensaje')";
    mysqli_query($conexion, $sql_insert);

    mysqli_close($conexion);
} else {
    header("Location: ayuda.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje Recibido</title>
    <link rel="stylesheet" href="estilo/reservada.css">
</head>
<body>
    <div id="contenedor-mensaje">
        <h1>Hemos recibido tu duda. Nos pondremos en contacto contigo pronto.</h1>
        <a href="ayuda.php" id="boton-inicio">Volver a Ayuda</a>
    </div>
</body>
</html>