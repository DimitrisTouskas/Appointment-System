<?php require __DIR__ . '/../layout/header.php'; ?>
<main class="d-flex align-items-center justify-content-center" style="min-height: 80vh">
    <div class="card mx-auto" style="max-width: 420px">
        <div class="card-body">
            <form name="registerForm" id="registerForm" action="<?= BASE_URL ?>/register" method="POST">
                <h5> Sign Up </h5>
                <div class="mb-3">
                    <label class="form-label" for="username">Username:</label>
                    <input class="form-control" type="text" name="username" id="username">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email:</label>
                    <input class="form-control" type="text" name="email" id="email">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="firstname">Firstame:</label>
                    <input class="form-control" type="text" name="firstname" id="firstname">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="lastname">Lastname:</label>
                    <input class="form-control" type="text" name="lastname" id="lastname">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password:</label>
                    <input class="form-control" type="password" name="password" id="password">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password2">Password again:</label>
                    <input class="form-control" type="password" name="password2" id="password2">
                </div>
                <div class="mb-3 form-check">
                        <label class="form-check-label" for="agree">
                        <input  class="form-check-input" type="checkbox" name="agree" id="agree" value="yes"/> I agree with the 
                        <a href="#" title="term of services">term of services</a>
                    </label>
                </div>
                <button class="btn btn-primary" type="submit">Register</button>
                <footer> Already member? <a href="<?= BASE_URL ?>/login">Login Here</a></footer>
                <input type="hidden" id="security_token" name="security_token" value="<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
                    echo $_SESSION['csrf_token']; ?>" />
            </form>
        </div>
    </div>
</main>
<script src="<?= BASE_URL ?>/assets/js/register.js"></script>
<?php require __DIR__ . '/../layout/footer.php'; ?>