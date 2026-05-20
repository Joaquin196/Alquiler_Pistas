<?php
session_start();

/*Esto es un control de seguridad, sirve para verificar que el usuario esté logueado y lo mandará al login*/
/*Por ejemplo, si escribimos en la URL directamente http://localhost/Alquiler_Pistas/principal.php ya no accederá sin cuenta*/
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}
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

    <section id="contenedor-competiciones">
        <h1>Competiciones</h1>

        <section id="seccion-torneos">
            <h2>Próximos Torneos</h2>
            <div id="lista-torneos">
                <article class="item-torneo">
                    <p><strong>07 MAR</strong> - Torneo Pádel Mixto - 15€</p>
                    <a href="apuntarse.html" class="btn-apuntarse">Apuntarse</a>
                </article>
                <article class="item-torneo">
                    <p><strong>21 MAR</strong> - Liga Fútbol 7 - 80€/equipo</p>
                    <a href="apuntarse.html" class="btn-apuntarse">Apuntarse</a>
                </article>
            </div>
        </section>

        <section id="seccion-ranking">
            <h2>Ranking Local</h2>
            <div id="contenedor-tabla">
                <table id="tabla-ranking">
                    <thead>
                        <tr>
                            <th>Posición</th>
                            <th>Jugador</th>
                            <th>Puntos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1º</td>
                            <td>Joaquín Molina</td>
                            <td>1540</td>
                        </tr>
                        <tr>
                            <td>2º</td>
                            <td>Samuel de Luque</td>
                            <td>1320</td>
                        </tr>
                    </tbody>
                </table>
            </div>
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