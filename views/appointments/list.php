<?php /** @var array $appointments */ ?>
<?php /** @var int $currentPage */ ?>
<?php /** @var int $totalPages */



 ?>
<?php require __DIR__ . '/../layout/header.php'; ?>
<main>
    <?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
<div class="row row-cols-1 row-cols-md-3 g-3" data-csrf-token="<?= $_SESSION['csrf_token'] ?>">
        <?php foreach($appointments as $appointment): ?>
        <div class="col">
  <div class="card">
    <div class="card-body">
      <p><?= htmlspecialchars($appointment['appointment_date']) ?></p>
      <p><?= htmlspecialchars($appointment['appointment_time']) ?></p>
      <?php $statusClass = match($appointment['status']){ "pending"=> 'bg-warning' , "completed"=>"bg-success" , "confirmed"=>"bg-primary" , "cancelled"=> "bg-danger" } ?>
      <div class="dropdown">
        <?php $statusText = htmlspecialchars($appointment['status']); ?>
        <?php $appointmentId = htmlspecialchars($appointment['id']);?> 
        <button class="dropdown-toggle badge <?= $statusClass ?>" data-bs-toggle="dropdown" data-appointment-id="<?= $appointmentId ?>"> <?= $statusText ?> </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" data-status="pending">Pending</a></li>
            <li><a class="dropdown-item" data-status="confirmed">Confirmed</a></li>
            <li><a class="dropdown-item" data-status="cancelled">Cancelled</a></li>
            <li><a class="dropdown-item" data-status="completed">Completed</a></li>
        </ul>
      </div>
      <p><?= htmlspecialchars($appointment['notes'] ?? '') ?></p>
      <p><form action="/appointment-system/public/appointments/delete" method="GET">
                <button class="btn btn-danger">Delete</button>
                <input type="hidden" id="appointment_id" name="appointment_id" value="<?= htmlspecialchars($appointment['id']); ?>" />
            </form>
                <form action="/appointment-system/public/appointments/edit" method = "GET" >
                <button class="btn btn-primary">Edit</button>
                <input type="hidden" id="appointment_id" name="appointment_id" value="<?= htmlspecialchars($appointment['id']) ?>" />
            </form>
            </p>
    </div>
  </div>
</div>
        <?php endforeach; ?>
        <div>

        <?php if($currentPage > 1): $prevPage = $currentPage - 1; ?>
            <a href="/appointment-system/public/appointments?page=<?= $prevPage ?>">Previous</a>        
        <?php endif;?>
        <?php if($currentPage < $totalPages): $nextPage = $currentPage + 1; ?>
            <a href="/appointment-system/public/appointments?page=<?= $nextPage ?>">Next</a>
        <?php endif;?>
    </div>
</main>
<script src="/appointment-system/public/assets/js/update-status.js"></script>
<?php require __DIR__ . '/../layout/footer.php'; ?>
