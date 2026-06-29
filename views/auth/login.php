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
    <form name="registerForm" action="../../auth/login.php" method="POST">
        <h1> Sign Up </h1>
        <div>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email">
        </div>
         <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
        </div>
        <button type="submit">Login</button>
        <footer> Didnt have an account? <a href="register.php">Register Here</a></footer>
    </form>
    <input type="hidden" id="security_token" name="security_token" value="<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
            echo $_SESSION['csrf_token']; ?>" />
</main>
<script src="../../public/assets/js/login.js"></script>
</body>
</html>