<?php require __DIR__ . '/../layout/header.php'; ?>
<style>
    body{
        background-color: #676666;
    }
    div.card-body{
        background-color: #ffffff;
    }
</style>
<main>
    <div class="card mx-auto" style="max-width: 420px"> 
        <div class="card-body">
            <h5>New appointment</h5>
            <p class="text-muted">Pick a date and time, add optional notes.</p>
            <form action="/appointment-system/public/appointments/create" method="POST" id="createForm">
                <div class="mb-3">
                <label for="date" class="form-label" >Appointment Date</label>
                <input type="date" name="appointment_date" id="appointment_date" class="form-control">
                </div>
                <div class="mb-3">
                <label for="time" class="form-label">Appointment Time</label>
                <input type="time" name="appointment_time" id="appointment_time" class="form-control">
                </div>
                <div class="mb-3">
                <label for="date" class="form-label">Appointment Notes to Remember</label>
                <textarea name="appointment_notes" id="appointment_notes" class="form-control"></textarea>
                </div>
                <input type="hidden" id="security_token" name="security_token" value="<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
                echo $_SESSION['csrf_token']; ?>" />
                <button type="submit" class="btn btn-primary" >Set New Appointment</button>
                
            </form>
        </div>
    </div>
</main>
<script src="/appointment-system/public/assets/js/create-appointment.js"></script>
<?php require __DIR__ . '/../layout/footer.php'; ?>
