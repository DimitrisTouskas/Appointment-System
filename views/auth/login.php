<?php require __DIR__ . '/../layout/header.php'; ?>
<main class="d-flex align-items-center justify-content-center" style="min-height: 80vh">
    <div class="card mx-auto" style="max-width: 420px">
        <div class="card-body" >
            <form name="loginForm" id="loginForm" action="/appointment-system/public/login" method="POST">
                <h5> Login </h5>
                <div class="mb-3">
                    <label class="form-label" for="email">Email:</label>
                    <input class="form-control" type="text" name="email" id="email">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password:</label>
                    <input class="form-control" type="password" name="password" id="password">
                </div>
                <button class="btn btn-primary" type="submit">Login</button>
                <footer> Didnt have an account? <a href="/appointment-system/public/register">Register Here</a></footer>
                <input type="hidden" id="security_token" name="security_token" value="<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
                echo $_SESSION['csrf_token']; ?>" />
            </form>
        </div>
    </div>
</main>
<script src="/appointment-system/public/assets/js/login.js"></script>
<?php require __DIR__ . '/../layout/footer.php'; ?>