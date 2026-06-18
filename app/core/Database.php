<?php
    class Database{
        private $host = "localhost";
        private $dbname = 'schemaDB';
        private $username = "root";
        private $password = "root";

        public mysqli $conn;

        public function connect(){
            $this->conn = new mysqli(
                $this-> host,
                $this-> username,
                $this->password,
                $this->dbname
            );

            if ($this->conn->connect_error){
                die("Connection failed: " . $this->conn->connect_error);
            }
            return $this->conn;
        }
    }