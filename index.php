<?php
date_default_timezone_set('America/La_Paz');
error_reporting(E_ALL);
ini_set('ignore_repeated_errors', true);
ini_set('display_errors', false);
ini_set('log_errors', true);
ini_set('error_log', 'debug.log');

// Configuración estricta de CORS
$allowedOrigins = [
    'http://localhost',
    'http://127.0.0.1',
    'http://192.168.1.100'
];

$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $origin);
} /* else {
    header("HTTP/1.1 403 Forbidden");
    exit('Origen no permitido');
} */

// Configuración adicional de CORS
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH");
header("Access-Control-Allow-Headers: Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600"); // Cache preflight por 1 hora

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

require_once('vendor/autoload.php');
require_once('app/config/config.php');

// Aplicar rate limiting antes de procesar las rutas
$rateLimitMiddleware = new app\config\rateLimitMiddleware();
$rateLimitMiddleware->handle();

require_once('app/routes/routes.php');
