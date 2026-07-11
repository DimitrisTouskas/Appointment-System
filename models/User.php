<?php
    namespace App\Models;
    use App\Core\DatabaseException;
    use App\Core\Model;

    class User extends Model {

        public function createUser($username, $email, $password , $first_name , $last_name) {
            $sql = "INSERT INTO users(username, email, password , first_name , last_name) VALUES (?, ?, ?, ?, ? )";
            $stmt = $this->conn->prepare($sql);

            
            if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);  
            }

            $stmt->bind_param("sssss", $username, $email, $password , $first_name , $last_name);

        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);  
}

    return true;
        }

        public function usernameExists(string $username){
            $sql = "SELECT id FROM users WHERE username=?";
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);  
            }
            $stmt->bind_param("s" , $username);
            $stmt->execute();
            $stmt->store_result();
            
            return $stmt->num_rows > 0;
        }
        
        public function emailExists(string $email){
            $sql = "SELECT id FROM users WHERE email=?";
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);  
            }

            $stmt->bind_param("s" , $email);
            $stmt->execute();
            $stmt->store_result();
            
            return $stmt->num_rows > 0;
        
    }

        public function  findByEmail(string $email){
            $sql = "SELECT * FROM users WHERE email=?";
            $stmt = $this->conn-> prepare($sql);

           if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);  
            }

            $stmt -> bind_param('s' , $email);
            $stmt ->execute();
            $result = $stmt->get_result();
            $assoc = $result->fetch_assoc();

            return $assoc;

        }

        public function incrementFailedAttempts($userId){
            $sql = "UPDATE users SET failed_login_attempts = failed_login_attempts + 1 WHERE id= ?";
            $stmt = $this->conn -> prepare($sql);

            if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);  
            }

            $stmt -> bind_param('i' , $userId);
            $stmt ->execute();
            return true;
        }

        public function lockAccount($userId, $minutes){
            $sql = "UPDATE users SET locked_until = DATE_ADD(NOW() , INTERVAL ? MINUTE) WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);  
            }

            $stmt->bind_param('ii' , $minutes , $userId);
            $stmt -> execute();
            return true;
        }
        public function resetFailedAttempts($userId){
            $sql = "UPDATE users SET failed_login_attempts = 0 , locked_until = NULL WHERE ID=?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);  
            }

            $stmt->bind_param('i' , $userId);
            $stmt ->execute();
            return true;
        }
    }

?>