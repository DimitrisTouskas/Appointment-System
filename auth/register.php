<?php
    use App\Controllers\AuthController;
    use App\Core\DatabaseException;


      if($_SERVER["REQUEST_METHOD"]==="GET"){
        if (isset($_SESSION['User_id'])) {
            header("Location: /appointment-system/public/appointments");
            exit();
        }
        require __DIR__ . "/../views/auth/register.php";
    }

    if($_SERVER["REQUEST_METHOD"]=== "POST"){
        $email = $_POST['email']??'';
        $password = $_POST['password']??'';
        $firstname = $_POST['firstname']??'';
        $lastname = $_POST['lastname']??'';
        $username = $_POST['username']??'';
        $password2 = $_POST['password2']??'';

        $auth = new AuthController(email:$email , first_name:$firstname , last_name:$lastname ,password: $password , username:$username ,  password2:$password2);
    
    if($_POST['security_token']=== $_SESSION['csrf_token']){ 
        
        try{
        $result = $auth->register();
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
