<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'database_config.php';

// 1. Sincronizamos la zona horaria (Evita que el contador falle por desfase de horas)
date_default_timezone_set('Europe/Madrid'); 

$conexion = conectarBD();

$tickets_usuario = [];
if (estaLogueado()) {
    $id_usuario = $_SESSION['id_usuario'];
    
    $query_tickets = "SELECT t.id_ticket, t.titulo, t.estado, t.criticidad, c.nombre as categoria 
                        FROM tickets t 
                        JOIN categorias c ON t.id_categoria = c.id_categoria 
                        WHERE t.id_usuario = ? 
                        ORDER BY t.fecha_creacion DESC LIMIT 5";
    
    $stmt = $conexion->prepare($query_tickets);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $tickets_usuario = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Estadísticas generales
$stats = ['pendientes' => 0, 'en_progreso' => 0, 'resueltos' => 0];
$resP = $conexion->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Pendiente'");
$stats['pendientes'] = $resP ? $resP->fetch_assoc()['total'] : 0;
$resE = $conexion->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'En Progreso'");
$stats['en_progreso'] = $resE ? $resE->fetch_assoc()['total'] : 0;
$resR = $conexion->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Resuelto'");
$stats['resueltos'] = $resR ? $resR->fetch_assoc()['total'] : 0;

$fuera_sla = 0;
if (estaLogueado() && tieneRol('administrador')) {
    $sql_sla = "SELECT COUNT(*) as total FROM tickets 
                WHERE estado != 'Resuelto' 
                AND (
                    (criticidad = 'P1' AND fecha_creacion < DATE_SUB(NOW(), INTERVAL 4 HOUR)) OR
                    (criticidad = 'P2' AND fecha_creacion < DATE_SUB(NOW(), INTERVAL 24 HOUR)) OR
                    (criticidad = 'P3' AND fecha_creacion < DATE_SUB(NOW(), INTERVAL 72 HOUR))
                )";
    $resSLA = $conexion->query($sql_sla);
    if ($resSLA) {
        $fuera_sla = $resSLA->fetch_assoc()['total'];
    }
}

include 'header.php'; 
?>

<section class="py-4">
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="fw-bold">Centro de Soporte Familiar</h1>
            <p class="lead text-muted">Gestión de incidencias IT bajo estándar ITIL.</p>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <div class="col">
            <div class="card h-100 border-0 shadow-sm bg-white border-bottom border-danger border-3 text-center p-3">
                <div class="text-danger mb-2"><i class="bi bi-exclamation-octagon fs-1"></i></div>
                <h6 class="text-muted small fw-bold text-uppercase">Pendientes</h6>
                <p class="display-5 fw-bold mb-0"><?= $stats['pendientes'] ?></p>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 border-0 shadow-sm bg-white border-bottom border-warning border-3 text-center p-3">
                <div class="text-warning mb-2"><i class="bi bi-gear-wide-connected fs-1"></i></div>
                <h6 class="text-muted small fw-bold text-uppercase">En Progreso</h6>
                <p class="display-5 fw-bold mb-0"><?= $stats['en_progreso'] ?></p>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 border-0 shadow-sm bg-white border-bottom border-success border-3 text-center p-3">
                <div class="text-success mb-2"><i class="bi bi-check-circle fs-1"></i></div>
                <h6 class="text-muted small fw-bold text-uppercase">Resueltos</h6>
                <p class="display-5 fw-bold mb-0"><?= $stats['resueltos'] ?></p>
            </div>
        </div>
    </div>

    <?php if (estaLogueado()): ?>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-4 text-center mb-4">
                    <h5 class="fw-bold mb-3">¿Tienes un problema?</h5>
                    <a href="nuevo_ticket.php" class="btn btn-danger btn-lg w-100 shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Nuevo Ticket
                    </a>
                </div>

                <?php if (tieneRol('administrador')): ?>
                    <?php
                    // Usamos TIMESTAMPDIFF para comparar contra la hora real del servidor
                    $sql_sla = "SELECT COUNT(*) as fuera_sla FROM tickets 
                                WHERE estado != 'Resuelto' 
                                AND (
                                    (criticidad = 'P1' AND TIMESTAMPDIFF(HOUR, fecha_creacion, NOW()) >= 4) OR
                                    (criticidad = 'P2' AND TIMESTAMPDIFF(HOUR, fecha_creacion, NOW()) >= 24) OR
                                    (criticidad = 'P3' AND TIMESTAMPDIFF(HOUR, fecha_creacion, NOW()) >= 72)
                                )";
                    $resSLA = $conexion->query($sql_sla);
                    $fuera_sla = ($resSLA) ? $resSLA->fetch_assoc()['fuera_sla'] : 0;
                    ?>
                    <div class="card border-0 shadow-sm p-4 text-center mt-3 border-start border-primary border-4">
                        <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-shield-lock me-2"></i>Gestión IT</h5>
                        
                        <?php if ($fuera_sla > 0): ?>
                            <div class="alert alert-danger py-2 small mb-3">
                                <i class="bi bi-exclamation-triangle-fill"></i> <strong><?= $fuera_sla ?></strong> alertas de SLA activas
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success py-2 small mb-3">
                                <i class="bi bi-check-circle"></i> SLA bajo control
                            </div>
                        <?php endif; ?>

                        <div class="d-grid gap-2">
                            <a href="admin/dashboard.php" class="btn btn-primary btn-sm shadow-sm">Panel de Control</a>
                            <a href="admin/usuarios.php" class="btn btn-outline-primary btn-sm shadow-sm">Gestionar Usuarios</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-list-task me-2"></i>Mis Tickets Recientes</h5>
                    <?php if (count($tickets_usuario) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Incidencia</th>
                                        <th>Estado</th>
                                        <th>Prioridad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tickets_usuario as $t): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold small"><?= htmlspecialchars($t['titulo']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($t['categoria']) ?></small>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill <?= $t['estado'] == 'Pendiente' ? 'bg-danger' : ($t['estado'] == 'En Progreso' ? 'bg-warning text-dark' : 'bg-success') ?>">
                                                    <?= $t['estado'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?= $t['criticidad'] == 'P1' ? 'bg-danger' : ($t['criticidad'] == 'P2' ? 'bg-warning text-dark' : 'bg-success') ?>">
                                                    <?= $t['criticidad'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-2">
                            <a href="mis_tickets.php" class="small text-danger text-decoration-none fw-bold">Ver todo →</a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">No has enviado tickets todavía.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <h4 class="text-muted">Inicia sesión para reportar una incidencia</h4>
            <div class="mt-3">
                <a href="login.php" class="btn btn-outline-danger px-4 me-2">Entrar</a>
                <a href="registro.php" class="btn btn-danger px-4 shadow-sm">Registrarse</a>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php 
// IMPORTANTE: Cerramos la conexión al final de todo
if (isset($conexion)) { $conexion->close(); }
include 'footer.php'; 
?>