<?php
date_default_timezone_set('America/La_Paz');
error_reporting(E_ALL);
ini_set('ignore_repeated_errors', true);
ini_set('display_errors', false);
ini_set('log_errors', true);
ini_set('error_log', 'debug.log');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH");
header('Access-Control-Allow-Credentials: true');

require_once('vendor/autoload.php');
require_once('app/config/config.php');
require_once('app/routes/routes.php');
