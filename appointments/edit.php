<?php
    use App\Controllers\AppointmentController;
    use App\Core\DatabaseException;
    use App\Core\Response;

    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $appointment_id = $_POST['appointment_id']??'';
        $appointment_date = $_POST['appointment_date']??'';
        $appointment_time = $_POST['appointment_time']??'';
        $appointment_notes = $_POST['appointment_notes']??'';
        $appointment_status = $_POST['status']??'';

       if($_POST['security_token']!== ($_SESSION['csrf_token']??'')){
        $result = ["status" => "error",
                "message" => "Invalid request",
                "code"=> 403
                ];
        Response::sendJsonResponse($result);
        } 
        $editAppointmentPush = new AppointmentController($appointment_date, $appointment_time, $appointment_notes, $appointment_status);
        try{            
        $result = $editAppointmentPush->update($appointment_id);
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

    if($_SERVER["REQUEST_METHOD"]==="GET"){
        $appointment_id = $_GET['appointment_id']??'';

        $editAppointmentPull = new AppointmentController();
        $appointment = $editAppointmentPull->findById($appointment_id);
        if($appointment!= NULL){
        require __DIR__ . "/../views/appointments/edit.php";
        }else{
        header("Location: " . BASE_URL . "/appointments");
        exit();
        }
    }
?>
