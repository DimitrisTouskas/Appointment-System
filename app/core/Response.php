<?php
namespace App\Core;

class Response{
    public static function sendJsonResponse($result){
        header('Content-Type: application/json');
        http_response_code($result['code']);
        echo json_encode($result);
        exit();

    }
}


?>