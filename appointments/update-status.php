<?php
use App\Controllers\AppointmentController;
use App\Core\DatabaseException;
use App\Core\Response;

if ($_SERVER["REQUEST_METHOD"]==="POST"){
        $status = $_POST["status"]??'';
        $appointment_id = $_POST["appointment_id"]??'';
    
if($_POST['security_token']!== $_SESSION['csrf_token']){ 
    $result = ["status" => "error",
       "message" => "Invalid request",
        "code"=> 403
        ];
    Response::sendJsonResponse($result);
} 
    $statusAppointment = new AppointmentController();
    try{            
        $result = $statusAppointment->updateStatus($appointment_id , $status);
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


