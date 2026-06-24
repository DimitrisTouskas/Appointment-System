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
    <title>Appointment-System</title>
</head>
<style>
  nav ul {
    display: inline-block;
    list-style-type: none;
    margin-left:auto;
    margin-right: 100px;
    display: inline-block;
    justify-content: space-between;
}

.search {
    width: max-content;
    display: flex;
    align-items: center;
    padding: 14px;
    border-radius: 28px;
    background: #f6f6f6;
    margin-left:auto;
    margin-right: 100px;
}

.search-input {
    font-size: 16px;
    font-family: "Lexend", sans-serif;
    color: #333333;
    margin-left: 14px;
    border-radius: 28px;
}
</style>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
       </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Appointments
        </a>
        <div class="dropdown-menu"  aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="../../appointments/list.php">Appointment list</a>
          <a class="dropdown-item" href="../appointments/create.php">Create new Appointment</a>
        </div>
      </li>
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
    <ul class="navbar-nav ms-auto">
    <li class="nav-item">
        <a class="nav-link text-danger" href="../../auth/logout.php">Logout</a>
    </li>
</ul>
</div>
</nav>
</body>
</html>