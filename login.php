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
    <title>Iniciar Sesión - Soporte IT Familiar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm p-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-danger">Soporte IT</h2>
                        <p class="text-muted small text-uppercase fw-bold">Acceso a Tickets</p>
                    </div>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger small py-2 text-center">
                            Credenciales incorrectas. Inténtalo de nuevo.
                        </div>
                    <?php endif; ?>

                    <form action="procesar_login.php" method="POST">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="correo@casa.com" required>
                            <label for="email">Email</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                            <label for="password">Contraseña</label>
                        </div>
                        <button type="submit" class="btn btn-danger btn-lg w-100 py-2 fw-bold shadow-sm">
                            Entrar
                        </button>
                    </form>
                    
                    <div class="text-center mt-4 border-top pt-3">
                        <p class="small text-muted mb-0">¿No tienes cuenta? 
                            <a href="registro.php" class="text-danger fw-bold text-decoration-none">Regístrate</a>
                        </p>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="index.php" class="text-muted text-decoration-none small">
                        <i class="bi bi-arrow-left"></i> Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>