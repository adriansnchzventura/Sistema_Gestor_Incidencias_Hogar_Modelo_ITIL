<?php
require_once 'database_config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registro.php');
    exit;
}

$conexion = conectarBD();

// Recogida de datos
$nombre = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

// 1. Validaciones básicas
if (empty($nombre) || empty($apellidos) || empty($email) || empty($password)) {
    header('Location: registro.php?error=datos_vacios');
    exit;
}

if ($password !== $confirm || strlen($password) < 6) {
    header('Location: registro.php?error=password');
    exit;
}

// 2. Comprobar si el email ya existe
$stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    header('Location: registro.php?error=email_duplicado');
    exit;
}

// 3. Inserción - FORZAMOS ROL 'usuario'
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$rol_por_defecto = 'usuario'; // Inalterable desde el registro

$query = "INSERT INTO usuarios (nombre, apellidos, email, password, rol) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($query);
$stmt->bind_param("sssss", $nombre, $apellidos, $email, $passwordHash, $rol_por_defecto);

if ($stmt->execute()) {
    $idUsuario = $stmt->insert_id;
    
    // Auto-login tras registro
    $_SESSION['id_usuario'] = $idUsuario;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['rol'] = $rol_por_defecto;
    $_SESSION['ultima_actividad'] = time();

    header('Location: index.php?registro=exito');
} else {
    header('Location: registro.php?error=db');
}

$stmt->close();
$conexion->close();