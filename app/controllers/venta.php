<?php

namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\ventaModel;
use app\models\pedidoModel;
use app\models\comisionModel;
use app\models\anticipoModel;
use app\models\propinaModel;
use app\models\cuentaModel;
use app\models\cajaModel;
use Exception;

class venta extends controller
{
    private $venta;
    private $comision;
    private $cuenta;
    private $propina;
    private $pedido;
    private $anticipo;
    private $caja;

    public function __construct()
    {
        parent::__construct();
        $this->venta = new ventaModel();
        $this->pedido = new pedidoModel();
        $this->comision = new comisionModel();
        $this->anticipo = new anticipoModel();
        $this->propina = new propinaModel();
        $this->cuenta = new cuentaModel();
        $this->caja = new cajaModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            $this->response(Response::estado405());
        }
        if ($_SESSION['rol'] !== "Administrador" && $_SESSION['rol'] !== "Cajero") {
            return $this->response(response::estado403());
        }
        try {
            $view = new view();
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('venta', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            $this->response(Response::estado404($e));
        }
    }

    public function createVenta()
    {
        if ($this->method !== 'POST') {
            return $this->response(Response::estado405());
        }

        if ($this->data === null) {
            return $this->response(response::estado400('Datos JSON no válidos.'));
        }

        guard::validateToken($this->header, guard::secretKey());

        $productos = $this->data['productos'] ?? [];
        $chicas = $this->data['usuario_id'] ?? null;
        $total_comision = $this->data['total_comision'] ?? 0;

        $d_venta = [
            'codigo' => (string) $this->data['codigo'],
            'cliente_id' => (int) $this->data['cliente_id'],
            'pieza_id' => (int) $this->data['pieza_id'],
            'metodo_pago' => (string) $this->data['metodo_pago'],
            'iva' => (int) $this->data['iva'],
            'total' => (int) $this->data['total'],
            'total_comision' => (int) $total_comision,
        ];

        try {
            $venta = $this->venta->createVenta($d_venta);
            if ($venta !== 'ok') {
                return $this->response(response::estado500('Error al crear la venta'));
            }

            $id_venta = $this->venta->getLastVenta();

            foreach ($productos as $value) {
                $d_detalle_venta = [
                    'venta_id' => (int)$id_venta['id_venta'],
                    'producto_id' => (int)$value['id_producto'],
                    'precio' => (int)$value['precio'],
                    'cantidad' => (int)$value['cantidad'],
                    'comision' => (int)$value['comision'],
                    'sub_total' => (int)$value['subtotal'],
                ];
                $detalle_venta = $this->venta->createDetalleVenta($d_detalle_venta);

                if ($detalle_venta !== 'ok') {
                    return $this->response(response::estado500('Error al crear el detalle de la venta'));
                }

                if (!empty($chicas)) {

                    $numUsuarios = is_array($chicas) ? count($chicas) : 1;
                    $comisionPorUsuario = $total_comision / $numUsuarios;

                    $usuarios = !is_array($chicas) ? [$chicas] : $chicas;
                    $data_comision = [
                        'venta_id' => (int)$id_venta['id_venta'],
                        'monto' => (int)$total_comision,
                    ];
                    $comision = $this->comision->createComision($data_comision);
                    if ($comision !== 'ok') {
                        return $this->response(response::estado500('Error al crear la comision'));
                    }
                    foreach ($usuarios as $usuario_id) {
                        if ($usuario_id > 0) {

                            $id_comision = $this->comision->getLastComision();
                            $detalle_comision = [
                                'comision_id' => (int)$id_comision['comision_id'],
                                'chica_id' => (int)$usuario_id,
                                'comision' => (int)$comisionPorUsuario,
                            ];
                            $detalle = $this->comision->cretaeDetalleComision($detalle_comision);
                            if ($detalle !== 'ok') {
                                return $this->response(response::estado500('Error al crear el detalle de la comision'));
                            }

                            $detalleUsuario = [
                                'venta_id' => (int)$id_venta['id_venta'],
                                'usuario_id' => (int)$usuario_id,
                            ];
                            $usuario_venta = $this->venta->createUsuarioVenta($detalleUsuario);
                            if ($usuario_venta !== 'ok') {
                                return $this->response(response::estado500('Error al crear el detalle de la venta'));
                            }

                            $anticipos = $this->anticipo->getAnticipoUsuario($usuario_id);
                            if (!empty($anticipos) && is_array($anticipos)) {
                                foreach ($anticipos as $anticipo) {
                                    if (isset($anticipo['id_anticipo']) && $total_comision > $anticipo['monto']) {
                                        $updateResult = $this->anticipo->updateAnticipo((int)$anticipo['id_anticipo']);
                                        if ($updateResult !== 'ok') {
                                            error_log('Error al actualizar el anticipo: ' . $updateResult);
                                            return $this->response(response::estado500('Error al actualizar el anticipo'));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


                if ($this->data['propina'] > 0) {
                    $propina = $this->propina->createPropina($this->data['propina']);
                    if ($propina !== 'ok') {
                        return $this->response(response::estado500('Error al crear la propina'));
                    }

                    $personal = $this->venta->getMeserosCajera();
                    if (empty($personal)) {
                        return $this->response(Response::estado204('No se encontraron meseros y cajeras disponibles'));
                    }

                    $id_propina = $this->propina->getLastPropina();
                    if (!$id_propina) {
                        return $this->response(Response::estado500('Error al obtener el ID de la propina'));
                    }

                    if (count($personal) > 0) {
                        $propinaPorPersona = $this->data['propina'] / count($personal);
                        foreach ($personal as $empleado) {
                            $d_propina = [
                                'monto' => (int)$propinaPorPersona,
                                'propina_id' => (int)$id_propina['id_propina'],
                                'usuario_id' => (int)$empleado['usuario_id']
                            ];
                            $propina_detalle = $this->propina->createDetallePropina($d_propina);
                            if ($propina_detalle !== 'ok') {
                                return $this->response(response::estado500('Error al crear el detalle de la propina'));
                            }
                        }
                    }
                }


                if (!empty($this->data['id_pedido'])) {
                    $pedido = $this->pedido->updatePedido($this->data['id_pedido']);
                    if ($pedido !== 'ok') {
                        return $this->response(response::estado500('Error al actualizar el pedido'));
                    }
                }

                $monto_cierre = 0;
                $monto_transferencia = 0;
                if ($this->data['metodo_pago'] == 'Efectivo') {
                    $monto_cierre = $this->data['total'];
                } else if ($this->data['metodo_pago'] == 'Transferencia' || $this->data['metodo_pago'] == 'Tarjeta') {
                    $monto_transferencia = $this->data['total'];
                }

                $detalle_caja = [
                    'monto_cierre' => (int)$monto_cierre,
                    'monto_trasferencia' => (int)$monto_transferencia,
                ];

                $caja = $this->caja->updateCaja($detalle_caja);
                if ($caja !== 'ok') {
                    return $this->response(response::estado500('Error al actualizar la caja'));
                }

                return $this->response(response::estado201('Venta realizada con éxito'));
            }
        } catch (Exception $e) {
            return $this->response(response::estado500('Error al crear la venta'));
        }
    }

    public function getVentas()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());

        try {
            $ventas = $this->venta->getVentas();
            if (!empty($ventas)) {
                return $this->response(response::estado200($ventas));
            }
            return $this->response(response::estado204('No se encontraron ventas'));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function getVenta(int $id)
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $venta = $this->venta->getVenta($id);
            if (!empty($venta)) {
                return $this->response(response::estado200($venta));
            }
            return $this->response(response::estado204());
        } catch (Exception $e) {
            return $this->response(Response::estado500($e));
        }
    }
}
