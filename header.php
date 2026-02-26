<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'database_config.php';

// Detectamos si estamos en la carpeta admin para ajustar las rutas
$base = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte IT Familiar</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $base ?>css/estilos.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= $base ?>index.php">
            <i class="bi bi-tools text-danger me-2"></i>
            <span>Soporte <span class="text-danger">IT</span></span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (estaLogueado()): ?>
                    <li class="nav-item d-none d-md-inline me-2">
                        <span class="small text-muted">Hola, <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong></span>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDrop" role="button" data-bs-toggle="dropdown">
                            <?php if (!empty($_SESSION['imagen_perfil'])): ?>
                                <img src="<?= $base ?>img/perfiles/<?= $_SESSION['imagen_perfiles'] ?? $_SESSION['imagen_perfil'] ?>" 
                                     class="rounded-circle border" width="32" height="32" style="object-fit: cover;">
                            <?php else: ?>
                                <i class="bi bi-person-circle fs-4"></i>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="<?= $base ?>admin/perfil.php"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                            
                            <?php if (tieneRol('administrador')): ?>
                                <li><a class="dropdown-item" href="<?= $base ?>admin/dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Panel Admin</a></li>
                                <li><a class="dropdown-item" href="<?= $base ?>admin/usuarios.php"><i class="bi bi-people me-2"></i>Usuarios</a></li>
                            <?php endif; ?>
                            
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= $base ?>logout.php"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?= $base ?>login.php" class="btn btn-outline-danger btn-sm me-2">Entrar</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base ?>registro.php" class="btn btn-danger btn-sm">Registro</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1">
    <div class="container">