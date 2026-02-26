<?php
require_once '../database_config.php';
session_start();

// Seguridad: Solo tú entras aquí
requerirLogin();
requerirRol('administrador');

$conexion = conectarBD();

// Consulta para ver todos los tickets con el nombre del usuario que lo creó
$sql = "SELECT t.*, u.nombre as remitente, c.nombre as categoria 
        FROM tickets t 
        JOIN usuarios u ON t.id_usuario = u.id_usuario 
        JOIN categorias c ON t.id_categoria = c.id_categoria 
        ORDER BY FIELD(t.criticidad, 'P1', 'P2', 'P3'), t.fecha_creacion DESC";

$res = $conexion->query($sql);
$tickets = $res->fetch_all(MYSQLI_ASSOC);

include '../header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold"><i class="bi bi-shield-lock-fill text-primary"></i> Panel de Control ITIL</h2>
    <p class="text-muted">Gestión global de incidencias domésticas.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Prioridad</th>
                    <th>Remitente</th>
                    <th>Incidencia</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($tickets as $t): 
                        // 1. Definimos los límites en horas según la criticidad
                        $limites = [
                            'P1' => 4,  // 4 horas
                            'P2' => 24, // 24 horas
                            'P3' => 72  // 72 horas
                        ];

                        $horas_limite = $limites[$t['criticidad']];
                        $segundos_limite = $horas_limite * 3600;

                        // 2. Calculamos el tiempo transcurrido
                        $fecha_creacion = strtotime($t['fecha_creacion']);
                        $tiempo_transcurrido = time() - $fecha_creacion;

                        // 3. Comprobamos si ha superado su SLA específico (solo si no está resuelto)
                        $fuera_de_sla = ($tiempo_transcurrido > $segundos_limite && $t['estado'] != 'Resuelto');
                    ?>
                    <tr class="<?= $fuera_de_sla ? 'table-warning' : '' ?> <?= $t['criticidad'] == 'P1' && $t['estado'] != 'Resuelto' ? 'table-danger' : '' ?>">
                        <td>
                            <span class="badge <?= $t['criticidad'] == 'P1' ? 'bg-danger' : ($t['criticidad'] == 'P2' ? 'bg-warning text-dark' : 'bg-success') ?>">
                                <?= $t['criticidad'] ?>
                            </span>
                        </td>
                        <td class="fw-bold">
                            <?= htmlspecialchars($t['remitente']) ?>
                            <?php if ($fuera_de_sla): ?>
                                <div class="badge bg-dark text-white tiny d-block" style="font-size: 0.6rem; width: fit-content;">
                                    <i class="bi bi-clock-history"></i> Fuera de SLA (> <?= $horas_limite ?>h)
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($t['titulo']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($t['categoria']) ?></small>
                        </td>
                        <td>
                            <span class="badge rounded-pill <?= $t['estado'] == 'Pendiente' ? 'bg-secondary' : ($t['estado'] == 'En Progreso' ? 'bg-primary' : 'bg-success') ?>">
                                <?= $t['estado'] ?>
                            </span>
                        </td>
                        <td class="small">
                            <?= date('d/m H:i', strtotime($t['fecha_creacion'])) ?>
                        </td>
                        <td>
                            <a href="ver_ticket.php?id=<?= $t['id_ticket'] ?>" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> Gestionar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$conexion->close();
include '../footer.php'; 
?>