<?php
    session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);
    require_once __DIR__ . "/../controllers/AuthController.php";
    if (isset($_SESSION['User_id'])) {
    header("Location: /appointment-system/appointments/list.php");
    exit();
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
        $result = $auth->register();
        
        echo json_encode($result);
        unset($_SESSION['csrf_token']);
        }else
        echo("Invalid request");
    }
    
?>
