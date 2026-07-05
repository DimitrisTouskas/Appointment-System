<?php
    namespace App\Controllers;
    use App\Core\Auth;
    use App\Models\Appointment;
     use App\Core\Controller;
    use App\Core\Database;

    class AppointmentController extends Controller {
        private ?string $appointment_date = NULL;
        private ?string $appointment_time = NULL;
        private ?string $appointment_notes = NULL;
        private ?string $appointment_status = NULL;
    
        public function __construct(
            ?string $appointment_date = NULL , 
            ?string $appointment_time = NULL,
            ?string $appointment_notes = NULL,
            ?string $appointment_status = NULL
        )
    {
        $this->appointment_date = $appointment_date;
        $this->appointment_time = $appointment_time;
        $this->appointment_notes = $appointment_notes;
        $this->appointment_status = $appointment_status;
    }
    private function emptyInputDate(): bool
    {
        return empty($this->appointment_date);

    }

    private function emptyInputTime(): bool
    {
        return empty($this->appointment_time);

    }
    private function sessionCheck()
    {
        if (!Auth::isLoggedIn()){
            $this->redirect("appointment-system/views/auth/login.php");
        }
    }

    public function create(){

        $this->sessionCheck();

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

        $db = new Database;
        $connection = $db->connect();
        $appointment = new Appointment($connection);
        
        $newAppointment = $appointment -> createAppointment($this->appointment_date ,$this-> appointment_time , $this->appointment_notes , $_SESSION['User_id'] );
        $this->redirect("/appointment-system/appointments/list.php");
        }   

    public function index(){
        $this->sessionCheck();
        
        $db = new Database;
        $connection = $db->connect();
        $list = new Appointment($connection);

        $view_list = $list -> viewAppointments($_SESSION['User_id']);
        return $view_list;
    }
    public function delete($appointment_id){
        $this->sessionCheck();

        if($this->emptyInputId($appointment_id))
        {
            return "Cannot delete this appointment";
        }
        $db = new Database;
        $connection = $db->connect();
        $appointment = new Appointment($connection);
        
        $appointment->delete($appointment_id , $_SESSION['User_id']);
        return true;
    
    }
    private function emptyInputId($appointment_id): bool
        {
            return empty($appointment_id);

    }
    public function update($appointment_id ){
        $this->sessionCheck();
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

        $db = new Database;
        $connection = $db->connect();
        $appointmentEdit = new Appointment($connection);
        
        $editAppointment = $appointmentEdit -> updateAppointment($this->appointment_date , $this->appointment_time  ,$this->appointment_notes , $this->appointment_status , $appointment_id , $_SESSION['User_id']);
        return true;
    }


    public function findById($appointment_id){
        $this->sessionCheck();
        
        $db = new Database;
        $connection = $db->connect();
        $fetchData = new Appointment($connection);

        $getData = $fetchData -> findById($appointment_id , $_SESSION['User_id']);
        return $getData;
    }

}