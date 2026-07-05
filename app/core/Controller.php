<?php 
namespace App\Core;
    class Controller{
        protected function redirect($path){
            header("Location:" . $path);
            exit();
        }
    }
?>