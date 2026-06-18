<?php
    session_start();
    require_once __DIR__ . "/../controllers/AuthController.php";
    if($_SERVER["REQUEST_METHOD"]=== "POST"){
        $email = $_POST['email']??'';
        $password = $_POST['password']??'';
        $username = $_POST['username']??'';
        $password2 = $_POST['password2']??'';

        var_dump($_POST);
        $auth = new AuthController ($email , $password);
        
        $result = $auth->login();

        echo json_encode($result);
    }
    
?>