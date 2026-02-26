<?php
require_once '../database_config.php';
session_start();
requerirRol('administrador');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    $conexion = conectarBD();
    $id = (int)$_POST['id_usuario'];
    $file = $_FILES['foto'];

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nombre_foto = "perfil_" . $id . "_" . time() . "." . $ext;
    $ruta = "../img/perfiles/" . $nombre_foto;

    if (move_uploaded_file($file['tmp_name'], $ruta)) {
        $stmt = $conexion->prepare("UPDATE usuarios SET imagen_perfil = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $nombre_foto, $id);
        $stmt->execute();
    }
    header("Location: usuarios.php");
}