<?php
require_once '../database_config.php';
session_start();
requerirRol('administrador');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conexion = conectarBD();
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_POST['nombre'], $_POST['email'], $pass, $_POST['rol']);
    $stmt->execute();
}
header("Location: usuarios.php");