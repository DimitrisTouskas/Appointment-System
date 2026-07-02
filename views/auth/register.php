<?php session_start(); 
require_once  "/../../controllers/AuthController.php"; 
$auth = new AuthController(email: '', password: '');
$auth->loginCheck();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://phptutorial.net/app/css/style.css">

    <title>Register</title>
</head>
<body>
<main>
    <form name="registerForm" action="../../auth/register.php" method="POST">
        <h1> Sign Up </h1>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username">
        </div>
         <div>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email">
        </div>
        <div>
            <label for="firstname">Firstame:</label>
            <input type="text" name="firstname" id="firstname">
        </div>
        <div>
            <label for="lastname">Lastname:</label>
            <input type="text" name="lastname" id="lastname">
        </div>
         <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
        </div>
         <div>
            <label for="password2">Password again:</label>
            <input type="password" name="password2" id="password2">
        </div>
         <div>
            <label for="agree">
                <input type="checkbox" name="agree" id="agree" value="yes"/> I agree with the 
                <a href="#" title="term of services">term of services</a>
            </label>
        </div>
        <button type="submit">Register</button>
        <footer> Already member? <a href="login.php">Login Here</a></footer>
        <input type="hidden" id="security_token" name="security_token" value="<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
            echo $_SESSION['csrf_token']; ?>" />
    </form>
</main>
<script src="../../public/assets/js/register.js"></script>
</body>
</html>