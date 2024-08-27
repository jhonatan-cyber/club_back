<?php

use Bramus\Router\Router;
use app\controllers\rol;
use app\controllers\usuario;


$rol = new rol();
$usuario = new usuario();
$router = new Router();

/******************** Roles ********************/
$router->get('getRoles', [$rol, 'getRoles']);
$router->post('createRol', [$rol, 'createRol']);
$router->get('getRol/(\d+)', [$rol, 'getRol']);
$router->get('deleteRol/(\d+)', [$rol, 'deleteRol']);

/******************** Roles ********************/
$router->get('getUsuarios', [$usuario, 'getUsuarios']);
$router->post('createUsuario', [$usuario, 'createUsuario']);
$router->get('getUsuario/(\d+)', [$usuario, 'getUsuario']);
$router->get('deleteUsuario/(\d+)', [$usuario, 'deleteUsuario']);









$router->run();
