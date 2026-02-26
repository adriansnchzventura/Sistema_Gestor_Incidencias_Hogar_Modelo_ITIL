<?php
require_once 'database_config.php';
session_start();

// Seguridad: Solo logueados
requerirLogin();

$conexion = conectarBD();
$id_usuario = $_SESSION['id_usuario'];

// Capturamos filtro si existe (para filtrar por estado)
$filtro = isset($_GET['estado']) ? sanear($conexion, $_GET['estado']) : '';

// Consulta base
$sql = "SELECT t.*, c.nombre as categoria 
        FROM tickets t 
        JOIN categorias c ON t.id_categoria = c.id_categoria 
        WHERE t.id_usuario = ?";

if ($filtro) {
    $sql .= " AND t.estado = '$filtro'";
}

$sql .= " ORDER BY t.fecha_creacion DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$tickets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

include 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="bi bi-journal-text text-danger me-2"></i>Mis Peticiones</h2>
    <a href="nuevo_ticket.php" class="btn btn-danger btn-sm shadow-sm">
        <i class="bi bi-plus-lg"></i> Nuevo
    </a>
</div>

<div class="mb-4 d-flex gap-2 overflow-auto pb-2">
    <a href="mis_tickets.php" class="btn btn-sm <?= !$filtro ? 'btn-dark' : 'btn-outline-dark' ?>">Todos</a>
    <a href="mis_tickets.php?estado=Pendiente" class="btn btn-sm <?= $filtro == 'Pendiente' ? 'btn-danger' : 'btn-outline-danger' ?>">Pendientes</a>
    <a href="mis_tickets.php?estado=En Progreso" class="btn btn-sm <?= $filtro == 'En Progreso' ? 'btn-warning' : 'btn-outline-warning' ?>">En Progreso</a>
    <a href="mis_tickets.php?estado=Resuelto" class="btn btn-sm <?= $filtro == 'Resuelto' ? 'btn-success' : 'btn-outline-success' ?>">Resueltos</a>
</div>

<div class="row">
    <?php if (count($tickets) > 0): ?>
        <?php foreach ($tickets as $t): ?>
            <div class="col-12 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge mb-1 
                                    <?= $t['criticidad'] == 'P1' ? 'badge-p1' : ($t['criticidad'] == 'P2' ? 'badge-p2' : 'badge-p3') ?>">
                                    <?= $t['criticidad'] ?>
                                </span>
                                <h5 class="card-title fw-bold mb-0"><?= htmlspecialchars($t['titulo']) ?></h5>
                                <small class="text-muted"><?= htmlspecialchars($t['categoria']) ?></small>
                            </div>
                            <span class="badge rounded-pill 
                                <?= $t['estado'] == 'Pendiente' ? 'bg-danger' : ($t['estado'] == 'En Progreso' ? 'bg-warning' : 'bg-success') ?>">
                                <?= $t['estado'] ?>
                            </span>
                        </div>
                        
                        <p class="card-text text-secondary mt-3 mb-3">
                            <?= nl2br(htmlspecialchars($t['descripcion'])) ?>
                        </p>
                        <?php if (!empty($t['notas_resolucion'])): ?>
                            <div class="mt-3 p-3 bg-light border-start border-success border-4 rounded">
                                <small class="fw-bold text-success text-uppercase d-block mb-1">Respuesta del Técnico:</small>
                                <span class="text-dark"><?= nl2br(htmlspecialchars($t['notas_resolucion'])) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="border-top pt-2 d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i> <?= date('d/m/Y H:i', strtotime($t['fecha_creacion'])) ?>
                            </small>
                            <?php if ($t['fecha_resolucion']): ?>
                                <small class="text-success fw-bold">
                                    <i class="bi bi-check-all"></i> Resuelto el <?= date('d/m/Y', strtotime($t['fecha_resolucion'])) ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-emoji-slight-smile fs-1 text-muted"></i>
            <p class="text-muted mt-2">No tienes tickets en esta categoría.</p>
        </div>
    <?php endif; ?>
</div>

<?php 
$conexion->close();
include 'footer.php'; 
?>