<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    use App\Controllers\AppointmentController;
    
    session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

    $controller = new AppointmentController();
    $appointments = $controller->index();
  
    
    require __DIR__ . "/../views/appointments/list.php";   

?>