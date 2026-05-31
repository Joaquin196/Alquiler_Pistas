<?php
session_start();

// Esto es un control de seguridad, sirve para verificar que el usuario esté logueado y lo mandará al login
// Por ejemplo, si escribimos en la URL directamente http://localhost/Alquiler_Pistas/principal.php ya no accederá sin cuenta
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}

// Verificamos que recibimos el ID de la reserva por POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_reserva'])) {
    
    $conexion = mysqli_connect("localhost", "root", "", "novasport");

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $id_reserva = mysqli_real_escape_string($conexion, $_POST['id_reserva']);

    // Borramos la reserva usando el ID único que llega del formulario
    $sql_delete = "DELETE FROM reservas WHERE id_reserva = '$id_reserva'";
    mysqli_query($conexion, $sql_delete);

    mysqli_close($conexion);
} else {
    // Si entran de golpe sin pasar por el botón, lo mandamos a mis reservas
    header("Location: mis_reservas.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Cancelada</title>
    <link rel="stylesheet" href="estilo/cancelacion.css">
</head>
<body>
    <div id="contenedor-mensaje">
        <h1>La reserva ha sido cancelada correctamente</h1>
        <a href="principal.php" id="boton-inicio">Volver al inicio</a>
    </div>
</body>
</html>