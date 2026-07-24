<?php
use App\Controllers\AppointmentController;
use App\Core\DatabaseException;
use App\Core\Response;

    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $appointment_id = $_POST['appointment_id']??'';
        

if($_POST['security_token']!== ($_SESSION['csrf_token']??'')){
        $result = ["status" => "error",
                "message" => "Invalid request",
                "code"=> 403
                ];
        Response::sendJsonResponse($result);
        } 
        $deleteAppointment = new AppointmentController();
        try{            
        $result = $deleteAppointment->delete($appointment_id);
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

        $deleteAppointment = new AppointmentController();
        $appointment = $deleteAppointment->findById($appointment_id);
        if($appointment!= NULL){
        require __DIR__ . "/../views/appointments/delete.php";
        }else{
        header("Location: " . BASE_URL . "/appointments");  
        exit();
        }
    }
?>
