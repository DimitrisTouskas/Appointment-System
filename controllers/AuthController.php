<?php 
    namespace App\Controllers;
    use App\Core\Controller;
    use App\Core\Database;
    use App\Core\Auth;
    use App\Models\User;
    
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
        return [
            "status"=>"error",
            "message"=>"All fields are required",
            "code" => 400
        ];
    }

    if (!$this->validEmail()) {
        return [
            "status"=>"error",
            "message"=>"Invalid email format",
            "code" => 400
        ];
    }

    if ($this->password !== $this->password2) {
        return [
            "status"=>"error",
            "message"=>"Passwords do not match",
            "code" => 400
        ];
    }

    if(!$this->validPasswordLenght()){
        return [
            "status"=>"error",
            "message"=>"Too few characters on password need to add more",
            "code" => 400
        ];
    }

    // 2. BUSINESS RULES (Model check)
    $db = new Database;
    $connection = $db->connect();
    $user = new User($connection);


    if ($user->emailExists($this->email)) {
        return [
            "status"=>"error",
            "message"=>"Email already exists",
            "code" => 409
        ];
    }

    if ($user->usernameExists($this->username)) {
        return [
            "status"=>"error",
            "message"=>"Username already exists",
            "code" => 409
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
                "message" => "User registered successfully",
                "code"=> 200
                ];
    }

    return ["status" => "error", "message" => "Something went wrong try again","code" => 500];
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
        $this->redirect(BASE_URL . "/appointments");
    }
}

public function login()
{  
    if ($this->emptyInputLogin()) {
        return ["status" => "error",
                "message" => "All fields are required",
                "code"=> 400
                ];
    }
    
    if (!$this->validEmail()) {
        return ["status" => "error",
                "message" => "Invalid email format",
                "code"=> 400
                ];
        
    }
    $db = new Database;
    $connection = $db->connect();
    $user = new User($connection);
    $foundUser = $user -> findByEmail($this-> email);
    
    if($foundUser!== Null){
        $passCheck = password_verify($this->password , $foundUser['password']);
    }else {
        return ["status" => "error",
                "message" => "User not found. Maybe wanna create account",
                "code"=> 401
                ];
    }

    if($this->isAccountLocked($foundUser)){
        return ["status" => "error",
                "message" => "Your account is locked. Try again later.",
                "code"=> 403
                ];
    }

    if ($passCheck===true){
        $_SESSION["User_id"] = $foundUser['id'];
        $user->resetFailedAttempts($foundUser['id']);
        return ["status" => "success",
        "message" => "Success Login!",
        "code"=> 200
        ];
    }else {
        $user->incrementFailedAttempts($foundUser['id']);
        if($foundUser['failed_login_attempts'] + 1 >= 5){
            $user->lockAccount($foundUser['id'], 15);
            return ["status" => "error",
                "message" => "Too many tries. Try again later.",
                "code"=> 403
                ];
    
        }else 
            return ["status" => "error",
            "message" => "Wrong Credentials!",
            "code"=> 401
            ];
    }

}
}