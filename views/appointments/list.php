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
            <?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
            <?php foreach($appointments as $appointment): ?>
            <tr>
                <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                <td><?= htmlspecialchars($appointment['notes'] ?? '') ?></td>
                <td><form action="delete.php" method="POST">
                        <button>Delete</button>
                        <input type="hidden" id="appointment_id" name="appointment_id" value="<?= htmlspecialchars($appointment['id']); ?>" />
                        <input type="hidden" name="security_token" value="<?php echo $_SESSION['csrf_token']; ?>" >
                    </form>
                    <form action="edit.php" method = "GET" >
                        <button>Edit</button>
                        <input type="hidden" id="appointment_id" name="appointment_id" value="<?= htmlspecialchars($appointment['id']) ?>" />
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
</body>
</html>