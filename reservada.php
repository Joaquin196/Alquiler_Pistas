<?php
session_start();

/*Esto es un control de seguridad, sirve para verificar que el usuario esté logueado y lo mandará al login*/
/*Por ejemplo, si escribimos en la URL directamente http://localhost/Alquiler_Pistas/principal.php ya no accederá sin cuenta*/
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}

// Si no hay datos de reserva en la sesión, los mandamos a reservar de nuevo
if (!isset($_SESSION['reserva_fecha'])) {
    header("Location: reservar.php");
    exit();
}

/*En este caso, nos conectamos a la base de datos, ya que realizaremos consultas (SELECT, INSERT, UPDATE, DELETE)*/
$conexion = mysqli_connect("localhost", "root", "", "novasport");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Averiguamos el id del usuario usando el nombre que tenemos en la sesión
$usuario_nombre = $_SESSION['usuario_nombre'];
$consulta_user = "SELECT id FROM usuarios WHERE nombre = '$usuario_nombre'";
$resultado_user = mysqli_query($conexion, $consulta_user);
$datos_usuario = mysqli_fetch_assoc($resultado_user);

if ($datos_usuario) {
    $id_usuario = $datos_usuario['id'];

    // Recuperamos el resto de datos de la reserva guardados en la sesión
    $deporte = $_SESSION['reserva_deporte'];
    $fecha = $_SESSION['reserva_fecha'];
    $hora = $_SESSION['reserva_hora'];
    $numero_pista = $_SESSION['reserva_pista'];

    // Hacemos el INSERT real en la tabla de reservas
    $sql_insert = "INSERT INTO reservas (id_usuario, deporte, fecha, hora, numero_pista) 
                   VALUES ('$id_usuario', '$deporte', '$fecha', '$hora', '$numero_pista')";
    
    mysqli_query($conexion, $sql_insert);
    
    // Limpiamos las variables temporales de la reserva para que no se dupliquen al recargar
    unset($_SESSION['reserva_deporte']);
    unset($_SESSION['reserva_fecha']);
    unset($_SESSION['reserva_hora']);
    unset($_SESSION['reserva_pista']);
}

mysqli_close($conexion);
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