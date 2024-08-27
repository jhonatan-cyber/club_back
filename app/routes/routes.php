<?php

use Bramus\Router\Router;
use app\controllers\rol;


$rol = new rol();
$router = new Router();

$router->get('getRoles', [$rol, 'getRoles']);
$router->post('createRol', [$rol, 'createRol']);
$router->get('getRol/(\d+)', [$rol, 'getRol']);
$router->get('deleteRol/(\d+)', [$rol, 'deleteRol']);










$router->run();
