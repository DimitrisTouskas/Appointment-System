<?php 
    class Controller{
        protected function redirect($path){
            header("Location:" . $path);
            exit();
        }
    }
?>