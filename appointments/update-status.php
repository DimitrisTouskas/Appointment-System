<?php
use App\Controllers\AppointmentController;
use App\Core\DatabaseException;

if ($_SERVER["REQUEST_METHOD"]==="POST"){
        $status = $_POST["status"]??'';
        $appointment_id = $_POST["appointment_id"]??'';
    
if($_POST['security_token']=== $_SESSION['csrf_token']){
        $statusAppointment = new AppointmentController();
        try{
        $result = $statusAppointment->updateStatus($appointment_id , $status);
        }catch(DatabaseException $e){
            $result = [
                "status"=> "error",
                "message" => $e->getMessage(),
                "code" => $e->getCode()
            ];
        }
    
        unset($_SESSION['csrf_token']);
        }else{
            $result = ["status" => "error",
            "message" => "Invalid request",
            "code"=> 403
            ];
        }
        header('Content-Type: application/json');
            http_response_code($result['code']);
            echo json_encode($result);
}
?>