<?php
session_start();

// Control de seguridad: si intentan entrar aquí directos sin loguearse, al login
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Confirmada</title>
    <link rel="stylesheet" href="estilo/reservada.css">
</head>
<body>
    <div id="contenedor-mensaje">
        <h1>La pista ha sido reservada correctamente</h1>
        <a href="principal.php" id="boton-inicio">Volver al inicio</a>
    </div>
</body>
</html>