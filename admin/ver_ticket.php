<?php
require_once '../database_config.php';
session_start();
requerirLogin();
requerirRol('administrador');

$conexion = conectarBD();
$id = (int)$_GET['id'];

// Obtener detalle del ticket
$stmt = $conexion->prepare("SELECT t.*, u.nombre as remitente, u.email, c.nombre as categoria 
                             FROM tickets t 
                             JOIN usuarios u ON t.id_usuario = u.id_usuario 
                             JOIN categorias c ON t.id_categoria = c.id_categoria 
                             WHERE t.id_ticket = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$t = $stmt->get_result()->fetch_assoc();

include '../header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <a href="dashboard.php" class="btn btn-link text-decoration-none mb-3"><i class="bi bi-arrow-left"></i> Volver al panel</a>
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Ticket #<?= $t['id_ticket'] ?></h5>
                <span class="badge <?= $t['criticidad'] == 'P1' ? 'bg-danger' : 'bg-secondary' ?>"><?= $t['criticidad'] ?></span>
            </div>
            <div class="card-body">
                <h4 class="fw-bold"><?= htmlspecialchars($t['titulo']) ?></h4>
                <p class="text-muted small">Enviado por <strong><?= htmlspecialchars($t['remitente']) ?></strong> (<?= $t['email'] ?>)</p>
                <hr>
                <p class="bg-light p-3 rounded"><?= nl2br(htmlspecialchars($t['descripcion'])) ?></p>
            </div>
        </div>

        <div class="card border-0 shadow-sm border-start border-primary border-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Actualizar Estado ITIL</h5>
        <form action="actualizar_ticket.php" method="POST">
            <input type="hidden" name="id_ticket" value="<?= $t['id_ticket'] ?>">
            
            <div class="row g-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Nuevo Estado</label>
                    <select name="estado" class="form-select">
                        <option value="Pendiente" <?= $t['estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="En Progreso" <?= $t['estado'] == 'En Progreso' ? 'selected' : '' ?>>En Progreso</option>
                        <option value="Resuelto" <?= $t['estado'] == 'Resuelto' ? 'selected' : '' ?>>Resuelto</option>
                        <option value="Cancelado" <?= $t['estado'] == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label small fw-bold">Notas de Resolución / Comentarios</label>
                    <textarea name="notas_resolucion" class="form-control" rows="3" placeholder="Explica qué has hecho para solucionarlo..."><?= htmlspecialchars($t['notas_resolucion'] ?? '') ?></textarea>
                </div>
                
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>