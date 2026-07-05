<?php 
namespace App\Core;

class Model {


    public \mysqli $conn;

    public function __construct(\mysqli $conn)
    {
        $this->conn=$conn;
    }
}



?>