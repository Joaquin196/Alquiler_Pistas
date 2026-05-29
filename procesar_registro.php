<?php
session_start();

// 1. Incluimos el archivo que conecta con SQLyog
include 'conexion.php';

// 2. Recogemos los datos que el usuario escribió en los inputs del formulario
$email = $_POST['email'];
$nombre = $_POST['nombre'];
$pass = $_POST['contraseña'];
$confirm_pass = $_POST['confirmar_contraseña'];

// 3. Comprobamos si las dos contraseñas son exactamente iguales
if ($pass !== $confirm_pass) {
    die("Las contraseñas no coinciden. <a href='registro.php'>Volver a intentar</a>");
}

// 4. Encriptamos la contraseña por seguridad (para que no se lea en texto plano en la BD)
$pass_encriptada = password_hash($pass, PASSWORD_DEFAULT);

// 5. Preparamos la orden SQL para insertar los datos en la tabla usuarios
$sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$pass_encriptada')";

// 6. Ejecutamos la consulta y comprobamos si ha funcionado
if (mysqli_query($conexion, $sql)) {
    // Cerramos la conexión justo antes de irnos
    mysqli_close($conexion);
    
    // Guardamos el nombre del nuevo usuario en la sesión para que principal.php lo reconozca
    $_SESSION['usuario_nombre'] = $nombre;
    
    // Redirige directamente a la página principal con la nueva sesión activa
    header("Location: principal.php");
    exit();
} else {
    echo "Error al registrar el usuario: " . mysqli_error($conexion);
    mysqli_close($conexion);
}
?>