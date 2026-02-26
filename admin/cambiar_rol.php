<?php
require_once '../database_config.php';
session_start();
requerirLogin();
requerirRol('administrador');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = conectarBD();
    $id = (int)$_POST['id_usuario'];
    $nuevo_rol = $_POST['nuevo_rol'];

    // Evitar que te quites el admin a ti mismo (ya lo hace el HTML, pero por seguridad)
    if ($id !== $_SESSION['id_usuario']) {
        $stmt = $conexion->prepare("UPDATE usuarios SET rol = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $nuevo_rol, $id);
        $stmt->execute();
    }
    
    $conexion->close();
    header("Location: usuarios.php?update=success");
}