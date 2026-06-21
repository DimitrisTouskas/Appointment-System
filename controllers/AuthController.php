<?php 
        
    require_once "../models/User.php";
    class AuthController{
        private ?string  $username=NULL;
        private ?string $firstname=NULL;
        private ?string $lastname=NULL;
        private string  $email;
        private string  $password;
        private ?string  $password2=NULL;
        
        public function __construct(
            string $email,
            string $password,
            ?string $firstname=NULL,
            ?string $lastname=NULL,
            ?string $username = NULL,
            ?string $password2 =NULL,
        )
        {
            $this->email = trim(strtolower($email));
            $this->firstname = $firstname!==null ? trim($firstname):null;
            $this->lastname = $lastname!==null ? trim($lastname):null;
            $this->password = trim($password);
            $this->username = $username!==null ? trim($username):null;
            $this->password2 = $password2!==null ? trim($password2):null;
        }

        public function register()
{   
    $this->loginCheck();
    
    // 1. VALIDATION
    if ($this->emptyInputRegister()) {
        return "All fields are required";
    }

    if (!$this->validEmail()) {
        return "Invalid email format";
    }

    if ($this->password !== $this->password2) {
        return "Passwords do not match";
    }

    // 2. BUSINESS RULES (Model check)
    $user = new User();

    if ($user->emailExists($this->email)) {
        return [
            "status"=>"error",
            "message"=>"Email already exists"
        ];
    }

    if ($user->usernameExists($this->username)) {
        return [
            "status"=>"error",
            "message"=>"Username already exists"
        ];
    }

    // 3. SECURITY
    $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

    // 4. CREATE USER (MODEL)
    $result = $user->createUser(
        $this->username,
        $this->email,
        $hashedPassword,
        $this->firstname,
        $this->lastname
    );

    // 5. RESPONSE
    if ($result) {
        return ["status" => "success",
                "message" => "User registered successfully"];
    }

    return ["status" => "success",
                "message" => "Somethink went wrong"];
}

private function emptyInputLogin(): bool
{
    return empty($this->email)
        || empty($this->password);
}

private function emptyInputRegister(): bool
{
    return empty($this->username)
        || empty($this->email)
        || empty($this->password)
        || empty($this->password2)
        || empty($this->firstname)
        || empty($this->lastname);
}

private function validEmail(): bool
{
    return filter_var($this->email, FILTER_VALIDATE_EMAIL);
}

private function loginCheck()
{
    if (isset($_SESSION["User_id"])){
        header("Location: /appointment-system/appointments/list.php");
        exit();
    }
}

public function login()
{  
    $this->loginCheck();

    if ($this->emptyInputLogin()) {
        return "All fields are required";
    }
    if (!$this->validEmail()) {
        return "Invalid email format";
    }
    $user = new User();
    $foundUser = $user -> findByEmail($this-> email);

    if($foundUser!== Null){
        $passCheck = password_verify($this->password , $foundUser['password']);
    }else {
        echo "User not found. Maybe wanna create account";
        exit();
    }
    if ($passCheck===true){
        $_SESSION["User_id"] = $foundUser['id'];
        header("Location: localhost:8888/appointment-system/appointments/list.php");
        exit();
    }else{
        echo "Wrong credentials try again";
    }
}

}