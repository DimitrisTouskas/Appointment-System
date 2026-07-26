<?php
    use App\Controllers\AppointmentController;

    $page = (int)( $_GET['page']?? 1 );
    $status =empty($_GET['status']) ? NULL : $_GET['status'];
    $searchTerm = $_GET['searchTerm']?? NULL;
    $sort = $_GET['sort']?? 'asc';

    $controller = new AppointmentController();
    $result = $controller ->index(page:$page , status:$status, sort:$sort , searchTerm:$searchTerm);

    $appointments = $result['appointments'];
    $currentPage = $result['currentPage'];
    $totalPages = $result['totalPages'];
  
    
    require __DIR__ . "/../views/appointments/list.php";   

?>