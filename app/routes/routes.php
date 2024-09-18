<?php

use app\controllers\categoria;
use app\controllers\rol;
use app\controllers\home;
use Bramus\Router\Router;
use app\controllers\login;
use app\controllers\cliente;
use app\controllers\usuario;


$rol = new rol();
$usuario = new usuario();
$login = new login();
$cliente = new cliente();
$home = new home();
$categoria = new categoria() ;

$router = new Router();

/******************** Login ********************/
$router->get('/', [$login, 'index']);
$router->post('login', [$login, 'login']);
$router->get('logout', [$login, 'logout']);
$router->post('updateCodigo', [$login, 'updateCodigo']);
$router->post('createCodigo', [$login, 'createCodigo']);
$router->get('validarCodigo/(.+)', [$login, 'validarCodigo']);
$router->post('createAsistencia', [$login, 'createAsistencia']);

/******************** Home ********************/
$router->get('home', [$home, 'index']);
$router->get( 'getCodigo', [$home, 'getCodigo']);

/******************** Roles ********************/
$router->get('roles', [$rol, 'index']);
$router->get('getRoles', [$rol, 'getRoles']);
$router->post('createRol', [$rol, 'createRol']);
$router->get('getRol/(\d+)', [$rol, 'getRol']);
$router->get('deleteRol/(\d+)', [$rol, 'deleteRol']);

/******************** Usuarios ********************/
$router->get('usuarios', [$usuario, 'index']);
$router->get('getUsuarios', [$usuario, 'getUsuarios']);
$router->post('createUsuario', [$usuario, 'createUsuario']);
$router->get('getUsuario/(\d+)', [$usuario, 'getUsuario']);
$router->get('deleteUsuario/(\d+)', [$usuario, 'deleteUsuario']);

/******************** Clientes ********************/
$router->get('clientes', [$cliente, 'index']);
$router->get('getClientes', [$cliente, 'getClientes']);
$router->post('createCliente', [$cliente, 'createCliente']);
$router->get('getCliente/(\d+)', [$cliente, 'getCliente']);
$router->get('deleteCliente/(\d+)', [$cliente, 'deleteCliente']);


/******************** Categorias ********************/
$router->get('categorias', [$categoria, 'index']);
$router->get('getCategorias', [$categoria, 'getCategorias']);
$router->post('createCategoria', [$categoria, 'createCategoria']);
$router->get('getCategoria/(\d+)', [$categoria, 'getCategoria']);
$router->get('deleteCategoria/(\d+)', [$categoria, 'deleteCategoria']);






$router->run();
