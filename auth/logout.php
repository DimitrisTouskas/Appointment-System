<?php
    session_start();
    session_destroy();
    header("Location: /appointment-system/auth/login.php");
    exit();

?>