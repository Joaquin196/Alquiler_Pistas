<?php
session_start();

// Control de seguridad
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}

// Si intenta entrar directo sin rellenar ningún formulario, al catálogo de reservas
if (!isset($_SESSION['reserva_fecha'])) {
    header("Location: reservar.php");
    exit();
}

// Recuperamos el retorno dinámico
$volver_a = $_SESSION['pagina_origen'] ?? 'reservar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Reserva - NovaSport</title>
    <link rel="stylesheet" href="estilo/pago_pista.css">
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
            <?php if (isset($_SESSION['usuario_nombre'])): ?> 
                <span class="user-welcome">Hola, <?php echo $_SESSION['usuario_nombre']; ?></span>
                <a href="cerrar_sesion.php" class="btn-logout">
                    <img src="imagenes/logout.png" alt="Cerrar sesión" id="user">
                </a>
            <?php else: ?>
                <a href="registro.php"><img src="imagenes/acceso.png" alt="Usuario" id="user"></a>
            <?php endif; ?>
        </div>
    </header>

    <section id="contenedor-pago">
        <div id="caja-pago">
            <h2>Finalizar Reserva</h2>
            
            <div id="info-resumen">
                <p>Deporte: <?php echo $_SESSION['reserva_deporte']; ?></p>
                <p>Pista: Pista <?php echo $_SESSION['reserva_pista']; ?></p>
                <p>Fecha: <?php echo date("d/m/Y", strtotime($_SESSION['reserva_fecha'])); ?></p>
                <p>Hora: <?php echo substr($_SESSION['reserva_hora'], 0, 5); ?> hs</p>
            </div>
            
            <form id="form-pago" action="reservada.php" method="POST">
                <label for="titular">Nombre del titular</label>
                <input type="text" id="titular" placeholder="Nombre completo" class="campo-pago" required>

                <label for="tarjeta">Número de tarjeta</label>
                <input type="text" id="tarjeta" placeholder="0000 0000 0000 0000" class="campo-pago" required>

                <div id="fila-pago">
                    <div>
                        <label for="caducidad">Caducidad</label>
                        <input type="text" id="caducidad" placeholder="MM/AA" class="campo-pago" required>
                    </div>
                    <div>
                        <label for="cvv">CVV</label>
                        <input type="password" id="cvv" placeholder="123" class="campo-pago" required>
                    </div>
                </div>

                <button type="submit" id="btn-pagar">Confirmar y Pagar 8,50€</button>
            </form>
            
            <a href="<?php echo $volver_a; ?>" id="btn-cancelar-pago">Cancelar y volver</a>
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