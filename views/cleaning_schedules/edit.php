<?php

require_once __DIR__ . '/../layouts/header.php';
?>

<h2>Editar Horario de Limpieza</h2>

<form method="POST" action="/index.php?controller=cleaning&action=update" class="form">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($schedule['id']); ?>">

    <div class="form-group">
        <label for="area">Área *</label>
        <input type="text" id="area" name="area" required
               value="<?php echo htmlspecialchars($schedule['area']); ?>"
               class="<?php echo isset($errors['area']) ? 'error' : ''; ?>">
        <?php if (isset($errors['area'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['area']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="assigned_staff">Responsable *</label>
        <input type="text" id="assigned_staff" name="assigned_staff" required
               value="<?php echo htmlspecialchars($schedule['assigned_staff']); ?>"
               class="<?php echo isset($errors['assigned_staff']) ? 'error' : ''; ?>">
        <?php if (isset($errors['assigned_staff'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['assigned_staff']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="cleaning_date">Fecha *</label>
        <input type="date" id="cleaning_date" name="cleaning_date" required
               value="<?php echo htmlspecialchars($schedule['cleaning_date']); ?>"
               class="<?php echo isset($errors['cleaning_date']) ? 'error' : ''; ?>">
        <?php if (isset($errors['cleaning_date'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['cleaning_date']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="start_time">Hora Inicio *</label>
        <input type="time" id="start_time" name="start_time" required
               value="<?php echo htmlspecialchars(date('H:i', strtotime($schedule['start_time']))); ?>"
               class="<?php echo isset($errors['start_time']) ? 'error' : ''; ?>">
        <?php if (isset($errors['start_time'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['start_time']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="end_time">Hora Fin *</label>
        <input type="time" id="end_time" name="end_time" required
               value="<?php echo htmlspecialchars(date('H:i', strtotime($schedule['end_time']))); ?>"
               class="<?php echo isset($errors['end_time']) ? 'error' : ''; ?>">
        <?php if (isset($errors['end_time'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['end_time']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="status">Estado *</label>
        <select id="status" name="status" required class="<?php echo isset($errors['status']) ? 'error' : ''; ?>">
            <option value="scheduled" <?php echo $schedule['status'] === 'scheduled' ? 'selected' : ''; ?>>Programado</option>
            <option value="completed" <?php echo $schedule['status'] === 'completed' ? 'selected' : ''; ?>>Completado</option>
            <option value="cancelled" <?php echo $schedule['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelado</option>
        </select>
        <?php if (isset($errors['status'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['status']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="notes">Notas</label>
        <textarea id="notes" name="notes" rows="3"><?php echo htmlspecialchars($schedule['notes'] ?? ''); ?></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="/index.php?controller=cleaning&action=index" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
