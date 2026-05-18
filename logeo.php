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
        <form id="login-form" action="procesar_login.php" method="POST">
            <img src="imagenes/logo-transparent-png.png" alt="Escudo del polideportivo" id="logo">
            <h2>Inicia sesión</h2>
            
            <div class="inputs">
                <input type="text" name="usuario" class="campos" placeholder="Correo electrónico" required>
            </div>
            
            <div class="inputs">
                <input type="password" name="contraseña" class="campos" placeholder="Contraseña" required>
            </div>
            
            <button type="submit" id="iniciar-sesion">Iniciar Sesión</button>

            <p id="texto-registro">¿No tiene cuenta?</p>
            <a href="registro.html" class="link-secundario">Regístrese aquí</a>
            <a href="rec_contraseña.html" class="link-secundario">¿Ha olvidado su contraseña?</a>
        </form>
    </div>
</body>
</html>
