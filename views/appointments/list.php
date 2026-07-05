<?php /** @var array $appointments */ ?>
<?php /** @var int $currentPage */ ?>
<?php /** @var int $totalPages */ ?>
<?php require __DIR__ . '/../layout/header.php'; ?>
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
                <td><form action="/appointment-system/public/appointments/delete" method="GET">
                        <button>Delete</button>
                        <input type="hidden" id="appointment_id" name="appointment_id" value="<?= htmlspecialchars($appointment['id']); ?>" />
                    </form>
                    <form action="/appointment-system/public/appointments/edit" method = "GET" >
                        <button>Edit</button>
                        <input type="hidden" id="appointment_id" name="appointment_id" value="<?= htmlspecialchars($appointment['id']) ?>" />
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
        <?php if($currentPage > 1): $prevPage = $currentPage - 1; ?>
            <a href="/appointment-system/public/appointments?page=<?= $prevPage ?>">Previous</a>        
        <?php endif;?>
        <?php if($currentPage < $totalPages): $nextPage = $currentPage + 1; ?>
            <a href="/appointment-system/public/appointments?page=<?= $nextPage ?>">Next</a>
        <?php endif;?>
    </div>
<?php require __DIR__ . '/../layout/footer.php'; ?>