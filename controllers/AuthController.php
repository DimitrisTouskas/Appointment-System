<?php 
    require_once __DIR__ . "/../app/core/Database.php";
    require_once __DIR__ . "/../models/User.php";
    require_once __DIR__ . "/../app/core/Controller.php";
    require_once __DIR__ . "/../app/core/Auth.php";
    
    
    class AuthController extends Controller{
        private ?string  $username=NULL;
        private ?string $first_name=NULL;
        private ?string $last_name=NULL;
        private string  $email;
        private string  $password;
        private ?string  $password2=NULL;
        
        public function __construct(
            string $email,
            string $password,
            ?string $first_name=NULL,
            ?string $last_name=NULL,
            ?string $username = NULL,
            ?string $password2 =NULL,
        )
        {
            $this->email = trim(strtolower($email));
            $this->first_name = $first_name!==null ? trim($first_name):null;
            $this->last_name = $last_name!==null ? trim($last_name):null;
            $this->password = trim($password);
            $this->username = $username!==null ? trim($username):null;
            $this->password2 = $password2!==null ? trim($password2):null;
        }

        public function register()
{   
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

    if(!$this->validPasswordLenght()){
        return "Too few characters on password need to add more";
    }

    // 2. BUSINESS RULES (Model check)
    $db = new Database;
    $connection = $db->connect();
    $user = new User($connection);


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
        $this->first_name,
        $this->last_name
    );

    // 5. RESPONSE
    if ($result) {
        return ["status" => "success",
                "message" => "User registered successfully"];
    }

    return ["status" => "error",
                "message" => "Something went wrong try again"];
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
        || empty($this->first_name)
        || empty($this->last_name);
}

private function validEmail(): bool
{
    return filter_var($this->email, FILTER_VALIDATE_EMAIL);
}

private function validPasswordLenght(): bool
{   if(mb_strlen($this->password)> 7 ){
    return true;
}else{
    return false;
}
}
private function isAccountLocked($foundUser):bool{
    return $foundUser['locked_until'] !== null && strtotime($foundUser['locked_until']) > time();
}

public function loginCheck()
{       
    if (Auth::isLoggedIn()){
        $this->redirect("/appointment-system/appointments/list.php");
    }
}

public function login()
{  
    if ($this->emptyInputLogin()) {
        return "All fields are required";
    }
    
    if (!$this->validEmail()) {
        return "Invalid email format";
    }
    $db = new Database;
    $connection = $db->connect();
    $user = new User($connection);
    $foundUser = $user -> findByEmail($this-> email);
    
    if($foundUser!== Null){
        $passCheck = password_verify($this->password , $foundUser['password']);
    }else {
        echo "User not found. Maybe wanna create account";
        exit();
    }

    if($this->isAccountLocked($foundUser)){
        echo "Your account is locked. Try again later.";
        exit();
    }

    if ($passCheck===true){
        $_SESSION["User_id"] = $foundUser['id'];
        $user->resetFailedAttempts($foundUser['id']);
        $this->redirect("/appointment-system/appointments/list.php");
    }else{
        echo "Wrong credentials try again";
        $user->incrementFailedAttempts($foundUser['id']);
        if($foundUser['failed_login_attempts'] + 1 >= 5){
            $user->lockAccount($foundUser['id'], 15);
        }
    }
}
}