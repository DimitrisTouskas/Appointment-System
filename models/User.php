<?php
    require_once "../app/core/Database.php";
    require_once "../app/core/Model.php";

    class User extends Model {

        public function createUser($username, $email, $password , $first_name , $last_name) {

            $sql = "INSERT INTO users(username, email, password , first_name , last_name) VALUES (?, ?, ?, ?, ? )";

            $stmt = $this->conn->prepare($sql);

            
            if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
            }

            $stmt->bind_param("sssss", $username, $email, $password , $first_name , $last_name);

        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return false;
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