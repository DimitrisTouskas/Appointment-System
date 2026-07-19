<?php
    use App\Controllers\DashboardController;

    $controller = new DashboardController();
    $result = $controller->index();
    $articles = $result["articles"];
    require __DIR__ . '/views/dashboard.php';

?>