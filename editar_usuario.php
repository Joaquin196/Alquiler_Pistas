<?php
session_start();

if (!isset($_SESSION['usuario_nombre']) || $_SESSION['usuario_nombre'] !== 'admin') {
    header("Location: logeo.php");
    exit();
}

include 'conexion.php';

// A. Cargar los datos actuales del usuario seleccionado
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        header("Location: admin.php");
        exit();
    }
} else {
    header("Location: admin.php");
    exit();
}

// B. Procesar el formulario cuando el admin le dé a "Guardar Cambios"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_update = $_POST['id'];
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_email = $_POST['email'];

    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = :nom, email = :em WHERE id = :id");
    $stmt->execute([
        ':nom' => $nuevo_nombre,
        ':em'  => $nuevo_email,
        ':id'  => $id_update
    ]);

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario (PDO)</title>
    <link rel="stylesheet" href="estilo/admin.css">
</head>
<body>

<div class="panel-container contenedor-editar">
    <h2>Modificar Registro</h2>
    
    <div class="tabla-contenedor">
        <form action="editar_usuario.php?id=<?php echo $id; ?>" method="POST" class="form-crear">
            
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            <div class="form-grupo">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo $user['nombre']; ?>" required>
            </div>

            <div class="form-grupo">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>

            <div class="bloque-botones">
                <button type="submit" class="btn-guardar btn-ancho-auto">Guardar Cambios</button>
                <a href="admin.php" class="btn-logout btn-volver">Volver</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>