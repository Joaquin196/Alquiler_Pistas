<?php
session_start();

/*Esto es un control de seguridad, sirve para verificar que el usuario esté logueado y lo mandará al login*/
/*Por ejemplo, si escribimos en la URL directamente http://localhost/Alquiler_Pistas/principal.php ya no accederá sin cuenta*/
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}

// Conexión a la base de datos para sacar los datos del usuario logueado
$conexion = mysqli_connect("localhost", "root", "", "novasport");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$usuario_nombre = $_SESSION['usuario_nombre'];
// Sacamos el correo del usuario para autorrellenar el formulario
$consulta_user = "SELECT email FROM usuarios WHERE nombre = '$usuario_nombre'";
$resultado_user = mysqli_query($conexion, $consulta_user);
$datos_usuario = mysqli_fetch_assoc($resultado_user);
$usuario_correo = $datos_usuario['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda - NovaSport</title>
    <link rel="stylesheet" href="estilo/ayuda.css">
    <link rel="stylesheet" href="estilo/comun.css">
</head>
<body>
    <header>
        <div>
            <a href="principal.php"><img src="imagenes/logo-transparent-png.png" alt="img" id="logo"></a>
        </div>
        <nav>
            <ul>
                <li><a href="principal.php">Inicio</a></li>
                <li><a href="reservar.php">Reservar pistas</a></li>
                <li><a href="mis_reservas.php">Mis reservas</a></li>
                <li><a href="competiciones.php">Competiciones</a></li>
                <li><a href="ayuda.php">Ayuda</a></li>
            </ul>
        </nav>
        
        <div id="iconos-derecha">
            
            <?php if (isset($_SESSION['usuario_nombre'])): /* Usamos isset para comprobar si la variable existe y no está vacía */?> 
                <span class="user-welcome">
                    Hola, <?php echo $_SESSION['usuario_nombre']; ?>
                </span>
                <a href="cerrar_sesion.php" class="btn-logout">
                    <img src="imagenes/logout.png" alt="Cerrar sesión" id="user">
                </a>
            
            <?php else: /* Si el usuario acaba de entrar sin logearse, la variable no existirá e irá al else */?>
                <a href="registro.php">
                    <img src="imagenes/acceso.png" alt="Usuario" id="user"> 
                </a>
            <?php endif; ?>

            
            
            
        </div>
    </header>

    <section id="contenedor-ayuda">
        <h1>Centro de Ayuda</h1>
        
        <div id="frecuentes">
            <article class="pregunta">
                <h3>¿Cómo puedo cancelar una reserva?</h3>
                <p>Ve a la sección "Mis reservas" y pulsa el botón de cancelar. Debe hacerse con 24h de antelación.</p>
            </article>
            <article class="pregunta">
                <h3>¿Es necesario estar registrado?</h3>
                <p>Sí, para reservar pistas es obligatorio tener una cuenta en NovaSport.</p>
            </article>
        </div>

        <section id="contacto-ayuda">
            <h2>¿Aún necesitas ayuda?</h2>
            
            <form id="form-ayuda" action="enviar_ayuda.php" method="POST">
                <?php // En los input metemos el php para autorrellenarlos con los datos del usuario logueado, así no tiene que escribirlos ?>
                
                <input type="text" name="nombre" placeholder="Tu nombre" class="campo-ayuda" value="<?php echo $usuario_nombre; ?>" required>
                <input type="email" name="correo" placeholder="Tu correo electrónico" class="campo-ayuda" value="<?php echo $usuario_correo; ?>" required>
                <textarea name="mensaje" placeholder="Escribe tu duda..." class="campo-ayuda" rows="5" required></textarea>
                
                <button type="submit" id="btn-enviar-ayuda">Enviar mensaje</button>
            </form>
        </section>
    </section>

    <footer>
        <div id="caja-footer">
            <div id="footer-nav">
                <a href="reservar.php">Reservar pistas</a>
                <a href="mis_reservas.php">Mis reservas</a>
                <a href="competiciones.php">Competiciones</a>
                <a href="ayuda.php">Ayuda</a>
            </div>
            <p id="copyright">&copy; 2026 NovaSport - Todos los derechos reservados</p>
        </div>
    </footer>
</body>
</html>
<?php mysqli_close($conexion); ?>