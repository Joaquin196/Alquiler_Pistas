<?php
session_start();

// 1. Control de seguridad: Solo el admin puede estar aquí
if (!isset($_SESSION['usuario_nombre']) || $_SESSION['usuario_nombre'] !== 'admin') {
    header("Location: logeo.php");
    exit();
}

include 'conexion.php';

// 2. Captura de la reserva actual (Método GET)
if (isset($_GET['id'])) {
    $id_reserva = $_GET['id'];
    
    // Traemos los datos de la reserva cruzándolos con el nombre del usuario para mostrarlo en pantalla
    $sql_reserva = "SELECT r.*, u.nombre AS usuario_nombre 
                    FROM reservas r 
                    JOIN usuarios u ON r.id_usuario = u.id 
                    WHERE r.id_reserva = '$id_reserva'";
                    
    $resultado = mysqli_query($conexion, $sql_reserva);
    // Si existe esa reserva, la guardamos en $reserva para mostrarla en el formulario. Si no, volvemos al admin.php
    if (mysqli_num_rows($resultado) == 1) {
        $reserva = mysqli_fetch_assoc($resultado);
    } else {
        header("Location: admin.php");
        exit();
    }
} else {
    header("Location: admin.php");
    exit();
}

// 3. Procesamiento del cambio (Método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_update = $_POST['id_reserva'];
    $nueva_fecha = $_POST['fecha'];
    $nueva_hora = $_POST['hora'];
    $nueva_pista = $_POST['numero_pista'];

    // Ejecutamos el UPDATE de la reserva
    $sql_update = "UPDATE reservas 
                   SET fecha = '$nueva_fecha', hora = '$nueva_hora', numero_pista = '$nueva_pista' 
                   WHERE id_reserva = '$id_update'";
    
    if (mysqli_query($conexion, $sql_update)) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error al actualizar la reserva: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva - NovaSport</title>
    <link rel="stylesheet" href="estilo/admin.css">
    <link rel="stylesheet" href="estilo/editar_usuario.css">
</head>
<body>

<div class="form-editar-container">
    <h2>Modificar Reserva</h2>
    <p>Reserva de: <strong><?php echo $reserva['usuario_nombre']; ?></strong></p>
    <p>Deporte: <strong><?php echo $reserva['deporte']; ?></strong> (ID Reserva: <?php echo $reserva['id_reserva']; ?>)</p>

    <form action="editar_reserva.php?id=<?php echo $id_reserva; ?>" method="POST" class="form-crear">
        
        <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">

        <div class="form-grupo">
            <label>Fecha de la Reserva:</label>
            <input type="date" name="fecha" value="<?php echo $reserva['fecha']; ?>" required>
        </div>

        <div class="form-grupo">
            <label>Hora del Partido:</label>
            <input type="text" name="hora" value="<?php echo $reserva['hora']; ?>" placeholder="Ej: 18:00 - 19:30" required>
        </div>

        <div class="form-grupo">
            <label>Número de Pista:</label>
            <input type="number" name="numero_pista" value="<?php echo $reserva['numero_pista']; ?>" min="1" max="10" required>
        </div>

        <div class="botones-contenedor">
            <button type="submit" class="btn-guardar">Modificar Partido</button>
            <a href="admin.php" class="btn-cancelar">Volver Atrás</a>
        </div>
    </form>
</div>

</body>
</html>