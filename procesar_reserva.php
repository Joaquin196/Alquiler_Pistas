<?php
session_start();

// Control de seguridad
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: logeo.php");
    exit();
}

// Verificar que los datos llegan por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Guardamos temporalmente los datos del formulario en la sesión
    $_SESSION['reserva_deporte'] = $_POST['deporte'];
    $_SESSION['reserva_fecha'] = $_POST['fecha'];
    $_SESSION['reserva_hora'] = $_POST['hora'];
    $_SESSION['reserva_pista'] = $_POST['numero_pista'];
    
    // Guardamos la página de origen dinámicamente para el botón "Cancelar y volver"
    $_SESSION['pagina_origen'] = $_SERVER['HTTP_REFERER'] ?? 'reservar.php';
    
    // Redirigimos a la página de pago universal
    header("Location: pago_pista.php");
    exit();
} else {
    header("Location: reservar.php");
    exit();
}
?>