<?php
require_once '../database_config.php';
session_start();
requerirRol('administrador');

$id = (int)$_GET['id'];
if ($id != $_SESSION['id_usuario']) {
    $conexion = conectarBD();
    // Nota: Por integridad ITIL, podrías querer borrar sus tickets o reasignarlos
    $conexion->query("DELETE FROM usuarios WHERE id_usuario = $id");
}
header("Location: usuarios.php");