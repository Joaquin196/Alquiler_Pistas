<?php
// 1. Iniciamos la sesión arriba del todo para poder guardar el usuario logueado
session_start();

// Si el usuario ya tiene una sesión iniciada, lo mandamos directo al inicio
if (isset($_SESSION['usuario_nombre'])) {
    header("Location: principal.php");
    exit();
}

// Creamos la variable de error vacía
$error_login = "";

// 2. Comprobamos si el formulario ha sido enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Incluimos tu archivo de conexión a la base de datos
    include 'conexion.php';

    // Recogemos lo que el usuario escribió en tus inputs
    $email_introducido = $_POST['usuario'];
    $pass_introducida = $_POST['contraseña'];

    // Buscamos al usuario por su email en la tabla 'usuarios'
    $sql = "SELECT * FROM usuarios WHERE email = '$email_introducido'";
    $resultado = mysqli_query($conexion, $sql);

    // Si el resultado no es falso y hay al menos un usuario con ese email, seguimos
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);

        // Verificamos si la contraseña coincide con el hash encriptado de la base de datos
        if (password_verify($pass_introducida, $usuario['password'])) {
            
            // ¡ÉXITO! Guardamos el nombre real en la sesión
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            
            mysqli_close($conexion);
            // Redirigimos directamente a la pantalla principal
            header("Location: principal.php");
            exit();
        } else {
            // Contraseña incorrecta
            $error_login = "Contraseña incorrecta. Vuelve a intentarlo.";
        }
    } else {
        // El correo electrónico no existe en tu tabla
        $error_login = "El correo electrónico no está registrado.";
    }

    // Cerramos la conexión si ha habido algún fallo
    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NovaSport</title>
    <link rel="stylesheet" href="estilo/logeo.css">
</head>
<body>
    <div id="login">
        <form id="login-form" action="" method="POST">
            <img src="imagenes/logo-transparent-png.png" alt="Escudo del polideportivo" id="logo">
            <h2>Inicia sesión</h2>

            <?php // Mostramos el mensaje de error si existe ?>
            <?php if (!empty($error_login)): ?>
                <p class="mensaje-error"><?php echo $error_login; ?></p>
            <?php endif; ?>
            
            <div class="inputs">
                <input type="text" name="usuario" class="campos" placeholder="Correo electrónico" required>
            </div>
            
            <div class="inputs">
                <input type="password" name="contraseña" class="campos" placeholder="Contraseña" required>
            </div>
            
            <button type="submit" id="iniciar-sesion">Iniciar Sesión</button>

            <p id="texto-registro">¿No tiene cuenta?</p>
            <a href="registro.php" class="link-secundario">Regístrese aquí</a>
            <a href="rec_contraseña.php" class="link-secundario">¿Ha olvidado su contraseña?</a>
        </form>
    </div>
</body>
</html>
