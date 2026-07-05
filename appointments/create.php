<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    use App\Controllers\AppointmentController;
    
    session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

    if ($_SERVER["REQUEST_METHOD"]==="POST"){
        $appointment_date = $_POST["appointment_date"]??'';
        $appointment_time = $_POST["appointment_time"]??'';
        $appointment_notes = $_POST["appointment_notes"]??'';
        
    if($_POST['security_token']=== $_SESSION['csrf_token']){
        $createAppointment = new AppointmentController($appointment_date , $appointment_time , $appointment_notes);
        $result = $createAppointment->create();
    
        echo json_encode($result);
        unset($_SESSION['csrf_token']);
        }else
            echo("Invalid request");
    }
?>