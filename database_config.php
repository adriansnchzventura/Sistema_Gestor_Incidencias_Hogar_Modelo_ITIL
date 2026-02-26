<?php
// Configuración de la base de datos
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'rootroot'); // Verifica si tu pass es 'rootroot' o vacía ''
define('DB_NAME', 'sistema_tickets');

// Configuración de Zona Horaria para el SLA
date_default_timezone_set('Europe/Madrid');

// Función para conectar
function conectarBD() {
    $conexion = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    $conexion->set_charset("utf8");
    return $conexion;
}

// Funciones de seguridad y roles
function estaLogueado() {
    return isset($_SESSION['id_usuario']);
}

function tieneRol($rol) {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === $rol;
}

function requerirLogin() {
    if (!estaLogueado()) {
        header("Location: login.php");
        exit();
    }
}

function requerirRol($rol) {
    if (!tieneRol($rol)) {
        header("Location: ../index.php?error=acceso_denegado");
        exit();
    }
}

function sanear($conexion, $datos) {
    return mysqli_real_escape_string($conexion, trim($datos));
}


?>