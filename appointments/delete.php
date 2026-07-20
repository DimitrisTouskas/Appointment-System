<?php
    use App\Controllers\AppointmentController;
    use App\Core\DatabaseException;

    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $appointment_id = $_POST['appointment_id']??'';
        

    if($_POST['security_token']=== $_SESSION['csrf_token']){
        $deleteAppointment = new AppointmentController();
        try{
        $results = $deleteAppointment->delete($appointment_id);
        }catch(DatabaseException $e){
             $results = [
                "status"=> "error",
                "message" => $e->getMessage(),
                "code" => $e->getCode()
            ];
        }
    }else{
        $results = ["status" => "error",
            "message" => "Invalid request",
            "code"=> 403
            ];  
        }
        header('Content-Type: application/json');
        http_response_code($results['code']);
        echo json_encode($results);
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