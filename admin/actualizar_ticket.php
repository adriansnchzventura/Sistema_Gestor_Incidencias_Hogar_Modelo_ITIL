<?php
require_once '../database_config.php';
session_start();
requerirLogin();
requerirRol('administrador');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = conectarBD();
    $id = (int)$_POST['id_ticket'];
    $estado = $_POST['estado'];
    $notas = sanear($conexion, $_POST['notas_resolucion']); // Limpiamos el texto
    
    // Si marcamos como resuelto, grabamos la fecha actual, si no, la dejamos como esté
    $update_fecha = ($estado === 'Resuelto') ? ", fecha_resolucion = NOW()" : "";

    // Actualizamos estado y notas
    $query = "UPDATE tickets SET estado = ?, notas_resolucion = ? $update_fecha WHERE id_ticket = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssi", $estado, $notas, $id);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php?update=success");
    } else {
        echo "Error al actualizar: " . $conexion->error;
    }
    
    $stmt->close();
    $conexion->close();
}