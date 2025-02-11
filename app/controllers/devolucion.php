<?php

namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\devolucionModel;
use app\models\comisionModel;
use app\models\propinaModel;
use app\models\cajaModel;
use app\models\ventaModel;
use Exception;

class devolucion extends controller
{
    private $devolucion;
    private $comision;
    private $propina;
    private $caja;
    private $venta;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->devolucion = new devolucionModel();
        $this->comision = new comisionModel();
        $this->propina = new propinaModel();
        $this->caja = new cajaModel();
        $this->venta = new ventaModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }
        if ($_SESSION['rol'] !== "Administrador" && $_SESSION['rol'] !== "Cajero") {
            return $this->response(response::estado403());
        }
        try {
            $view = new view();
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('devolucion', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            $this->response(Response::estado404($e));
        }
    }

    public function getAllServicios()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            $servicios = $this->devolucion->getAllServicios();
            if (empty($servicios)) {
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($servicios));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function createDevolucion()
    {
        if ($this->method !== 'POST') {
            return $this->response(Response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            if (
                !is_numeric($this->data['servicio_id']) ||
                !is_numeric($this->data['pieza_id']) ||
                !is_numeric($this->data['cliente_id']) ||
                !is_numeric($this->data['total'])
            ) {
                return $this->response(response::estado400());
            }

            $usuario_id = $this->data['usuario_id'];
            $devolucion = $this->devolucion->createDevolucionServicio($this->data);

            if ($devolucion === 'ok') {
                $id_devolucion = $this->devolucion->getLastDevolucion();

                if (!empty($usuario_id)) {
                    $usuarios = is_array($usuario_id) ? count($usuario_id) : 1;
                    $montoUsuario = $this->data['total'] / $usuarios;

                    if (!array($usuario_id)) {
                        if ($usuario_id > 0) {
                            $detalleDevolucion = [
                                'devolucion_id' => $id_devolucion['id_devolucion'],
                                'usuario_id' => $usuario_id,
                                'monto' => $montoUsuario
                            ];

                            $this->devolucion->createDetalleDevolucion($detalleDevolucion);
                        }
                    } else {
                        foreach ($usuario_id as $key => $value) {
                            $detalleDevolucion = [
                                'devolucion_id' => $id_devolucion['id_devolucion'],
                                'usuario_id' => $value,
                                'monto' => $montoUsuario
                            ];

                            $this->devolucion->createDetalleDevolucion($detalleDevolucion);
                        }
                    }

                    $servicio = $this->devolucion->updateServicio($this->data['servicio_id']);

                    if ($servicio !== 'ok') {
                        return $this->response(response::estado500(' Error al actualizar el servicio '));
                    }

                    $comision = $this->devolucion->updateComision($this->data['servicio_id']);

                    if ($comision !== 'ok') {
                        return $this->response(response::estado500('Error al actualizar la comision '));
                    }
                    $id_comision = $this->devolucion->getComisionServicio($this->data['servicio_id']);

                    $detalle_comision = $this->devolucion->updateDetalleComision($id_comision['id_comision']);

                    if ($detalle_comision !== 'ok') {
                        return $this->response(response::estado500('Error al actualizar el detalle de la comision '));
                    }
                }

                return $this->response(response::estado201());
            }
            return $this->response(response::estado500());
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function getDevolucion(int $id_devolucion)
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            $devolucion = $this->devolucion->getDevolucion($id_devolucion);
            if (empty($devolucion)) {
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($devolucion));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function getDevoluciones()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            $devoluciones = $this->devolucion->getDevoluciones();
            if (empty($devoluciones)) {
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($devoluciones));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function createDevolucionVenta()
    {
        if ($this->method !== 'POST') {
            return $this->response(Response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        try {

            if ($this->data === null || empty($this->data)) {
                return $this->response(response::estado400('No se recibieron datos'));
            }
            $usuario_id = $this->data['usuario_id'] ?? [];
            $productos = $this->data['producto'] ?? [];
            $d_devolucion_venta = [
                'cliente_id' => $this->data['cliente_id'],
                'venta_id' => $this->data['venta_id'],
                'total' => $this->data['total']
            ];
            $devolucion_venta = $this->devolucion->createDevolucionVenta($d_devolucion_venta);
            if ($devolucion_venta !== 'ok') {
                return $this->response(response::estado500('Error al crear la devolucion de venta'));
            }
            $id_devolucion_venta = $this->devolucion->getLastDevolucionVenta()['id_devolucion_venta'];

            foreach ($productos as $producto) {
                $d_detalle_devolucion_venta = [
                    'devolucion_venta_id' => $id_devolucion_venta,
                    'producto_id' => $producto['id_producto'],
                    'cantidad' => $producto['cantidad'],
                    'precio' => $producto['precio'],
                    'comision' => $producto['comision']
                ];
                $detalle_devolucion_venta = $this->devolucion->createDetalleDevolucionVenta($d_detalle_devolucion_venta);
                if ($detalle_devolucion_venta !== 'ok') {
                    return $this->response(response::estado500('Error al crear el detalle de la devolucion'));
                }

                $id_detalle_devolucion = $this->devolucion->getLastDetalleDevolucionVenta()['id_detalle_devolucion'];

                if (!empty($usuario_id)) {
                    foreach ($usuario_id as $usuario) {
                        $d_devolucion_venta_usuario = [
                            'detalle_devolucion_venta_id' => $id_detalle_devolucion,
                            'usuario_id' => $usuario,
                        ];
                        $devolucion_venta_usuario = $this->devolucion->createDevolucionVentaUsuario($d_devolucion_venta_usuario);
                        if ($devolucion_venta_usuario !== 'ok') {
                            return $this->response(response::estado500('Error al crear el detalle de la devolucion'));
                        }
                    }
                }
            }

            $comision = $this->comision->updateComisiones($this->data['venta_id']);
            if ($comision !== 'ok') {
                return $this->response(response::estado500('Error al actualizar la comision'));
            }

            $id_comision = $this->comision->getComisionVenta($this->data['venta_id'])['id_comision'];

            if ($id_comision == null || empty($id_comision)) {
                return $this->response(response::estado500('Error al obtener la comision'));
            }

            $detalle_comision = $this->comision->getDetalleComision($id_comision);

            if (empty($detalle_comision) || $detalle_comision === null) {
                return $this->response(response::estado500('Error al obtener el detalle de la comision'));
            }

            $id_detalle_comision = array_map(function ($detalle) {
                return $detalle['id_detalle_comision'];
            }, $detalle_comision);

            $detalle_comision_update = $this->comision->updateDetalleComisiones($id_detalle_comision);
            if ($detalle_comision_update !== 'ok') {
                return $this->response(response::estado500('Error al actualizar el detalle de la comision'));
            }


            $propina = $this->propina->getPropinaVenta($this->data['venta_id'])['propina'];

            if ($propina > 0) {

                $propina_update = $this->propina->updatePropinaDevolcion($propina);
                if ($propina_update !== 'ok') {
                    return $this->response(response::estado500('Error al actualizar la propina'));
                }
                if (!empty($usuario_id)) {
                    foreach ($usuario_id as $usuario) {
                        $d_detalle_propina = [
                            'usuario_id' => $usuario,
                            'monto' => $propina * -1
                        ];

                        $detalle_propina = $this->propina->createDetallePropina($d_detalle_propina);

                        if ($detalle_propina !== 'ok') {
                            return $this->response(response::estado500('Error al actualizar el detalle de la propina'));
                        }
                    }
                }
            }

            $venta_update = $this->venta->updateVenta($this->data['venta_id']);
            if ($venta_update !== 'ok') {
                return $this->response(response::estado500('Error al actualizar la venta'));
            }
            $total = $this->data['total'] + $propina;
            $caja_update = $this->caja->updateCajaDevolucion($total);
            if ($caja_update !== 'ok') {
                return $this->response(response::estado500('Error al actualizar la caja'));
            }

            return $this->response(response::estado200('Venta anulada con exito'));
        } catch (Exception $e) {

            $this->response(response::estado500($e));
        }
    }


    public function getDevolucionesVentas()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $devoluciones = $this->devolucion->getDevolucionesVenta();
            if (!empty($devoluciones)) {
                return $this->response(response::estado200($devoluciones));
            }
            return $this->response(response::estado204());
        } catch (Exception $e) {
            $this->response(response::estado500($e));
        }
    }
}
