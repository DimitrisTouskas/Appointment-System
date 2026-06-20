<?php
    require_once "../models/Appointment.php";

    class AppointmentController {
        private ?string $appointment_date = NULL;
        private ?string $appointment_time = NULL;
        private ?string $appointment_notes = NULL;
    
        public function __construct(
            ?string $appointment_date = NULL , 
            ?string $appointment_time = NULL,
            ?string $appointment_notes = NULL
        )
    {
        $this->appointment_date = $appointment_date;
        $this->appointment_time = $appointment_time;
        $this->appointment_notes = $appointment_notes;

    }
    private function emptyInputDate(): bool
        {
            return empty($this->appointment_date);

    }

    private function emptyInputTime(): bool
        {
            return empty($this->appointment_time);

    }

    public function create(){

     if ($this->emptyInputDate()) {
        return "Date field are required";
    }
    
    if ($this->emptyInputTime()) {
        return "Time field are required";
    }

    // Validations for Date and Time
    if ($this->appointment_date < date('Y-m-d')){
        return "Cannot accept previous date";
    }

    if ($this->appointment_time < date('H:i') && $this->appointment_date == date('Y-m-d')){
        return "Cannot set previous time";
    }

    $appointment = new Appointment();
    
    $newAppointment = $appointment -> createAppointment($this->appointment_date ,$this-> appointment_time , $this->appointment_notes , $_SESSION['User_id'] );
    return true;
}

public function index(){
    $list = new Appointment;
    $view_list = $list -> viewAppointments($_SESSION['User_id']);
    return $view_list;
}
public function delete($appointment_id){

    if($this->emptyInputId($appointment_id))
    {
        return "Cannot delete this appointment";
    }
    $appointment = new Appointment();
    $appointment->delete($appointment_id);
    return true;
    
}
 private function emptyInputId($appointment_id): bool
        {
            return empty($appointment_id);

    }
}