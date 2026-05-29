<?php
session_start();

// Si no viene del paso anterior, no le dejamos estar aquí
if (!isset($_SESSION['recuperar_usuario_id'])) {
    header("Location: logeo.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'conexion.php';
    
    $nueva_pass = $_POST['nueva_pass'];
    $confirm_pass = $_POST['confirm_pass'];
    $usuario_id = $_SESSION['recuperar_usuario_id'];
    
    if ($nueva_pass !== $confirm_pass) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Encriptamos la nueva contraseña igual que en el registro
        $pass_encriptada = password_hash($nueva_pass, PASSWORD_DEFAULT);
        
        // Actualizamos la contraseña en tu tabla 'usuarios' usando el ID guardado
        $sql = "UPDATE usuarios SET password = '$pass_encriptada' WHERE id = '$usuario_id'";
        
        if (mysqli_query($conexion, $sql)) {
            // Limpiamos la variable temporal de la sesión
            unset($_SESSION['recuperar_usuario_id']);
            mysqli_close($conexion);
            
            // Redirección directa al login
            header("Location: logeo.php");
            exit();
        } else {
            $error = "Error al actualizar la contraseña: " . mysqli_error($conexion);
        }
    }
    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - NovaSport</title>
    <link rel="stylesheet" href="estilo/logeo.css">
</head>
<body>
    <div id="login">
        <form id="login-form" action="" method="POST">
            <img src="imagenes/logo-transparent-png.png" alt="Logo NovaSport" id="logo">
            <h2>Establecer Contraseña</h2>

            <?php if (!empty($error)): ?>
                <p class="mensaje-error"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="inputs">
                <input type="password" name="nueva_pass" class="campos" placeholder="Nueva contraseña" required>
            </div>
            <div class="inputs">
                <input type="password" name="confirm_pass" class="campos" placeholder="Confirmar contraseña" required>
            </div>
            
            <button type="submit" id="iniciar-sesion">Guardar Contraseña</button>
        </form>
    </div>
</body>
</html>