<?php /** @var array $appointment */ ?>
<?php require __DIR__ . '/../layout/header.php'; ?>
    <main>
    <div> 
        <form action="/appointment-system/public/appointments/delete" method="POST">
            <label for="date" class="form-label" >Appointment Date</label>
            <span><?= htmlspecialchars($appointment['appointment_date']??'') ?></span>
            <label for="time" class="form-label">Appointment Time</label>
            <span><?= htmlspecialchars($appointment['appointment_time']??'') ?></span>
            <label for="date" class="form-label" >Appointment Status</label>
            <span><?= htmlspecialchars($appointment['status']??'') ?></span>
            <label for="date" class="form-label">Appointment Notes to Remember</label>
            <span><?= htmlspecialchars($appointment['notes']??'') ?></span>
            <input type="hidden" id="appointment_id" name="appointment_id" value="<?= htmlspecialchars($appointment['id']) ?>" />
            <input type="hidden" id="security_token" name="security_token" value="<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
            echo $_SESSION['csrf_token']; ?>" />
            <button type="submit" class="btn btn-danger" >Delete Appointment</button>
            <a href="/appointment-system/public/appointments" class="btn btn-secondary" >Cancel</a> 
        </form>
    </div>
    </main>
<?php require __DIR__ . '/../layout/footer.php'; ?>