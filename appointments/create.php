<?php
    use App\Controllers\AppointmentController;
    use App\Core\Auth;
    use App\Core\DatabaseException;
    use App\Core\Response;

     if ($_SERVER["REQUEST_METHOD"]==="GET"){
        if(Auth::isLoggedIn()){
            require __DIR__ . "/../views/appointments/create.php";
        }else{
            header("Location: " . BASE_URL . "/login");
            exit();
     }
     }

    if ($_SERVER["REQUEST_METHOD"]==="POST"){
        $appointment_date = $_POST["appointment_date"]??'';
        $appointment_time = $_POST["appointment_time"]??'';
        $appointment_notes = $_POST["appointment_notes"]??'';
        
       if($_POST['security_token']!== ($_SESSION['csrf_token']??'')){
        $result = ["status" => "error",
                "message" => "Invalid request",
                "code"=> 403
                ];
        Response::sendJsonResponse($result);
        } 
        $createAppointment = new AppointmentController($appointment_date , $appointment_time , $appointment_notes);
        try{            
        $result = $createAppointment->create();
        unset($_SESSION['csrf_token']);   
        Response::sendJsonResponse($result);
        
        }catch (DatabaseException $e){
            $result = [
                "status"=> "error",
                "message" => $e->getMessage(),
                "code" => $e->getCode()
                
            ];
            unset($_SESSION['csrf_token']);   
            Response::sendJsonResponse($result);
        }
    }
?>
