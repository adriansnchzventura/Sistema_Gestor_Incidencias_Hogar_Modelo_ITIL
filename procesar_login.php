<?php
require_once 'database_config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('Location: login.php?error=vacio');
    exit;
}

$conexion = conectarBD();

// MODIFICACIÓN: Añadimos 'imagen_perfil' a la consulta SELECT
$stmt = $conexion->prepare("SELECT id_usuario, nombre, password, rol, imagen_perfil FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($password, $usuario['password'])) {
        
        // LOGIN EXITOSO: Guardamos datos en sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];
        // NUEVA LÍNEA: Aquí guardamos la foto para que el header la reconozca
        $_SESSION['imagen_perfil'] = $usuario['imagen_perfil']; 
        
        $_SESSION['ultima_actividad'] = time();

        $stmt->close();
        $conexion->close();
        
        header('Location: index.php');
        exit;
    }
}

// Si llega aquí, es que falló algo
$stmt->close();
$conexion->close();
header('Location: login.php?error=credenciales');
exit;