<?php
    session_start();
    require_once __DIR__ . "/../controllers/AppointmentController.php";
    
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $appointment_id = $_POST['appointment_id']??'';
        

    if($_POST['security_token']=== $_SESSION['csrf_token']){
        $deleteAppointment = new AppointmentController();
        $results = $deleteAppointment->delete($appointment_id);
        header("Location: /appointment-system/appointments/list.php");
    }else{
        echo("Invalid request");
        }
    }
?>