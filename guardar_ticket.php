<?php
require_once 'database_config.php';
session_start();

requerirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: nuevo_ticket.php');
    exit;
}

$conexion = conectarBD();

// Saneamos los datos usando la función de tu config
$id_usuario = $_SESSION['id_usuario'];
$id_categoria = (int)$_POST['id_categoria'];
$titulo = sanear($conexion, $_POST['titulo']);
$descripcion = sanear($conexion, $_POST['descripcion']);
$criticidad = $_POST['criticidad']; // P1, P2, P3

// Query de inserción
$query = "INSERT INTO tickets (id_usuario, id_categoria, titulo, descripcion, criticidad, estado, fecha_creacion) 
          VALUES (?, ?, ?, ?, ?, 'Pendiente', NOW())";

$stmt = $conexion->prepare($query);
$stmt->bind_param("iisss", $id_usuario, $id_categoria, $titulo, $descripcion, $criticidad);

if ($stmt->execute()) {
    header('Location: index.php?envio=exito');
} else {
    header('Location: nuevo_ticket.php?error=db');
}

$stmt->close();
$conexion->close();