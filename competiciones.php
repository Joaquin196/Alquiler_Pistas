<?php
session_start();

/*Esto es un control de seguridad, sirve para verificar que el usuario esté logueado y lo mandará al login*/
/*Por ejemplo, si escribimos en la URL directamente http://localhost/Alquiler_Pistas/principal.php ya no accederá sin cuenta*/
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "novasport");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Conseguimos el ID numérico del usuario actual usando su nombre de sesión
$usuario_nombre = $_SESSION['usuario_nombre'];
$consulta_user = "SELECT id FROM usuarios WHERE nombre = '$usuario_nombre'";
$resultado_user = mysqli_query($conexion, $consulta_user);
$datos_usuario = mysqli_fetch_assoc($resultado_user);
$id_usuario = $datos_usuario['id'] ?? 0;

// Consulta para sacar todos los torneos disponibles
$consulta_torneos = "SELECT id_torneo, nombre, fecha_texto, precio FROM torneos ORDER BY id_torneo ASC";
$resultado_torneos = mysqli_query($conexion, $consulta_torneos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competiciones - NovaSport</title>
    <link rel="stylesheet" href="estilo/competiciones.css">
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

    <section id="contenedor-competiciones">
        <h1>Competiciones</h1>

        <section id="seccion-torneos">
            <h2>Próximos Torneos</h2>
            <div id="lista-torneos">
                <?php /* Por cada torneo que haya, fabrica un bloque article de HTML e inyecta su fecha, su nombre y su precio en el texto */ ?>
                <?php if (mysqli_num_rows($resultado_torneos) > 0): ?>
                    <?php while ($torneo = mysqli_fetch_assoc($resultado_torneos)): ?>
                        <?php 
                        $id_torneo = $torneo['id_torneo'];
                        // Comprobamos si este usuario en concreto ya está inscrito en este torneo
                        $consulta_check = "SELECT id_inscripcion FROM inscripciones_torneos WHERE id_usuario = '$id_usuario' AND id_torneo = '$id_torneo'";
                        $resultado_check = mysqli_query($conexion, $consulta_check);
                        $ya_apuntado = (mysqli_num_rows($resultado_check) > 0);
                        ?>
                        <article class="item-torneo">
                            <p><strong><?php echo $torneo['fecha_texto']; ?></strong> - <?php echo $torneo['nombre']; ?> - <?php echo $torneo['precio']; ?></p>
                            
                            <?php if ($ya_apuntado): ?>
                                <span class="btn-apuntado">✓ ¡Apuntado!</span>
                            <?php else: ?>
                                <form action="apuntarse_torneo.php" method="POST" style="margin: 0;">
                                    <input type="hidden" name="id_torneo" value="<?php echo $id_torneo; ?>">
                                    <button type="submit" class="btn-apuntarse">Apuntarse</button>
                                </form>
                            <?php endif; ?>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No hay torneos disponibles en este momento.</p>
                <?php endif; ?>
            </div>
        </section>

        <div class="caja-ayuda-torneos">
            <h3>¿Tienes alguna duda sobre las reglas o las inscripciones?</h3>
            <p>Si necesitas ayuda con los horarios, las normativas de los torneos o quieres proponernos una nueva competición, estamos aquí para ayudarte.</p>
            <a href="ayuda.php" class="btn-ir-ayuda">Ir al Centro de Ayuda</a>
        </div>
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