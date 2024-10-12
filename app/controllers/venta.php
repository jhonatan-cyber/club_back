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

        if (isset($this->data['productos']) && is_string($this->data['productos'])) {
            $productos = json_decode($this->data['productos'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->response(response::estado400('Error al decodificar productos: ' . json_last_error_msg()));
            }
        } else {
            return $this->response(response::estado400('El campo productos debe ser una cadena JSON.'));
        }

        try {
            $venta = $this->model->createVenta($this->data);
            $this->model->updatePedido($this->data['id_pedido']);
            if ($venta == 'ok') {
                $id_venta = $this->model->lastVenta();

                foreach ($productos as $value) {
                    $detalle = [
                        'venta_id' => $id_venta['id_venta'],
                        'producto_id' => $value['producto_id'],
                        'precio' => $value['precio'],
                        'cantidad' => $value['cantidad'],
                        'comision' => $value['comision'],
                        'sub_total' => $value['subtotal'],
                    ];
                    $this->model->createDetalleVenta($detalle);
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

}
