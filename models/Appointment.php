<?php
    namespace App\Models;
    use App\Core\DatabaseException;
    use App\Core\Model;

    class Appointment extends Model{
        

        public function createAppointment($appointment_date , $appointment_time , $appointment_note , $user_id) {

            $sql = "INSERT INTO appointments(appointment_date , appointment_time , notes , user_id) VALUES (?, ?, ? , ?)";

            $stmt = $this->conn->prepare($sql);

            
            if (!$stmt) {
             error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);
}

            $stmt->bind_param("sssi", $appointment_date , $appointment_time , $appointment_note , $user_id);

            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
                throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);

            }
            return true;
        }

         public function countAppointments($user_id , $status = null , $searchTerm = null){
            $conditions =["user_id = ?" ];
            $values = [$user_id];
            $types = "i";

            if($status !== NULL){
                $conditions[] = 'status = ?'; 
                $values[] = $status; 
                $types .= 's';
            }
            
            if ($searchTerm !== NULL ){
                $conditions[] ="notes LIKE ?" ;
                $values[] = "%" . $searchTerm  . "%" ;
                $types .= "s";
            }
            
            $sql = "SELECT COUNT(*) AS TOTAL FROM appointments" 
            ." WHERE " .implode(' AND ' , $conditions);

            $stmt = $this->conn->prepare($sql);

            $stmt -> bind_param($types , ...$values);
            $stmt -> execute();
            $result = $stmt -> get_result();
            $assoc = $result->fetch_assoc();
            return $assoc;
         }



        // μολις τελειωσω το feauture να το ξαναδω παλι ωστε να καταλαβω λιγο καλυτερα πως δουλευει το dyname sql
        public function viewAppointments($user_id , $limit , $offset  , $status = null , $sort = 'asc' , $searchTerm = null ){
            $conditions =["user_id = ?" ];
            $values = [$user_id];
            $types = "i";

            if($status !== NULL){
                $conditions[] = 'status = ?'; 
                $values[] = $status; 
                $types .= 's';
            }
            
            if ($searchTerm !== NULL ){
                $conditions[] ="notes LIKE ?" ;
                $values[] = "%" . $searchTerm  . "%" ;
                $types .= "s";
            }
            
            $direction = ($sort === 'asc') ? 'ASC' : 'DESC';

            

            $sql = "SELECT id , appointment_date , appointment_time , status , notes , created_at FROM appointments "
            ."WHERE ". implode(' AND ' , $conditions)
            .' ORDER BY appointment_date '. $direction
            .'  LIMIT ? OFFSET ? ';
            
            $values[] = $limit; 
            $values[] = $offset;
            $types .= 'ii';

            $stmt = $this->conn->prepare($sql);
            
            if(!$stmt){
            error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);  
            }
            $stmt -> bind_param($types , ...$values);
            $stmt ->execute();
            $result = $stmt->get_result();
            $assoc = $result->fetch_all(MYSQLI_ASSOC);
            return $assoc;
        }
        public function delete($appointment_id , $user_id){
            $sql = "DELETE FROM appointments WHERE id= ? AND user_id=?" ;
            $stmt = $this->conn->prepare($sql);

            if(!$stmt){
            error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);

            }
            $stmt -> bind_param("ii", $appointment_id , $user_id);
            $stmt -> execute();
            return true;
        } 

        public function findById($appointment_id , $user_id){
            $sql = "SELECT id , appointment_date , appointment_time , status , notes , created_at FROM appointments WHERE id=? AND user_id=?" ;
            $stmt = $this->conn->prepare($sql);

            if(!$stmt){
            error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);
            }
            $stmt -> bind_param('ii', $appointment_id , $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $assoc = $result->fetch_assoc();
            return $assoc;
        }

        public function updateAppointment($appointment_date , $appointment_time  , $appointment_notes , $appointment_status , $appointment_id , $user_id  ){
            $sql = "UPDATE appointments SET appointment_date=? , appointment_time=? , status=? , notes=? WHERE id=? AND user_id=?" ;
            $stmt = $this->conn ->prepare($sql);
            
            if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);
            }

            $stmt->bind_param("ssssii", $appointment_date , $appointment_time , $appointment_status ,$appointment_notes , $appointment_id , $user_id);

            if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);
            }
            return true;
        }

        public function updateStatus($appointment_id , $status , $user_id) {
            $sql= "UPDATE appointments SET status=? WHERE id = ? AND user_id = ?";
            $stmt = $this->conn-> prepare($sql);

            if(!$stmt){
                error_log("Prepare failed: " . $this->conn->error);
                throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);
            }
            $stmt->bind_param("sii", $status , $appointment_id , $user_id);

            if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            throw new DatabaseException('Error: ' . $this->conn->connect_error , 500);
            }
            return true;
            
        }

    }
