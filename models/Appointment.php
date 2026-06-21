<?php
    require_once "../app/core/Database.php";

    class Appointment {
        
        public mysqli $conn;

        public function __construct(){
            $db = new Database();
            $this->conn = $db->connect();

        }

        public function createAppointment($appointment_date , $appointment_time , $appointment_note , $user_id) {

            $sql = "INSERT INTO Appointments(appointment_date , appointment_time , notes , user_id) VALUES (?, ?, ? , ?)";

            $stmt = $this->conn->prepare($sql);

            
            if (!$stmt) {
                die("Prepare failed: " . $this->conn->error);
            }

            $stmt->bind_param("sssi", $appointment_date , $appointment_time , $appointment_note , $user_id);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }
            return true;
        }


        public function viewAppointments($user_id){
            $sql = "SELECT id , appointment_date , appointment_time , status , notes , created_at FROM Appointments WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            
            if(!$stmt){
                die("Prepare fail: " . $this->conn->error);
            }
            $stmt -> bind_param('i' , $user_id);
            $stmt ->execute();
            $result = $stmt->get_result();
            $assoc = $result->fetch_all(MYSQLI_ASSOC);
            return $assoc;
        }
        public function delete($appointment_id){
            $sql = "DELETE FROM appointments WHERE id= ?";
            $stmt = $this->conn->prepare($sql);

            if(!$stmt){
                die("Prepare fail:" . $this->conn->error);
            }
            $stmt -> bind_param("i", $appointment_id);
            $stmt -> execute();
            return true;
        } 

        public function findById($appointment_id){
            $sql = "SELECT id , appointment_date , appointment_time , status , notes , created_at FROM Appointments WHERE id=?";
            $stmt = $this->conn->prepare($sql);

            if(!$stmt){
                die("Prepare fail:" . $this->conn->error);
            }
            $stmt -> bind_param('i', $appointment_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $assoc = $result->fetch_assoc();
            return $assoc;
        }

        public function updateAppointment($appointment_date , $appointment_time  , $appointment_notes , $appointment_status , $appointment_id  ){
            $sql = "UPDATE Appointments SET appointment_date=? , appointment_time=? , status=? , notes=? WHERE id=? ";
            $stmt = $this->conn ->prepare($sql);
            
            if (!$stmt) {
                die("Prepare failed: " . $this->conn->error);
            }

            $stmt->bind_param("ssssi", $appointment_date , $appointment_time , $appointment_status ,$appointment_notes , $appointment_id);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }
            return true;
        }

    }
