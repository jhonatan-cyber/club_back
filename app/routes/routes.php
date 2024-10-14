<?php

use app\controllers\categoria;
use app\controllers\producto;
use app\controllers\rol;
use app\controllers\home;
use Bramus\Router\Router;
use app\controllers\login;
use app\controllers\cliente;
use app\controllers\usuario;
use app\controllers\pedido;
use app\controllers\venta;
use app\controllers\contrato;
use app\controllers\comision;

$rol = new rol();
$usuario = new usuario();
$login = new login();
$cliente = new cliente();
$home = new home();
$categoria = new categoria();
$producto = new producto();
$pedido = new pedido();
$venta = new venta();
$contrato = new contrato();
$comision = new comision();
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
$router->get('getCodigo', [$home, 'getCodigo']);

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
$router->get('getChicas', [$usuario, 'getChicas']);

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

/******************** Productos ********************/
$router->get('productos', [$producto, 'index']);
$router->get('getProductos', [$producto, 'getProductos']);
$router->get('getProductoCategoria/(\d+)', [$producto, 'getProductoCategoria']);
$router->post('createProducto', [$producto, 'createProducto']);
$router->get('getProducto/(\d+)', [$producto, 'getProducto']);
$router->get('deleteProducto/(\d+)', [$producto, 'deleteProducto']);
$router->get('getProductosPrecio', [$producto, 'getProductosPrecio']);
$router->get('getBebidasPrecio/(\d+)', [$producto, 'getBebidasPrecio']);

/******************** Pedidos ********************/
$router->get('pedidos', [$pedido, 'index']);
$router->get('getChicasActivas', [$pedido, 'getChicasActivas']);
$router->post('createPedido', [$pedido, 'createPedido']);
$router->get('getPedidos', [$pedido, 'getPedidos']);
$router->get('getDetallePedido/(\d+)', [$pedido, 'getDetallePedido']);

/******************** Ventas ********************/
$router->get('ventas', [$venta, 'index']);
$router->post('createVenta', [$venta, 'createVenta']);
$router->get('getVentas', [$venta, 'getVentas']);
$router->get('getVenta/(\d+)', [$venta, 'getVenta']);


/******************** Contratos ********************/
$router->get('contratos', [$contrato, 'index']);
$router->get('getContratos', [$contrato, 'getContratos']);
$router->post('createContrato', [$contrato, 'createContrato']);
$router->get('getContrato/(\d+)', [$contrato, 'getContrato']);
$router->get('deleteContrato/(\d+)', [$contrato, 'deleteContrato']);

/******************** Comisiones ********************/
$router->get('comisiones', [$comision, 'index']);
$router->get('getComisionUsuario', [$comision, 'getComisionUsuario']);
$router->get('getComisiones', [$comision, 'getComisiones']);



$router->run();
