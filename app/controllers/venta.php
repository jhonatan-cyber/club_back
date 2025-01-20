<?php

namespace app\controllers;

use app\config\cache;
use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\ventaModel;
use Exception;

class venta extends controller
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new ventaModel();
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
        $chicas = $this->data['usuario_id'] ?? $this->data['chica_id'] ?? null;
        $total_comision = $this->data['total_comision'] ?? 0;

        try {
        
            $venta = $this->model->createVenta($this->data);
            if ($venta !== 'ok') {
                return $this->response(response::estado500('Error al crear la venta'));
            }

            $id_venta = $this->model->getLastVenta();

       
            foreach ($productos as $value) {
                $detalle = [
                    'venta_id' => $id_venta['id_venta'],
                    'producto_id' => $value['id_producto'],
                    'precio' => $value['precio'],
                    'cantidad' => $value['cantidad'],
                    'comision' => $value['comision'],
                    'sub_total' => $value['subtotal'],
                ];
                $detalle_venta = $this->model->createDetalleVenta($detalle);
                if ($detalle_venta !== 'ok') {
                    return $this->response(response::estado500('Error al crear el detalle de la venta'));
                }
            }

        
            if (!empty($chicas)) {
                $numUsuarios = is_array($chicas) ? count($chicas) : 1;
                $comisionPorUsuario = $total_comision / $numUsuarios;

                $usuarios = !is_array($chicas) ? [$chicas] : $chicas;
                foreach ($usuarios as $usuario_id) {
                    if ($usuario_id > 0) {
                  
                        $data_comision = [
                            'venta_id' => $id_venta['id_venta'],
                            'monto' => $total_comision,
                        ];
                        $comision = $this->model->createComision($data_comision);
                        if ($comision !== 'ok') {
                            return $this->response(response::estado500('Error al crear la comision'));
                        }
                        $id_comision = $this->model->getLastComision();
                        $detalle_comision = [
                            'comision_id' => $id_comision['comision_id'],
                            'chica_id' => $usuario_id,
                            'comision' => $comisionPorUsuario,
                        ];
                        $detalle = $this->model->cretaeDetalleComision($detalle_comision);
                        if ($detalle !== 'ok') {
                            return $this->response(response::estado500('Error al crear el detalle de la comision'));
                        }

                        $detalleUsuario = [
                            'venta_id' => $id_venta['id_venta'],
                            'usuario_id' => $usuario_id,
                        ];
                        $usuario_venta = $this->model->createUsuarioVenta($detalleUsuario);
                        if ($usuario_venta !== 'ok') {
                            return $this->response(response::estado500('Error al crear el detalle de la venta'));
                        }

                        $anticipos = $this->model->getAnticipoUsuario($usuario_id);
                        if (!empty($anticipos) && is_array($anticipos)) {
                            foreach ($anticipos as $anticipo) {
                                if (isset($anticipo['id_anticipo']) && $total_comision > $anticipo['monto']) {
                                    $updateResult = $this->model->updateAnticipo($anticipo['id_anticipo']);
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
                $propina = $this->model->createPropina($this->data['propina']);
                if ($propina !== 'ok') {
                    return $this->response(response::estado500('Error al crear la propina'));
                }

                $personal = $this->model->getMeserosCajera();
                if (empty($personal)) {
                    return $this->response(Response::estado204('No se encontraron meseros y cajeras disponibles'));
                }

                $id_propina = $this->model->getLastPropina();
                if (!$id_propina) {
                    return $this->response(Response::estado500('Error al obtener el ID de la propina'));
                }

                if (count($personal) > 0) {
                    $propinaPorPersona = $this->data['propina'] / count($personal);
                    foreach ($personal as $empleado) {
                        $detalle_propina = [
                            'monto' => $propinaPorPersona,
                            'propina_id' => $id_propina['id_propina'],
                            'usuario_id' => $empleado['usuario_id']
                        ];
                        $propina_detalle = $this->model->createDetallePropina($detalle_propina);
                        if ($propina_detalle !== 'ok') {
                            return $this->response(response::estado500('Error al crear el detalle de la propina'));
                        }
                    }
                }
            }


            if (!empty($this->data['id_pedido'])) {
                $pedido = $this->model->updatePedido($this->data['id_pedido']);
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
                'monto_cierre' => $monto_cierre,
                'monto_trasferencia' => $monto_transferencia,
            ];

            $caja = $this->model->updateCaja($detalle_caja);
            if ($caja !== 'ok') {
                return $this->response(response::estado500('Error al actualizar la caja'));
            }

            return $this->response(response::estado201('Venta realizada con éxito'));
        } catch (Exception $e) {
            return $this->response(Response::estado500($e));
        }
    }

    public function getVentas()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());

        try {
            $ventas = $this->model->getVentas();
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
            $venta = $this->model->getVenta($id);
            if (!empty($venta)) {
                return $this->response(response::estado200($venta));
            }
            return $this->response(response::estado204());
        } catch (Exception $e) {
            return $this->response(Response::estado500($e));
        }
    }
}
