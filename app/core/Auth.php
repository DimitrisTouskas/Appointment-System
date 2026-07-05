<?php 
namespace App\Core;
    class Auth{
        public static function isLoggedIn(): bool {
            return isset($_SESSION["User_id"]);

        }

    }
?>