<?php

use app\controllers\anticipo;
use app\controllers\asistencia;
use app\controllers\caja;
use app\controllers\categoria;
use app\controllers\cliente;
use app\controllers\comision;
use app\controllers\cuenta;
use app\controllers\devolucion;
<<<<<<< HEAD
use app\controllers\propina;
use app\controllers\asistencia;
use app\controllers\caja;
=======
use app\controllers\home;
>>>>>>> 21c0d6e (arreglos)
use app\controllers\horaExtra;
use app\controllers\login;
use app\controllers\pedido;
use app\controllers\pieza;
use app\controllers\planilla;
use app\controllers\producto;
use app\controllers\propina;
use app\controllers\rol;
use app\controllers\servicio;
use app\controllers\usuario;
use app\controllers\venta;
use Bramus\Router\Router;

$router = new Router();
$rol = new rol();
$devolucion = new devolucion();
$usuario = new usuario();
$login = new login();
$cliente = new cliente();
$home = new home();
$categoria = new categoria();
$producto = new producto();
$pedido = new pedido();
$venta = new venta();
$comision = new comision();
$pieza = new pieza();
$servicio = new servicio();
$cuenta = new cuenta();
$anticipo = new anticipo();
$planilla = new planilla();
$propina = new propina();
$asistencia = new asistencia();
$horaExtra = new horaExtra();
$caja = new caja();

<<<<<<< HEAD

/******************** Login ********************/
=======
/* Login */
>>>>>>> 21c0d6e (arreglos)
$router->get('/', [$login, 'index']);
$router->post('login', [$login, 'login']);
$router->get('logout', [$login, 'logout']);
$router->get('updateCodigo', [$login, 'updateCodigo']);
$router->get('createCodigo', [$login, 'createCodigo']);
$router->get('validarCodigo/(.+)', [$login, 'validarCodigo']);
$router->get('createAsistencia/(\d+)', [$login, 'createAsistencia']);

/* Home */
$router->get('home', [$home, 'index']);
$router->get('getCodigo', [$home, 'getCodigo']);

/* Roles */
$router->get('roles', [$rol, 'index']);
$router->get('getRoles', [$rol, 'getRoles']);
$router->post('createRol', [$rol, 'createRol']);
$router->get('getRol/(\d+)', [$rol, 'getRol']);
$router->get('deleteRol/(\d+)', [$rol, 'deleteRol']);

/* Usuarios */
$router->get('usuarios', [$usuario, 'index']);
$router->get('getUsuarios', [$usuario, 'getUsuarios']);
$router->post('createUsuario', [$usuario, 'createUsuario']);
$router->get('getUsuario/(\d+)', [$usuario, 'getUsuario']);
$router->get('deleteUsuario/(\d+)', [$usuario, 'deleteUsuario']);
$router->get('getChicas', [$usuario, 'getChicas']);

/* Clientes */
$router->get('clientes', [$cliente, 'index']);
$router->get('getClientes', [$cliente, 'getClientes']);
$router->post('createCliente', [$cliente, 'createCliente']);
$router->get('getCliente/(\d+)', [$cliente, 'getCliente']);
$router->get('deleteCliente/(\d+)', [$cliente, 'deleteCliente']);

/* Categorias */
$router->get('categorias', [$categoria, 'index']);
$router->get('getCategorias', [$categoria, 'getCategorias']);
$router->post('createCategoria', [$categoria, 'createCategoria']);
$router->get('getCategoria/(\d+)', [$categoria, 'getCategoria']);
$router->get('deleteCategoria/(\d+)', [$categoria, 'deleteCategoria']);

/* Productos */
$router->get('productos', [$producto, 'index']);
$router->get('getProductos', [$producto, 'getProductos']);
$router->get('getProductoCategoria/(\d+)', [$producto, 'getProductoCategoria']);
$router->post('createProducto', [$producto, 'createProducto']);
$router->get('getProducto/(\d+)', [$producto, 'getProducto']);
$router->get('deleteProducto/(\d+)', [$producto, 'deleteProducto']);
$router->get('getProductosPrecio', [$producto, 'getProductosPrecio']);
$router->get('getBebidasPrecio/(\d+)', [$producto, 'getBebidasPrecio']);

/* Pedidos */
$router->get('pedidos', [$pedido, 'index']);
$router->get('getChicasActivas', [$pedido, 'getChicasActivas']);
$router->post('createPedido', [$pedido, 'createPedido']);
$router->get('getPedidos', [$pedido, 'getPedidos']);
$router->get('getDetallePedido/(\d+)', [$pedido, 'getDetallePedido']);

/* Ventas */
$router->get('ventas', [$venta, 'index']);
$router->post('createVenta', [$venta, 'createVenta']);
$router->get('getVentas', [$venta, 'getVentas']);
$router->get('getVenta/(\d+)', [$venta, 'getVenta']);

/* Comisiones */
$router->get('comisiones', [$comision, 'index']);
$router->get('getComisionUsuario', [$comision, 'getComisionUsuario']);
$router->get('getComisiones', [$comision, 'getComisiones']);

