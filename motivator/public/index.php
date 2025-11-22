<?php

// ДОБАВЬТЕ ЭТУ СТРОКУ ПЕРВОЙ СТРОКОЙ ФАЙЛА
if (!function_exists('mb_split')) {
    if (file_exists('C:/OSPanel/modules/php/PHP_8.4/ext/php_mbstring.dll')) {
        dl('php_mbstring.dll');
    } else {
        die('КРИТИЧЕСКАЯ ОШИБКА: mbstring не найден. Переключитесь на PHP 8.3');
    }
}
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
