<?php
    // include "../appointment-system/config/config.php";
    class Database{

        private array $config;
        public mysqli $conn;

        public function __construct()
        {
            $this->config = require __DIR__ . '/../../config/config.php';
        }

        public function connect(){
            $this->conn = new mysqli(
                $this-> config['host'],
                $this-> config['username'],
                $this->config['password'],
                $this->config['dbname']
            );

            if ($this->conn->connect_error){
                die("Connection failed: " . $this->conn->connect_error);
            }
            return $this->conn;
        }
    }