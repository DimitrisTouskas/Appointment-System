<?php
    use App\Controllers\AuthController;
    use App\Core\DatabaseException;

    if($_SERVER["REQUEST_METHOD"]==="GET"){
        if (isset($_SESSION['User_id'])) {
            header("Location: " . BASE_URL . "/appointments");
            exit();
        }
        require __DIR__ . "/../views/auth/login.php";
    }

    if($_SERVER["REQUEST_METHOD"]=== "POST"){
        $email = $_POST['email']??'';
        $password = $_POST['password']??'';
        $username = $_POST['username']??'';
        $password2 = $_POST['password2']??'';

        $auth = new AuthController (email:$email , password:$password , password2:$password2);
        if($_POST['security_token']=== $_SESSION['csrf_token']){    
        try{
        $result = $auth->login();
        }catch (DatabaseException $e){
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