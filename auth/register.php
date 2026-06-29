<?php
    session_start();
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

        var_dump($_POST);
        $auth = new AuthController($email , $firstname , $lastname ,$password , $username ,  $password2);
    
    if($_POST['security_token']=== $_SESSION['csrf_token']){
        $createSession = new AuthController($email , $firstname , $lastname ,$password , $username ,  $password2);
        $result = $createSession->register();    
        $result = $auth->register();
        
        echo json_encode($result);
        unset($_SESSION['csrf_token']);
        }else
        echo("Invalid request");
    }
    
?>
