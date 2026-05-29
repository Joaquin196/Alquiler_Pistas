<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'conexion.php';
    
    $email_introducido = $_POST['email'];
    
    // Comprobamos si el correo existe en la base de datos
    $sql = "SELECT id FROM usuarios WHERE email = '$email_introducido'";
    $resultado = mysqli_query($conexion, $sql);
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        // Guardamos temporalmente el ID en la sesión para saber a quién le vamos a cambiar la contraseña
        $_SESSION['recuperar_usuario_id'] = $usuario['id'];
        
        mysqli_close($conexion);
        // Lo mandamos al segundo paso: el formulario para escribir la nueva clave
        header("Location: cambiar_contraseña.php");
        exit();
    } else {
        $error = "El correo electrónico no está registrado en NovaSport.";
    }
    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - NovaSport</title>
    <link rel="stylesheet" href="estilo/logeo.css">
</head>
<body>
    <div id="login">
        <form id="login-form" action="" method="POST">
            <img src="imagenes/logo-transparent-png.png" alt="Logo NovaSport" id="logo">
            <h2>Recuperar Contraseña</h2>
            <p class="link-secundario" style="margin-bottom: 10px;">Introduce tu correo para restablecer la contraseña.</p>

            <?php if (!empty($error)): ?>
                <p class="mensaje-error"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="inputs">
                <input type="email" name="email" class="campos" placeholder="Correo electrónico" required>
            </div>
            
            <button type="submit" id="iniciar-sesion">Comprobar Correo</button>
            <a href="logeo.php" class="link-secundario">Volver al inicio de sesión</a>
        </form>
    </div>
</body>
</html>