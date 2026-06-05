<?php
session_start();

// Control de seguridad: solo entra admin
if (!isset($_SESSION['usuario_nombre']) || $_SESSION['usuario_nombre'] !== 'admin') {
    header("Location: logeo.php");
    exit();
}

include 'conexion.php';

// Logica de procesamiento (Formularios POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Desactivamos temporalmente las comprobaciones de claves foráneas para evitar errores al borrar registros relacionados
    mysqli_query($conexion, "SET FOREIGN_KEY_CHECKS = 0");

    // 1. Borrar Usuario
    if (isset($_POST['id_usuario_borrar'])) {
        $id = $_POST['id_usuario_borrar'];
        mysqli_query($conexion, "DELETE FROM usuarios WHERE id = '$id'");
    } 
    // 2. Borrar Reserva
    elseif (isset($_POST['id_reserva_borrar'])) {
        $id = $_POST['id_reserva_borrar'];
        mysqli_query($conexion, "DELETE FROM reservas WHERE id_reserva = '$id'");
    } 
    // 3. Borrar Inscripción de Torneo
    elseif (isset($_POST['id_inscripcion_borrar'])) {
        $id = $_POST['id_inscripcion_borrar'];
        mysqli_query($conexion, "DELETE FROM inscripciones_torneos WHERE id_inscripcion = '$id'");
    }
    // 4. Borrar Mensaje de Ayuda
    elseif (isset($_POST['id_mensaje_borrar'])) {
        $id = $_POST['id_mensaje_borrar'];
        mysqli_query($conexion, "DELETE FROM mensajes_ayuda WHERE id_mensaje = '$id'");
    }
    // 5. Crear Nuevo Torneo
    elseif (isset($_POST['crear_torneo'])) {
        $nombre_torneo = $_POST['nombre'];
        $fecha_torneo = $_POST['fecha_texto'];
        $precio_torneo = $_POST['precio'];
        
        mysqli_query($conexion, "INSERT INTO torneos (nombre, fecha_texto, precio) VALUES ('$nombre_torneo', '$fecha_torneo', '$precio_torneo')");
    }

    mysqli_query($conexion, "SET FOREIGN_KEY_CHECKS = 1");
    header("Location: admin.php");
    exit();
}

// Recogida de filtros de búsqueda (Con el metodo GET)
$buscar_usuario = isset($_GET['buscar_usuario']) ? $_GET['buscar_usuario'] : '';
$buscar_deporte = isset($_GET['buscar_deporte']) ? $_GET['buscar_deporte'] : '';

// Consultas a la base de datos 
$usuarios = mysqli_query($conexion, "SELECT id, nombre, email, fecha_registro FROM usuarios WHERE nombre != 'admin' AND nombre LIKE '%$buscar_usuario%'");
$reservas = mysqli_query($conexion, "SELECT r.id_reserva, u.nombre AS usuario_nombre, r.deporte, r.fecha, r.hora, r.numero_pista FROM reservas r JOIN usuarios u ON r.id_usuario = u.id WHERE r.deporte LIKE '%$buscar_deporte%'");
$competiciones = mysqli_query($conexion, "SELECT i.id_inscripcion, u.nombre AS usuario_nombre, t.nombre AS torneo_nombre FROM inscripciones_torneos i JOIN usuarios u ON i.id_usuario = u.id JOIN torneos t ON i.id_torneo = t.id_torneo");
$mensajes = mysqli_query($conexion, "SELECT id_mensaje, nombre, correo, mensaje, fecha_envio FROM mensajes_ayuda");
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

    <?php // Sección de creación de torneos ?>
    <h2>Crear Nuevo Torneo</h2>
    <div class="tabla-contenedor">
        <form action="admin.php" method="POST" class="form-crear">
            <input type="hidden" name="crear_torneo" value="1">
            <div class="form-grupo">
                <label>Nombre del Torneo:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-grupo">
                <label>Fecha o Texto Informativo:</label>
                <input type="text" name="fecha_texto" placeholder="Ej: Sabado 15 de Junio" required>
            </div>
            <div class="form-grupo">
                <label>Precio de Inscripcion:</label>
                <input type="text" name="precio" placeholder="Ej: 15€ por equipo" required>
            </div>
            <button type="submit" class="btn-guardar">Dar de Alta Torneo</button>
        </form>
    </div>

    <h2>Gestion de Usuarios</h2>

    <?php // Formulario de búsqueda de usuarios ?>
    <form method="GET" action="admin.php" class="form-filtro">
        <input type="text" name="buscar_usuario" placeholder="Buscar usuario por nombre..." value="<?php echo $buscar_usuario; ?>">
        <button type="submit" class="btn-filtrar">Buscar</button>
    </form>
    
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
                        <div class="acciones-celda">
                            <a href="editar_usuario.php?id=<?php echo $user['id']; ?>" class="btn-editar">Editar</a>
                            
                            <form action="admin.php" method="POST" class="form-inline">
                                <input type="hidden" name="id_usuario_borrar" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn-borrar">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <h2>Gestion de Reservas</h2>

    <?php // Formulario de filtro de reservas por deporte ?>
    <form method="GET" action="admin.php" class="form-filtro">
        <input type="text" name="buscar_deporte" placeholder="Filtrar por deporte (Padel, Futbol...)..." value="<?php echo $buscar_deporte; ?>">
        <button type="submit" class="btn-filtrar">Filtrar</button>
    </form>

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
                        <form action="admin.php" method="POST" class="form-inline">
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
                        <form action="admin.php" method="POST" class="form-inline">
                            <input type="hidden" name="id_inscripcion_borrar" value="<?php echo $comp['id_inscripcion']; ?>">
                            <button type="submit" class="btn-borrar">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <h2>Mensajes de Soporte y Ayuda</h2>

    <?php // Sección de mensajes de ayuda con opción de marcar como atendido (borrar) ?>
    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID Mensaje</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Mensaje</th>
                    <th>Fecha Envio</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php while($msg = mysqli_fetch_assoc($mensajes)): ?>
                <tr>
                    <td><?php echo $msg['id_mensaje']; ?></td>
                    <td><?php echo $msg['nombre']; ?></td>
                    <td><?php echo $msg['correo']; ?></td>
                    <td><?php echo $msg['mensaje']; ?></td>
                    <td><?php echo $msg['fecha_envio']; ?></td>
                    <td>
                        <form action="admin.php" method="POST" class="form-inline">
                            <input type="hidden" name="id_mensaje_borrar" value="<?php echo $msg['id_mensaje']; ?>">
                            <button type="submit" class="btn-borrar btn-atendido">Atendido / Borrar</button>
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