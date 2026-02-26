<?php
// perfil.php (Ubicado en la carpeta admin)
session_start();
require_once '../database_config.php'; // Salimos de admin para buscar el config

// Verificamos login
requerirLogin();

$conexion = conectarBD();
$id_usuario = $_SESSION['id_usuario'];
$mensaje = "";
$tipo_alerta = "success";

// 1. PROCESAR CAMBIOS (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // --- SUBIDA DE FOTO ---
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $permitidos = ['jpg', 'jpeg', 'png'];
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        
        if (in_array(strtolower($ext), $permitidos)) {
            $nombre_foto = "user_" . $id_usuario . "_" . time() . "." . $ext;
            // La ruta es relativa a este archivo: subir un nivel y entrar en img
            $ruta_destino = "../img/perfiles/" . $nombre_foto;
            
            // Crear carpeta si no existe
            if (!is_dir("../img/perfiles/")) {
                mkdir("../img/perfiles/", 0777, true);
            }

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
                // Actualizar DB
                $conexion->query("UPDATE usuarios SET imagen_perfil = '$nombre_foto' WHERE id_usuario = $id_usuario");
                
                // Actualizar Sesión para que el header cambie al instante
                $_SESSION['imagen_perfil'] = $nombre_foto; 
                $mensaje = "Foto de perfil actualizada correctamente.";
            } else {
                $mensaje = "Error al guardar el archivo en el servidor.";
                $tipo_alerta = "danger";
            }
        } else {
            $mensaje = "Formato no permitido. Solo JPG o PNG.";
            $tipo_alerta = "danger";
        }
    }

    // --- CAMBIO DE CONTRASEÑA ---
    if (!empty($_POST['pass_nueva'])) {
        $pass_hash = password_hash($_POST['pass_nueva'], PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("UPDATE usuarios SET password = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $pass_hash, $id_usuario);
        
        if ($stmt->execute()) {
            $mensaje = ($mensaje == "") ? "Contraseña actualizada con éxito." : "Perfil actualizado por completo.";
        }
        $stmt->close();
    }
}

// 2. OBTENER DATOS DEL USUARIO PARA EL FORMULARIO
$res = $conexion->query("SELECT * FROM usuarios WHERE id_usuario = $id_usuario");
$user = $res->fetch_assoc();

include '../header.php'; // Salimos de admin para buscar el header
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm border-top border-primary border-4">
                <div class="card-body p-4 text-center">
                    <h3 class="fw-bold mb-4">Configuración de Perfil</h3>
                    
                    <?php if ($mensaje): ?>
                        <div class="alert alert-<?= $tipo_alerta ?> alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle me-2"></i><?= $mensaje ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="perfil.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <div class="position-relative d-inline-block">
                                <?php 
                                // Ruta de la imagen: salimos de admin para entrar en img
                                $ruta_foto = "../img/perfiles/" . ($user['imagen_perfil'] ?? '');
                                if (!empty($user['imagen_perfil']) && file_exists($ruta_foto)): ?>
                                    <img src="<?= $ruta_foto ?>" class="rounded-circle border shadow-sm" width="150" height="150" style="object-fit: cover;">
                                <?php else: ?>
                                    <i class="bi bi-person-circle display-1 text-secondary"></i>
                                <?php endif; ?>
                                
                                <div class="mt-3">
                                    <label class="btn btn-sm btn-primary px-3 shadow-sm">
                                        <i class="bi bi-camera me-1"></i> Cambiar Foto
                                        <input type="file" name="foto" class="d-none" onchange="this.form.submit()">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="text-start">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">NOMBRE Y APELLIDOS</label>
                                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['nombre'] . ' ' . $user['apellidos']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">CORREO ELECTRÓNICO</label>
                                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-primary">NUEVA CONTRASEÑA</label>
                                <input type="password" name="pass_nueva" class="form-control border-primary" placeholder="Escribe aquí si quieres cambiarla">
                                <small class="text-muted mt-1 d-block">Mínimo 6 caracteres recomendados.</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 mt-3 shadow-sm py-2">
                            Actualizar Información
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="../index.php" class="text-muted text-decoration-none">
                    <i class="bi bi-house-door me-1"></i> Volver al Inicio
                </a>
            </div>
        </div>
    </div>
</div>

<?php 
$conexion->close();
include '../footer.php'; 
?>