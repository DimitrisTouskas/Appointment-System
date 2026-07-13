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
            $this->redirect("/appointment-system/public/login");
        }
    }

    public function create(){

        $this->sessionCheck();

        if ($this->emptyInputDate()) {
            return ["status" => "error",
            "message" => "Date field are required!",
            "code"=> 400
            ];
        }
        
        if ($this->emptyInputTime()) {
            return ["status" => "error",
            "message" => "Time field are required",
            "code"=> 400
            ];
        }

        // Validations for Date and Time
        if ($this->appointment_date < date('Y-m-d')){
            return ["status" => "error",
            "message" => "Cannot accept previous date",
            "code"=> 400
            ];
        }

        if ($this->appointment_time < date('H:i') && $this->appointment_date == date('Y-m-d')){
            return ["status" => "error",
            "message" => "Cannot set previous time",
            "code"=> 400
            ];
            
        }

        $db = new Database;
        $connection = $db->connect();
        $appointment = new Appointment($connection);
        
        $newAppointment = $appointment -> createAppointment($this->appointment_date ,$this-> appointment_time , $this->appointment_notes , $_SESSION['User_id'] );
        return ["status" => "success",
            "message" => "New Appointment added successfully",
            "code"=> 200
            ];
        
        }   

    public function index(int $page = 1){
        $this->sessionCheck();
        $db = new Database;
        $connection = $db->connect();
        $list = new Appointment($connection);
        $perPage = 5; 
        $offset =($page - 1) * $perPage;

        $view_list = $list -> viewAppointments($_SESSION['User_id'] , $perPage , $offset );
        $countResult = $list->countAppointments($_SESSION['User_id']);
        $totalCount = $countResult['TOTAL'];
        $totalPages = ceil($totalCount/$perPage);
        return ['appointments' => $view_list, 'currentPage' => $page, 'totalPages' => $totalPages];
    }
    public function delete($appointment_id){
        $this->sessionCheck();

        if($this->emptyInputId($appointment_id))
        {
            return ["status" => "error",
            "message" => "Cannot delete this appointment",
            "code"=> 400
            ];
            
        }
        $db = new Database;
        $connection = $db->connect();
        $appointment = new Appointment($connection);
        
        $appointment->delete($appointment_id , $_SESSION['User_id']);
            return ["status" => "success",
            "message" => "Appointment Deleted Successfully",
            "code"=> 200
            ];    
    }
    private function emptyInputId($appointment_id): bool
        {
            return empty($appointment_id);

    }
    public function update($appointment_id ){
        $this->sessionCheck();
        if ($this->emptyInputDate()) {
            return ["status" => "error",
            "message" => "Date field are required",
            "code"=> 400
            ];
            
        }
        
        if ($this->emptyInputTime()) {
            return ["status" => "error",
            "message" => "Time field are required",
            "code"=> 400
            ];
        }

        // Validations for Date and Time
        if ($this->appointment_date < date('Y-m-d')){
            return ["status" => "error",
            "message" => "Cannot accept previous date",
            "code"=> 400
            ];
        }

        if ($this->appointment_time < date('H:i') && $this->appointment_date == date('Y-m-d')){
            return ["status" => "error",
            "message" => "Cannot set previous time",
            "code"=> 400
            ];
        }

        $db = new Database;
        $connection = $db->connect();
        $appointmentEdit = new Appointment($connection);
        
        $editAppointment = $appointmentEdit -> updateAppointment($this->appointment_date , $this->appointment_time  ,$this->appointment_notes , $this->appointment_status , $appointment_id , $_SESSION['User_id']);
        return ["status" => "success",
            "message" => "Appointment edited successfully",
            "code"=> 200
            ];
    }


    public function findById($appointment_id){
        $this->sessionCheck();
        
        $db = new Database;
        $connection = $db->connect();
        $fetchData = new Appointment($connection);

        $getData = $fetchData -> findById($appointment_id , $_SESSION['User_id']);
        return $getData;
    }

    public function updateStatus($appointment_id , $status){
        $this->sessionCheck();

        if(!in_array($status , ['cancelled' , 'pending' , 'confirmed' , 'completed'] )){
            return ["status" => "error",
            "message" => "Choose a status from the options",
            "code"=> 400
            ];
        }else{
            $db = new Database;
        $connection = $db->connect();
        $results = new Appointment($connection);
        $results -> updateStatus($appointment_id , $status , $_SESSION['User_id']);
        return ["status" => "success",
            "message" => "Status updated successfully",
            "code"=> 200
            ];  
        }
    }

}