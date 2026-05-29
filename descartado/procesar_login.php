<?php
// 1. Iniciamos la sesión de PHP (esencial para recordar al usuario en las demás páginas)
session_start();

// 2. Conectamos a la base de datos
include 'conexion.php';

// 3. Recogemos los datos del formulario de logeo
$email = $_POST['usuario']; 
$pass = $_POST['contraseña'];

// 4. Buscamos al usuario por su email en SQLyog
$sql = "SELECT * FROM usuarios WHERE email = '$email'";
$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) > 0) {
    // El usuario existe, ahora extraemos sus datos de la base de datos
    $usuario = mysqli_fetch_assoc($resultado);
    
    // 5. Comprobamos si la contraseña coincide con la encriptada
    if (password_verify($pass, $usuario['password'])) {
        // Login correcto, guardamos el ID y el Nombre en la sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        
        // Lo redirigimos a la página principal de la web
        header("Location: principal.php");
        exit();
    } else {
        echo "Contraseña incorrecta. <a href='logeo.php'>Volver a intentar</a>";
    }
} else {
    echo "El correo electrónico no está registrado. <a href='logeo.php'>Volver a intentar</a>";
}

mysqli_close($conexion);
?>