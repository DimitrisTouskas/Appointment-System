<?php
    require_once "../app/core/Database.php";

    class User {
        
        public mysqli $conn;

        public function __construct(){
            $db = new Database();
            $this->conn = $db->connect();

        }

        public function createUser($username, $email, $password , $first_name , $last_name) {

            $sql = "INSERT INTO users(username, email, password , first_name , last_name) VALUES (?, ?, ?, ?, ? )";

            $stmt = $this->conn->prepare($sql);

            
            if (!$stmt) {
                die("Prepare failed: " . $this->conn->error);
            }

            $stmt->bind_param("sssss", $username, $email, $password , $first_name , $last_name);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

    return true;
        }
        public function usernameExists(string $username){
            $sql = "SELECT id FROM users WHERE username=?";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bind_param("s" , $username);
            $stmt->execute();
            $stmt->store_result();
            
            return $stmt->num_rows > 0;
        }
        public function emailExists(string $email){
            $sql = "SELECT id FROM users WHERE email=?";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bind_param("s" , $email);
            $stmt->execute();
            $stmt->store_result();
            
            return $stmt->num_rows > 0;
        
    }

        public function  findByEmail(string $email){
            $sql = "SELECT * FROM users WHERE email=?";
            $stmt = $this->conn-> prepare($sql);
           
            $stmt -> bind_param('s' , $email);
            $stmt ->execute();
            $result = $stmt->get_result();
            $assoc = $result->fetch_assoc();

            return $assoc;

        }

    }

?>