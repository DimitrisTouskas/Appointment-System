<?php use App\Core\Auth; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://phptutorial.net/app/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?php  $cssVersion=  filemtime(__DIR__ . "/../../public/assets/css/mainStyle.css"); ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mainStyle.css?v=<?=$cssVersion?>">
    
    
    <title>Appointment-System</title>
</head>

<body data-base-url="<?= BASE_URL  ?>">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0">
  <a class="navbar-brand bi bi-calendar-check nav-link active" href="<?= BASE_URL ?>/dashboard">Appointement-System</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="<?= BASE_URL ?>/dashboard">Home <span class="sr-only">(current)</span></a>
      </li>
      <?php if(Auth::isLoggedIn()): ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Appointments
        </a>
        <div class="dropdown-menu"  aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= BASE_URL ?>/appointments">Appointment list</a>
          <a class="dropdown-item" href="<?= BASE_URL ?>/appointments/create">Create new Appointment</a>
        </div>
      </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link" href="#">About</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact Us</a>
      </li>
    </ul>
    <form>
      <div class="search">
        <span class="search-icon material-symbols-outlined">search</span>
        <input class ="search-input" type="search" placeholder="Search">
      </div>
    </form>
    <ul class="navbar-nav">
    <?php if(Auth::isLoggedIn()): ?>
    <li class="nav-item">
        <i class="bi bi-person-circle text-white fs-4"></i>
    </li>
    <li class="nav-item">
        <a class="nav-link text-danger" href="<?= BASE_URL ?>/logout">Logout</a>
    </li>
    <?php else: ?>
      <li class="nav-item">
        <a class="nav-link text-danger" href="<?= BASE_URL ?>/login">Login</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-danger" href="<?= BASE_URL ?>/register">Register</a>
    </li>
    <?php endif; ?>
  </ul>  
</div>
</nav>