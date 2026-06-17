<?php
$host = 'localhost';
$db   = 'novasport'; 
$user = 'root';      
$pass = '';          

// 1. CONEXIÓN ANTIGUA (mysqli) - Para que no se rompa el resto de la práctica
$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error en la conexión mysqli: " . mysqli_connect_error());
}


// 2. NUEVA CONEXIÓN (PDO) - La que usaremos para el nuevo código moderno
try {
    // Creamos el objeto PDO configurando la base de datos y el idioma de caracteres
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    
    // Activamos el modo de errores para que PHP nos avise con excepciones si fallan las Querys
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Si la conexión PDO falla, frena la carga y nos dice el motivo
    die("Error en la conexión PDO: " . $e->getMessage());
}
?>