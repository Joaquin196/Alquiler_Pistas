<?php
session_start();

// 1. Control de seguridad: Solo el admin puede estar aquí
if (!isset($_SESSION['usuario_nombre']) || $_SESSION['usuario_nombre'] !== 'admin') {
    header("Location: logeo.php");
    exit();
}

include 'conexion.php';

// 2. CAPTURA DE DATOS ACTUALES (Método GET)
if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];
    
    // Buscamos los datos de este usuario en la base de datos
    $resultado = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id = '$id_usuario'");
    
    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
    } else {
        header("Location: admin.php");
        exit();
    }
} else {
    header("Location: admin.php");
    exit();
}

// 3. PROCESAMIENTO DEL CAMBIO (Método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_update = $_POST['id_usuario'];
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_email = $_POST['email'];

    // Ejecutamos la sentencia UPDATE en la base de datos
    $sql_update = "UPDATE usuarios SET nombre = '$nuevo_nombre', email = '$nuevo_email' WHERE id = '$id_update'";
    
    if (mysqli_query($conexion, $sql_update)) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error al actualizar el usuario: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - NovaSport</title>
    <link rel="stylesheet" href="estilo/admin.css">
    <link rel="stylesheet" href="estilo/editar_usuario.css">
</head>
<body>

<div class="form-editar-container">
    <h2>Modificar Datos de Usuario</h2>
    <p>Estás editando el perfil de: <?php echo $usuario['nombre']; ?> (ID: <?php echo $usuario['id']; ?>)</p>  

    <form action="editar_usuario.php?id=<?php echo $id_usuario; ?>" method="POST" class="form-crear">
        
        <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">

        <div class="form-grupo">
            <label>Nombre de Usuario:</label>
            <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
        </div>

        <div class="form-grupo">
            <label>Correo Electrónico:</label>
            <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required>
        </div>

        <div class="botones-contenedor">
            <button type="submit" class="btn-guardar">Guardar Cambios</button>
            <a href="admin.php" class="btn-cancelar">Volver Atrás</a>
        </div>
    </form>
</div>

</body>
</html>