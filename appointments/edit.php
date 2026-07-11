<?php
    use App\Controllers\AppointmentController;
    use App\Core\DatabaseException;
    

    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $appointment_id = $_POST['appointment_id']??'';
        $appointment_date = $_POST['appointment_date']??'';
        $appointment_time = $_POST['appointment_time']??'';
        $appointment_notes = $_POST['appointment_notes']??'';
        $appointment_status = $_POST['status']??'';

        if($_POST['security_token']=== $_SESSION['csrf_token']){
        $editAppointmentPush = new AppointmentController($appointment_date, $appointment_time, $appointment_notes, $appointment_status);
        try{
        $results = $editAppointmentPush->update($appointment_id);
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

        $editAppointmentPull = new AppointmentController();
        $appointment = $editAppointmentPull->findById($appointment_id);
        if($appointment!= NULL){
        require __DIR__ . "/../views/appointments/edit.php";
        }else{
        header("Location: /appointment-system/public/appointments");
        exit();
        }
    }
?>
