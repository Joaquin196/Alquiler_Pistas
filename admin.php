<?php
session_start();

// Control de seguridad básico
// Si no hay sesión iniciada o el usuario no es 'admin', redirigir a logeo.php
if (!isset($_SESSION['usuario_nombre']) || $_SESSION['usuario_nombre'] !== 'admin') {
    header("Location: logeo.php");
    exit();
}

include 'conexion.php';

// ==========================================
// 1. CREAR (CREATE) -> Procesar inserción
// ==========================================
if (isset($_POST['crear_usuario'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    // Sentencia preparada para evitar inyecciones SQL    
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email) VALUES (:nom, :em)");
    $stmt->execute([
        ':nom' => $nombre,
        ':em'  => $email
    ]);

    header("Location: admin.php");
    exit();
}

// ==========================================
// 2. BORRAR (DELETE) -> Procesar eliminación
// ==========================================
if (isset($_GET['borrar_id'])) {
    $id_borrar = $_GET['borrar_id'];

    // Desactivamos claves foráneas por si ese usuario tiene reservas, para que no dé error
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id_borrar]);

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    header("Location: admin.php");
    exit();
}

// ==========================================
// 3. LEER (READ) -> Con Filtro de Búsqueda
// ==========================================

// A. Inicializamos la variable de búsqueda vacía por defecto
$buscar = "";

// B. Si el admin ha escrito algo en el buscador, capturamos el texto
if (isset($_GET['buscar_usuario'])) {
    $buscar = $_GET['buscar_usuario'];
}

// C. Preparamos la consulta usando LIKE y un marcador de posición
$stmt_usuarios = $pdo->prepare("SELECT id, nombre, email FROM usuarios WHERE nombre != 'admin' AND (nombre LIKE :buscar OR email LIKE :buscar)");

// D. Ejecutamos pasando el texto rodeado de porcentajes % (comodines de SQL)
$stmt_usuarios->execute([
    ':buscar' => "%" . $buscar . "%"
]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel CRUD</title>
    <link rel="stylesheet" href="estilo/admin.css">
</head>
<body>

<div class="panel-container">
    
    <div class="header-panel">
        <h1>Panel de Administración</h1>
        <a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesión</a>
    </div>

    <h2>Añadir Nuevo Usuario</h2>
    <div class="tabla-contenedor">
        <form action="admin.php" method="POST" class="form-crear">
            <input type="hidden" name="crear_usuario" value="1">
            <div class="form-grupo">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-grupo">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit" class="btn-guardar">Registrar Usuario</button>
        </form>
    </div>

    <h2>Buscar Usuarios</h2>
    <div class="tabla-contenedor">
        <form action="admin.php" method="GET" class="form-buscar">
            <input type="text" name="buscar_usuario" placeholder="Buscar por nombre o email..." value="<?php echo htmlspecialchars($buscar); ?>" class="input-buscar">
            
            <button type="submit" class="btn-guardar btn-buscar">Buscar</button>
            
            <?php if ($buscar !== ""): ?>
                <a href="admin.php" class="btn-logout btn-volver">Limpiar</a>
            <?php endif; ?>
        </form>
    </div>

    <h2>Listado de Usuarios</h2>
    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php // El while se encarga de recorrer todos los registros obtenidos de la base de datos y mostrarlos en la tabla ?>
                <?php while ($user = $stmt_usuarios->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['nombre']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        <div class="acciones-celda">
                            <a href="editar_usuario.php?id=<?php echo $user['id']; ?>" class="btn-editar">Editar</a>
                            
                            <a href="admin.php?borrar_id=<?php echo $user['id']; ?>" class="btn-borrar" onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">Eliminar</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>