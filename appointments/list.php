<?php
    session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);
    require_once __DIR__ . "/../controllers/AppointmentController.php";    

    $controller = new AppointmentController();
    $appointments = $controller->index();
  
    
    require __DIR__ . "/../views/appointments/list.php";   

?>