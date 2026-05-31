<?php
session_start();

// Control de seguridad: solo entra el usuario con nombre 'admin'
if (!isset($_SESSION['usuario_nombre']) || $_SESSION['usuario_nombre'] !== 'admin') {
    header("Location: logeo.php");
    exit();
}

include 'conexion.php';

// Logica de borrado que solo se ejecuta si se ha enviado el formulario por POST
// REQUEST_METHOD se refiere a cómo se ha enviado el formulario, en este caso por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Desactivamos temporalmente el control de llaves foráneas para evitar bloqueos por dependencias
    mysqli_query($conexion, "SET FOREIGN_KEY_CHECKS = 0");
    
    // 1. Si se ha pulsado el botón de eliminar de la tabla de usuarios
    // id_usuario_borrar es el nombre del input oculto que hemos puesto en cada fila de la tabla de usuarios para identificar cuál queremos borrar
    if (isset($_POST['id_usuario_borrar'])) {
        $id = $_POST['id_usuario_borrar'];
        $sql_borrar = "DELETE FROM usuarios WHERE id = '$id'";
        mysqli_query($conexion, $sql_borrar);
    } 
    // 2. Si se ha pulsado el botón de eliminar de la tabla de reservas
    // id_reserva_borrar es el nombre del input oculto que hemos puesto en cada fila de la tabla de reservas para identificar cuál queremos borrar
    elseif (isset($_POST['id_reserva_borrar'])) {
        $id = $_POST['id_reserva_borrar'];
        $sql_borrar = "DELETE FROM reservas WHERE id_reserva = '$id'";
        mysqli_query($conexion, $sql_borrar);
    } 
    // 3. Si se ha pulsado el botón de eliminar de la tabla de competiciones
    // id_inscripcion_borrar es el nombre del input oculto que hemos puesto en cada fila de la tabla de competiciones para identificar cuál queremos borrar
    elseif (isset($_POST['id_inscripcion_borrar'])) {
        $id = $_POST['id_inscripcion_borrar'];
        $sql_borrar = "DELETE FROM inscripciones_torneos WHERE id_inscripcion = '$id'";
        mysqli_query($conexion, $sql_borrar);
    }
    
    // Volvemos a activar el control de llaves foráneas
    mysqli_query($conexion, "SET FOREIGN_KEY_CHECKS = 1");
    
    // Recargamos la página limpia para que desaparezca el registro eliminado
    header("Location: admin.php");
    exit();
}

// Consultas adaptadas exactamente al árbol de columnas de SQLyog
$usuarios = mysqli_query($conexion, "SELECT id, nombre, email, fecha_registro FROM usuarios WHERE nombre != 'admin'");
$reservas = mysqli_query($conexion, "SELECT r.id_reserva, u.nombre AS usuario_nombre, r.deporte, r.fecha, r.hora, r.numero_pista FROM reservas r JOIN usuarios u ON r.id_usuario = u.id");
$competiciones = mysqli_query($conexion, "SELECT i.id_inscripcion, u.nombre AS usuario_nombre, t.nombre AS torneo_nombre FROM inscripciones_torneos i JOIN usuarios u ON i.id_usuario = u.id JOIN torneos t ON i.id_torneo = t.id_torneo");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administracion - NovaSport</title>
    <link rel="stylesheet" href="estilo/admin.css">
</head>
<body>

<div class="panel-container">
    <div class="header-panel">
        <h1>Panel de Control de NovaSport</h1>
        <a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesion</a>
    </div>

    <h2>Gestion de Usuarios</h2>
    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Fecha Registro</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = mysqli_fetch_assoc($usuarios)): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['nombre']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['fecha_registro']; ?></td>
                    <td>
                        <form action="admin.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="id_usuario_borrar" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="btn-borrar">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <h2>Gestion de Reservas</h2>
    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID Reserva</th>
                    <th>Usuario</th>
                    <th>Deporte</th>
                    <th>Pista</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php while($res = mysqli_fetch_assoc($reservas)): ?>
                <tr>
                    <td><?php echo $res['id_reserva']; ?></td>
                    <td><?php echo $res['usuario_nombre']; ?></td>
                    <td><?php echo $res['deporte']; ?></td>
                    <td>Pista <?php echo $res['numero_pista']; ?></td>
                    <td><?php echo $res['fecha']; ?></td>
                    <td><?php echo $res['hora']; ?></td>
                    <td>
                        <form action="admin.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="id_reserva_borrar" value="<?php echo $res['id_reserva']; ?>">
                            <button type="submit" class="btn-borrar">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <h2>Inscripciones a Torneos</h2>
    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID Inscripcion</th>
                    <th>Usuario</th>
                    <th>Torneo</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php while($comp = mysqli_fetch_assoc($competiciones)): ?>
                <tr>
                    <td><?php echo $comp['id_inscripcion']; ?></td>
                    <td><?php echo $comp['usuario_nombre']; ?></td>
                    <td><?php echo $comp['torneo_nombre']; ?></td>
                    <td>
                        <form action="admin.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="id_inscripcion_borrar" value="<?php echo $comp['id_inscripcion']; ?>">
                            <button type="submit" class="btn-borrar">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>