/* Piezas */
$router->get('habitaciones', [$pieza, 'index']);
$router->get('getPiezas', [$pieza, 'getPiezas']);
$router->post('createPieza', [$pieza, 'createPieza']);
$router->get('getPieza/(\d+)', [$pieza, 'getPieza']);
$router->get('deletePieza/(\d+)', [$pieza, 'deletePieza']);
$router->get('getPiezasLibres', [$pieza, 'getPiezasLibres']);

/* Servicios */
$router->get('servicios', [$servicio, 'index']);
$router->post('createServicio', [$servicio, 'createServicio']);
$router->get('getServicio/(\d+)', [$servicio, 'getServicio']);
$router->get('getServicios', [$servicio, 'getServicios']);
$router->get('getCuenta/(\w+)', [$servicio, 'getCuenta']);
$router->get('updateServicio/(\d+)', [$servicio, 'updateServicio']);
$router->get('updatePieza/(\d+)', [$servicio, 'updatePieza']);
$router->get('getDetalleCuenta/(\d+)', [$servicio, 'getDetalleCuenta']);
$router->get('getServicio/(\w+)', [$servicio, 'getServicio']);
$router->post('updateCuenta', [$servicio, 'updateCuenta']);
$router->get('getCuentaServicio/(\d+)', [$servicio, 'getCuentaServicio']);

/* Cuentas */
$router->get('cuentas', [$cuenta, 'index']);
$router->get('getCuentas', [$cuenta, 'getCuentas']);
$router->post('clearCuentasCache', [$cuenta, 'clearCuentasCache']);
$router->get('getDetalleCuentas/(\d+)', [$cuenta, 'getDetalleCuentas']);
$router->post('cobrarCuenta', [$cuenta, 'cobrarCuenta']);
$router->post('createDetalleCuenta', [$cuenta, 'createDetalleCuenta']);
$router->post('createCuenta', [$cuenta, 'createCuenta']);

/* Anticipos */
$router->get('anticipos', [$anticipo, 'index']);
$router->get('getAnticipos', [$anticipo, 'getAnticipos']);
$router->post('createAnticipo', [$anticipo, 'createAnticipo']);
$router->get('getAnticipo/(\d+)', [$anticipo, 'getAnticipo']);
$router->post('updateAnticipo', [$anticipo, 'updateAnticipo']);

/* Devoluciones */
$router->get('devoluciones', [$devolucion, 'index']);
$router->get('getAllServicios', [$devolucion, 'getAllServicios']);
$router->post('createDevolucion', [$devolucion, 'createDevolucion']);
$router->get('getDevolucion/(\d+)', [$devolucion, 'getDevolucion']);
$router->get('getDevoluciones', [$devolucion, 'getDevoluciones']);

$router->post('createDevolucionVenta', [$devolucion, 'createDevolucionVenta']);
<<<<<<< HEAD
$router->get('getDevolucionesVentas', [$devolucion,'getDevolucionesVentas']);
=======
$router->get('getDevolucionesVentas', [$devolucion, 'getDevolucionesVentas']);
>>>>>>> 21c0d6e (arreglos)

/* Planillas */
$router->get('planillas', [$planilla, 'index']);
$router->get('getPlanillas', [$planilla, 'getPlanillas']);
$router->get('pagarPlanilla/(\d+)', [$planilla, 'pagarPlanilla']);

<<<<<<< HEAD

/******************** Propinas ********************/
=======
/* Propinas */
>>>>>>> 21c0d6e (arreglos)
$router->get('propinas', [$propina, 'index']);
$router->get('getPropinas', [$propina, 'getPropinas']);

<<<<<<< HEAD

/******************** Asistencias ********************/
=======
/* Asistencias */
>>>>>>> 21c0d6e (arreglos)
$router->get('asistencias', [$asistencia, 'index']);
$router->get('getAsistencias', [$asistencia, 'getAsistencias']);
$router->get('getAsistencia/(\d+)', [$asistencia, 'getAsistencia']);

/* Horas Extras */
$router->get('horasExtras', [$horaExtra, 'index']);
$router->post('createHoraExtra', [$horaExtra, 'createHoraExtra']);
$router->get('getHorasExtras', [$horaExtra, 'getHorasExtras']);
$router->get('getHoraExtra/(\d+)', [$horaExtra, 'getHoraExtra']);

<<<<<<< HEAD
/******************** Cajas ********************/
$router->get('cajas', [$caja, 'index']);
$router->post('createCaja', [$caja, 'createCaja']);
$router->get('getCajas', [$caja,'getCajas']);
$router->get('cerrarCaja/(\d+)', [$caja,'cerrarCaja']);



=======
/* Cajas */
$router->get('cajas', [$caja, 'index']);
$router->post('createCaja', [$caja, 'createCaja']);
$router->get('getCajas', [$caja, 'getCajas']);
$router->get('cerrarCaja/(\d+)', [$caja, 'cerrarCaja']);
>>>>>>> 21c0d6e (arreglos)

$router->run();
