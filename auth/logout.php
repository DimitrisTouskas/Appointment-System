<?php
    session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);
    session_destroy();
    header("Location: ../views/auth/login.php");
    exit();

?>