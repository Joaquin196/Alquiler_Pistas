<?php
session_start();

// Esto es un control de seguridad, sirve para verificar que el usuario esté logueado y lo mandará al login
// Por ejemplo, si escribimos en la URL directamente http://localhost/Alquiler_Pistas/principal.php ya no accederá sin cuenta
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_torneo'])) {
    
    $conexion = mysqli_connect("localhost", "root", "", "novasport");

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $usuario_nombre = $_SESSION['usuario_nombre'];
    $id_torneo = mysqli_real_escape_string($conexion, $_POST['id_torneo']);

    // 1. Conseguimos el ID numérico del usuario actual
    $consulta_user = "SELECT id FROM usuarios WHERE nombre = '$usuario_nombre'";
    $resultado_user = mysqli_query($conexion, $consulta_user);
    $datos_usuario = mysqli_fetch_assoc($resultado_user);

    if ($datos_usuario) {
        $id_usuario = $datos_usuario['id'];

        // 2. Control de seguridad extra: verificar si ya está apuntado para no duplicar
        $consulta_duplicado = "SELECT id_inscripcion FROM inscripciones_torneos WHERE id_usuario = '$id_usuario' AND id_torneo = '$id_torneo'";
        $resultado_duplicado = mysqli_query($conexion, $consulta_duplicado);

        if (mysqli_num_rows($resultado_duplicado) == 0) {
            // 3. No está inscrito, procedemos a meterlo
            $sql_insert = "INSERT INTO inscripciones_torneos (id_usuario, id_torneo) VALUES ('$id_usuario', '$id_torneo')";
            mysqli_query($conexion, $sql_insert);
        }
    }

    mysqli_close($conexion);
}

// Redirigimos de vuelta a competiciones para que vea que todo sigue ordenado
header("Location: competiciones.php");
exit();
?>