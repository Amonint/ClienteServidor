<?php

require_once __DIR__ . '/../layouts/header.php';
?>

<h2>Gestión de Horarios de Limpieza</h2>

<div class="actions">
    <a href="/index.php?controller=cleaning&action=create" class="btn btn-primary">➕ Nuevo Horario</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Área</th>
            <th>Responsable</th>
            <th>Fecha</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($schedules)): ?>
            <tr>
                <td colspan="8" class="text-center">No hay horarios de limpieza registrados</td>
            </tr>
        <?php else: ?>
            <?php foreach ($schedules as $schedule): ?>
                <tr>
                    <td><?php echo htmlspecialchars($schedule['id']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['area']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['assigned_staff']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['cleaning_date']); ?></td>
                    <td><?php echo htmlspecialchars(date('H:i', strtotime($schedule['start_time']))); ?></td>
                    <td><?php echo htmlspecialchars(date('H:i', strtotime($schedule['end_time']))); ?></td>
                    <td>
                        <?php
                        $status = $schedule['status'];
                        $badge = $status === 'completed' ? 'success' : 'warning';
                        ?>
                        <span class="badge badge-<?php echo $badge; ?>"><?php echo htmlspecialchars($status); ?></span>
                    </td>
                    <td class="actions-cell">
                        <a href="/index.php?controller=cleaning&action=edit&id=<?php echo $schedule['id']; ?>" class="btn btn-sm btn-secondary">Editar</a>
                        <a href="/index.php?controller=cleaning&action=delete&id=<?php echo $schedule['id']; ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('¿Está seguro de eliminar este horario?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
