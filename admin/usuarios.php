<?php
require_once '../database_config.php';
session_start();
requerirLogin();
requerirRol('administrador');

$conexion = conectarBD();

// Consultar todos los usuarios
$sql = "SELECT id_usuario, nombre, apellidos, email, rol, imagen_perfil FROM usuarios ORDER BY apellidos ASC";
$res = $conexion->query($sql);
$usuarios = $res->fetch_all(MYSQLI_ASSOC);

include '../header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="bi bi-people-fill text-primary"></i> Gestión de Usuarios</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario">
        <i class="bi bi-person-plus-fill me-2"></i>Nuevo Miembro
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Perfil</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td>
                        <?php if($u['imagen_perfil']): ?>
                            <img src="../img/perfiles/<?= $u['imagen_perfil'] ?>" class="rounded-circle" width="40" height="40" style="object-fit:cover">
                        <?php else: ?>
                            <i class="bi bi-person-circle fs-2 text-secondary"></i>
                        <?php endif; ?>
                    </td>
                    <td><span class="fw-bold"><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellidos']) ?></span></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><span class="badge <?= $u['rol'] == 'administrador' ? 'bg-primary' : 'bg-light text-dark border' ?>"><?= strtoupper($u['rol']) ?></span></td>
                    <td class="text-end">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('file<?= $u['id_usuario'] ?>').click()">
                                <i class="bi bi-camera"></i>
                            </button>
                            <form action="procesar_foto.php" method="POST" enctype="multipart/form-data" class="d-none">
                                <input type="file" id="file<?= $u['id_usuario'] ?>" name="foto" onchange="this.form.submit()">
                                <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                            </form>

                            <?php if ($u['id_usuario'] != $_SESSION['id_usuario']): ?>
                                <a href="eliminar_usuario.php?id=<?= $u['id_usuario'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar a este usuario?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" action="crear_usuario.php" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Añadir Miembro a la Casa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña Temporal</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-select">
                        <option value="usuario">Usuario Estándar</option>
                        <option value="administrador">Administrador IT</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>