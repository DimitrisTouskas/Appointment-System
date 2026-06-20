<?php
    session_start();
    require_once __DIR__ . "/../controllers/AppointmentController.php";
    
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $appointment_id = $_POST['appointment_id']??'';

        $deleteAppointment = new AppointmentController();
        $results = $deleteAppointment->delete($appointment_id);
        return $results;
    }
?>