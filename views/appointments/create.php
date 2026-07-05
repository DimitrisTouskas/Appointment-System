<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">

    <title>Document</title>
</head>
<body>
    <main>
    <div> 
        <form action="/appointment-system/public/appointments/create" method="POST" onsubmit="return validateForm()">
            <label for="date" class="form-label" >Appointment Date</label>
            <input type="date" name="appointment_date" id="appointment_date" class="form-control">
            <label for="time" class="form-label">Appointment Time</label>
            <input type="time" name="appointment_time" id="appointment_time" class="form-control">
            <label for="date" class="form-label">Appointment Notes to Remember</label>
            <textarea name="appointment_notes" id="appointment_notes" class="form-control"></textarea>
            <input type="hidden" id="security_token" name="security_token" value="<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
            echo $_SESSION['csrf_token']; ?>" />
            <button type="submit" class="btn btn-primary" >Set New Appointment</button>
            
        </form>
    </div>
    </main>
<script src="../../public/assets/js/create-appointment.js"></script>
</body>
</html>