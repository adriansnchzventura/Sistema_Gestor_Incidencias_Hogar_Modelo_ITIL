<?php
require_once 'database_config.php';
session_start();

// Solo usuarios logueados pueden entrar
requerirLogin();

$conexion = conectarBD();

// Obtenemos las categorías de la base de datos para el desplegable
$resCategorias = $conexion->query("SELECT id_categoria, nombre FROM categorias");
$categorias = $resCategorias->fetch_all(MYSQLI_ASSOC);

include 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h3 class="fw-bold text-dark mb-4">
                    <i class="bi bi-send-plus text-danger me-2"></i>Nueva Incidencia
                </h3>

                <form action="guardar_ticket.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">¿Qué está pasando? (Resumen)</label>
                        <input type="text" name="titulo" class="form-control" placeholder="Ej: No funciona el WiFi en mi cuarto" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Categoría del problema</label>
                        <select name="id_categoria" class="form-select" required>
                            <option value="" selected disabled>Selecciona una opción...</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nivel de Urgencia (Criticidad)</label>
                        <div class="d-flex gap-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="criticidad" id="p1" value="P1" required>
                                <label class="form-check-label badge bg-danger" for="p1">P1 - Crítica</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="criticidad" id="p2" value="P2">
                                <label class="form-check-label badge bg-warning text-dark" for="p2">P2 - Media</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="criticidad" id="p3" value="P3" checked>
                                <label class="form-check-label badge bg-success" for="p3">P3 - Baja</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">Cuéntame los detalles (especialmente si marcaste "Otros")</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Describe el error paso a paso..." required></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-lg shadow-sm">
                            <i class="bi bi-megaphone-fill me-2"></i>Enviar al Informático
                        </button>
                        <a href="index.php" class="btn btn-link text-muted text-decoration-none small">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$conexion->close();
include 'footer.php'; 
?>