<?php

namespace app\controllers;

use Exception;
use app\config\view;
use app\config\guard;
use app\config\response;
use app\models\ventaModel;
use app\config\controller;

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

        try {
            $view = new view();
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('venta', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(Response::estado404($e));
        }
    }

    public function createVenta()
    {
        if ($this->method !== 'POST') {
            return $this->response(Response::estado405());
        }

        if ($this->data === null) {
            http_response_code(400);
            return $this->response(response::estado400('Datos JSON no válidos.'));
        }

        guard::validateToken($this->header, guard::secretKey());

        $productos = $this->data['productos'] ?? [];
        $usuarios = $this->data['usuario_id'] ?? null;
        $total_comision = $this->data['total_comision'] ?? 0;

        try {
            $venta = $this->model->createVenta($this->data);

            if ($venta == 'ok') {
                $id_venta = $this->model->lastVenta();

                // Guardar el detalle de la venta
                foreach ($productos as $value) {
                    $detalle = [
                        'venta_id' => $id_venta['id_venta'],
                        'producto_id' => $value['id_producto'],
                        'precio' => $value['precio'],
                        'cantidad' => $value['cantidad'],
                        'comision' => $value['comision'],
                        'sub_total' => $value['subtotal'],
                    ];

                    $this->model->createDetalleVenta($detalle);
                }

                // Registro de comisiones
                if (!empty($usuarios)) {
                    // Si hay más de un usuario, dividir la comisión
                    $numUsuarios = is_array($usuarios) ? count($usuarios) : 1;
                    $comisionPorUsuario = $total_comision / $numUsuarios;

                    // Si es un solo usuario, agregar la comisión
                    if (!is_array($usuarios)) {
                        if ($usuarios > 0) {
                            $detalleUsuario = [
                                'venta_id' => $id_venta['id_venta'],
                                'usuario_id' => $usuarios,
                            ];
                            $this->model->createUsuarioVenta($detalleUsuario);

                            // Registrar la comisión para este usuario
                            $comision = [
                                'venta_id' => $id_venta['id_venta'],
                                'usuario_id' => $usuarios,
                                'monto' => $comisionPorUsuario,
                            ];
                            $this->model->createComision($comision);
                        }

                        // Si hay más de un usuario, dividir la comisión y registrar para cada uno
                    } else {
                        foreach ($usuarios as $usuario_id) {
                            if ($usuario_id !== 0) {
                                $detalleUsuario = [
                                    'venta_id' => $id_venta['id_venta'],
                                    'usuario_id' => $usuario_id,
                                ];
                                $this->model->createUsuarioVenta($detalleUsuario);

                                // Registrar la comisión para este usuario
                                $comision = [
                                    'venta_id' => $id_venta['id_venta'],
                                    'usuario_id' => $usuario_id,
                                    'monto' => $comisionPorUsuario,
                                ];
                                $this->model->createComision($comision);
                            }
                        }
                    }
                }

                // Actualizar el estado del pedido si se proporciona un id de pedido
                if (!empty($this->data['id_pedido'])) {
                    $this->model->updatePedido($this->data['id_pedido']);
                }

                http_response_code(201);
                return $this->response(response::estado201());
            }

            http_response_code(500);
            return $this->response(response::estado500());
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(Response::estado500($e));
        }
    }

    public function getVentas()
    {
        if ($this->method !== 'GET') {
            $this->response(Response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $venta = $this->model->getVentas();
            if (!empty($venta)) {
                http_response_code(200);
                return $this->response(response::estado200($venta));
            }
            http_response_code(204);
            return $this->response(response::estado204());
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(Response::estado500($e));
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
                http_response_code(200);
                return $this->response(response::estado200($venta));
            }
            http_response_code(204);
            return $this->response(response::estado204());
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(Response::estado500($e));
        }
    }

}
