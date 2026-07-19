<?php
namespace App\Controllers;
use App\Core\Controller; 

class DashboardController extends Controller{
    public function index(){
        $config = require __DIR__ . '/../config/news.php';
        $apiUrl = "https://newsapi.org/v2/top-headlines?country=us&apiKey={$config['api_key']}";
        $options = [
        'http' => [
        'header' => "User-Agent: AppointmentSystem/1.0\r\n"
        ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($apiUrl, false, $context);
        $data = json_decode($response, true);
        return $data;
    }


}


?>