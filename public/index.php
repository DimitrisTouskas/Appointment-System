<?php 
    require_once __DIR__ . '/../vendor/autoload.php';
    
   
    session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    if ($basePath === '/') {
    $basePath = '';
}
    define('BASE_URL' , $basePath);
    $route = str_replace($basePath, '', $path);

    $routing = [
        '/login' => __DIR__ . '/../auth/login.php',
        '/register' => __DIR__ . '/../auth/register.php',
        '/logout' => __DIR__ . '/../auth/logout.php',
        '/appointments' => __DIR__ . '/../appointments/list.php',
        '/appointments/create' => __DIR__ . '/../appointments/create.php',
        '/appointments/edit' => __DIR__ . '/../appointments/edit.php',
        '/appointments/delete' => __DIR__ . '/../appointments/delete.php',
        '/appointments/update-status' => __DIR__ . '/../appointments/update-status.php',
        '/dashboard' => __DIR__ . '/../dashboard.php'
    ];
    
    if(isset($routing[$route])){
        require $routing[$route];
    }else{
        http_response_code(404); echo "Page not found";
    }

?>