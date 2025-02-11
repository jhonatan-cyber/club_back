<?php
date_default_timezone_set('America/La_Paz');
error_reporting(E_ALL);
ini_set('ignore_repeated_errors', true);
ini_set('display_errors', false);
ini_set('log_errors', true);
ini_set('error_log', 'debug.log');

// Configuración de CORS para permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH");
header("Access-Control-Allow-Headers: Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 600");

// Manejo de solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

// Carga de dependencias y configuración
require_once('vendor/autoload.php');
require_once('app/config/config.php');

// Middleware de límite de tasa
$rateLimitMiddleware = new app\config\rateLimitMiddleware();
$rateLimitMiddleware->handle();

// Carga de rutas
require_once('app/routes/routes.php');