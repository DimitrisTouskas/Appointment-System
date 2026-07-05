<?php
    use App\Controllers\AppointmentController;

    $page = (int)( $_GET['page']?? 1 );

    $controller = new AppointmentController();
    $result = $controller ->index($page);

    $appointments = $result['appointments'];
    $currentPage = $result['currentPage'];
    $totalPages = $result['totalPages'];
  
    
    require __DIR__ . "/../views/appointments/list.php";   

?>