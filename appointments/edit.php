<?php
    session_start();
    require_once __DIR__ . "/../controllers/AppointmentController.php";
    
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $appointment_id = $_POST['appointment_id']??'';
        $appointment_date = $_POST['appointment_date']??'';
        $appointment_time = $_POST['appointment_time']??'';
        $appointment_notes = $_POST['appointment_notes']??'';
        $appointment_status = $_POST['status']??'';

        if($_POST['security_token']=== $_SESSION['csrf_token']){
        $editAppointmentPush = new AppointmentController($appointment_date, $appointment_time, $appointment_notes, $appointment_status);
        $results = $editAppointmentPush->update($appointment_id);
        header("Location: /appointment-system/appointments/list.php");
    }else
        echo("Invalid request");
    }

    

    if($_SERVER["REQUEST_METHOD"]==="GET"){
        $appointment_id = $_GET['appointment_id']??'';

        $editAppointmentPull = new AppointmentController();
        $appointment = $editAppointmentPull->findById($appointment_id);
        require __DIR__ . "/../views/appointments/edit.php";
    }
?>
