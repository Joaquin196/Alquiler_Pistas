<?php
session_start();

// Esto es un control de seguridad, sirve para verificar que el usuario esté logueado y lo mandará al login
// Por ejemplo, si escribimos en la URL directamente http://localhost/Alquiler_Pistas/principal.php ya no accederá sin cuenta
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "novasport");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$usuario_nombre = $_SESSION['usuario_nombre'];

// Consulta para las próximas citas (fechas desde hoy en adelante) cruzando con la tabla usuarios
$consulta_activas = "SELECT r.id_reserva, r.deporte, r.numero_pista, r.fecha, r.hora 
                     FROM reservas r 
                     INNER JOIN usuarios u ON r.id_usuario = u.id 
                     WHERE u.nombre = '$usuario_nombre' AND r.fecha >= CURDATE() 
                     ORDER BY r.fecha ASC, r.hora ASC";
$resultado_activas = mysqli_query($conexion, $consulta_activas);

// Consulta para el historial de reservas (fechas anteriores a hoy) cruzando con la tabla usuarios
$consulta_historial = "SELECT r.deporte, r.numero_pista, r.fecha, r.hora 
                       FROM reservas r 
                       INNER JOIN usuarios u ON r.id_usuario = u.id 
                       WHERE u.nombre = '$usuario_nombre' AND r.fecha < CURDATE() 
                       ORDER BY r.fecha DESC, r.hora DESC";
$resultado_historial = mysqli_query($conexion, $consulta_historial);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - NovaSport</title>
    <link rel="stylesheet" href="estilo/mis_reservas.css">
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
        
            <?php if (isset($_SESSION['usuario_nombre'])): // Usamos isset para comprobar si la variable existe y no está vacía ?> 
                <span class="user-welcome">
                    Hola, <?php echo $_SESSION['usuario_nombre']; ?>
                </span>
                <a href="cerrar_sesion.php" class="btn-logout">
                    <img src="imagenes/logout.png" alt="Cerrar sesión" id="user">
                </a>
            
            <?php else: // Si el usuario acaba de entrar sin logearse, la variable no existirá e irá al else ?>
                <a href="registro.php">
                    <img src="imagenes/acceso.png" alt="Usuario" id="user"> 
                </a>
            <?php endif; ?>
        </div>
    </header>

    <main id="contenedor-reservas">
        <h1>Mis Reservas</h1>

        <section id="proximas-citas">
            <h2>Próximas Citas</h2>

            <?php // Si el número de filas del resultado de la consulta de próximas citas es mayor que 0, se mostrarán las reservas activas. ?>
            <?php // Si no, se mostrará un mensaje indicando que no hay próximas citas reservadas. ?>
            <?php if (mysqli_num_rows($resultado_activas) > 0): ?>
                <?php while ($reserva = mysqli_fetch_assoc($resultado_activas)): ?>
                    <article class="reserva-activa">
                        <div class="info-reserva">
                            <strong><?php echo strtoupper($reserva['deporte']); ?></strong>
                            <h3>Pista / Campo <?php echo $reserva['numero_pista']; ?></h3>
                            <p><?php echo $reserva['fecha']; ?> (<?php echo $reserva['hora']; ?>)</p>
                        </div>
                        <form action="cancelacion.php" method="POST">
                            <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                            <button type="submit" class="btn-cancelar">Cancelar Reserva</button>
                        </form>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="sin-reservas">No tienes próximas citas reservadas en este momento.</p>
            <?php endif; ?>
        </section>

        <section id="historial-reservas">
            <h2>Historial de Reservas</h2>
            <div class="contenedor-tabla">
                <table id="tabla-historial">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Deporte</th>
                            <th>Pista</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php // Si el número de filas del resultado de la consulta del historial de reservas es mayor que 0, se mostrarán las reservas anteriores. ?>
                        <?php // Si no, se mostrará un mensaje indicando que no hay reservas anteriores en el historial ?>
                        <?php if (mysqli_num_rows($resultado_historial) > 0): ?>
                            <?php while ($historial = mysqli_fetch_assoc($resultado_historial)): ?>
                                <tr>
                                    <td><?php echo $historial['fecha']; ?></td>
                                    <td><?php echo $historial['hora']; ?></td>
                                    <td><?php echo $historial['deporte']; ?></td>
                                    <td>Pista / Campo <?php echo $historial['numero_pista']; ?></td>
                                    <td class="estado-jugado">Jugado</td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="historial-vacio">No hay reservas anteriores en tu historial.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

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