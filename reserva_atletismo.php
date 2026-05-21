<?php
session_start();

/*Esto es un control de seguridad, sirve para verificar que el usuario esté logueado y lo mandará al login*/
/*Por ejemplo, si escribimos en la URL directamente http://localhost/Alquiler_Pistas/principal.php ya no accederá sin cuenta*/
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}

/*En este caso, nos conectamos a la base de datos, ya que realizaremos consultas (SELECT, INSERT, UPDATE, DELETE)*/
$conexion = mysqli_connect("localhost", "root", "", "novasport");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="estilo/reserva_atletismo.css">
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
            <img src="imagenes/espana.png" alt="pais" id="bandera">

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
    <section id="foto-reserva">
        <h2>Reserva tu pista de atletismo</h2>
    </section>

    <section id="seccion-formulario">
        <div id="formulario-contenedor">
            <h3>Selecciona los detalles de tu entrenamiento</h3>
            
            <form action="procesar_reserva.php" method="POST">
                <input type="hidden" name="deporte" value="Atletismo">

                <div class="inputs">
                    <label for="fecha">¿Qué día quieres entrenar?</label>
                    <?php /*echo date('Y-m-d') sirve para que el navegador no admita fechas anteriores a la actual */?>
                    <input type="date" id="fecha" name="fecha" required min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="inputs">
                    <label for="hora">Selecciona la hora</label>
                    <select id="hora" name="hora" required>
                        <option value="" disabled selected>Elige un horario</option>
                        <option value="09:00:00">09:00 - 10:30</option>
                        <option value="10:30:00">10:30 - 12:00</option>
                        <option value="12:00:00">12:00 - 13:30</option>
                        <option value="16:30:00">16:30 - 18:00</option>
                        <option value="18:00:00">18:00 - 19:30</option>
                        <option value="19:30:00">19:30 - 21:00</option>
                        <option value="21:00:00">21:00 - 22:30</option>
                    </select>
                </div>

                <div class="inputs">
                    <label for="numero_pista">Selecciona la calle</label>
                    <select id="numero_pista" name="numero_pista" required>
                        <option value="" disabled selected>Elige una calle</option>
                        <option value="1">Calle 1</option>
                        <option value="2">Calle 2</option>
                        <option value="3">Calle 3</option>
                        <option value="4">Calle 4</option>
                    </select>
                </div>

                <button type="submit" class="btn-confirmar">Confirmar y Reservar</button>
            </form>
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