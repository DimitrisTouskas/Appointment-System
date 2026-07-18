<?php /** @var array $appointment */ ?>
<?php require __DIR__ . '/../layout/header.php'; ?>
    <main>
    <div class="card mx-auto" style="max-width: 420px">
        <div class="card-body">
            <h5>Edit Appointment</h5>
            <form action="/appointment-system/public/appointments/edit" method="POST" id="editForm">
                <div class="mb-3">
                <label for="date" class="form-label" >Appointment Date</label>
                <input type="date" name="appointment_date" id="appointment_date" class="form-control" value="<?= htmlspecialchars($appointment['appointment_date']) ?>">
                </div>
                <div class="mb-3">
                <label for="time" class="form-label">Appointment Time</label>
                <input type="time" name="appointment_time" id="appointment_time" class="form-control" value="<?= htmlspecialchars($appointment['appointment_time']) ?>">
                </div>
                <div class="mb-3">
                <label for="date" class="form-label" >Appointment Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="pending" <?= $appointment['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="confirmed"<?= $appointment['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="cancelled"<?= $appointment['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    <option value="completed"<?= $appointment['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>
                </div>
                <div class="mb-3">
                <label for="date" class="form-label">Appointment Notes to Remember</label>
                <textarea name="appointment_notes" id="appointment_notes" class="form-control"><?= htmlspecialchars($appointment['notes'] ?? '') ?></textarea>
                </div>
                <input type="hidden" id="appointment_id" name="appointment_id" value="<?= htmlspecialchars($appointment['id']) ?>" />
                <input type="hidden" id="security_token" name="security_token" value="<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
                echo $_SESSION['csrf_token']; ?>" />
                <button type="submit" class="btn btn-primary" >Edit Appointment</button>
            </form>
        </div>
    </div>
</main>
<script src="/appointment-system/public/assets/js/edit-appointment.js"></script>
<?php require __DIR__ . '/../layout/footer.php'; ?>