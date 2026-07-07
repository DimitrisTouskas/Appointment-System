<?php require __DIR__ . '/../layout/header.php'; ?>
<main>
    <form name="loginForm" id="loginForm" action="/appointment-system/public/login" method="POST">
        <h1> Login </h1>
        <div>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email">
        </div>
         <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
        </div>
        <button type="submit">Login</button>
        <footer> Didnt have an account? <a href="/appointment-system/public/register">Register Here</a></footer>
        <input type="hidden" id="security_token" name="security_token" value="<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
        echo $_SESSION['csrf_token']; ?>" />
    </form>
</main>
<script src="/appointment-system/public/assets/js/login.js"></script>
<?php require __DIR__ . '/../layout/footer.php'; ?>