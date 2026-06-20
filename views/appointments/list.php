<?php /** @var array $appointments */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>Appointment system</title>
</head>
<body>
    <div>
        <table class="table">
        <thead>
            <tr>
            <th class= "thead">Date</th>
            <th class= "thead">Time</th>
            <th class= "thead">Status</th>
            <th class= "thead">Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($appointments as $appointment): ?>
            <tr>
                <td><?= $appointment['appointment_date'] ?></td>
                <td><?= $appointment['appointment_time'] ?></td>
                <td><?= $appointment['status'] ?></td>
                <td><?= $appointment['notes'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
</body>
</html>