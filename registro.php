<?php
require_once 'database_config.php';
session_start();

if (estaLogueado()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unirse al Soporte Familiar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-danger">Soporte IT Familiar</h2>
                    <p class="text-muted">Crea tu cuenta para empezar a enviar tickets</p>
                </div>
                <div class="card border-0 shadow-sm p-4">
                    <form action="procesar_registro.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Nombre</label>
                                <input type="text" name="nombre" class="form-control" required placeholder="Ej: Mamá">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" required placeholder="Ej: García">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="correo@casa.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Contraseña</label>
                            <input type="password" name="password" class="form-control" required minlength="6" placeholder="Mínimo 6 caracteres">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Confirmar Contraseña</label>
                            <input type="password" name="confirm_password" class="form-control" required placeholder="Repite la contraseña">
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-2 mt-3 fw-bold">
                            <i class="bi bi-person-plus-fill me-2"></i>Registrarse
                        </button>
                    </form>
                </div>
                <div class="text-center mt-4">
                    <p class="small text-muted">¿Ya tienes cuenta? <a href="login.php" class="text-danger fw-bold text-decoration-none">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>