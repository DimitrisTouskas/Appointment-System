<?php
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
    return [
        'api_key' => $_ENV['NEWS_API_KEY']
     ];
