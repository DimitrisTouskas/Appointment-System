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
    }
