<?php
    use App\Controllers\AppointmentController;
    use App\Core\Auth;

     if ($_SERVER["REQUEST_METHOD"]==="GET"){
        if(Auth::isLoggedIn()){
            require __DIR__ . "/../views/appointments/create.php";
        }else{
            header("Location: /appointment-system/public/login");
            exit();
     }
     }

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