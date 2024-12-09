<?php

namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\pedidoModel;
use Exception;

class pedido extends controller
{
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new pedidoModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            $this->response(response::estado405());
        }

        try {
            $view = new view();
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('pedido', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(response::estado404($e));
        }
    }

    public function getChicasActivas()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $chicas = $this->model->getChicasActivas();
            if (empty($chicas)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($chicas));
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado400($e));
        }
    }

    public function createPedido()
    {
        if ($this->method !== 'POST') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        if ($this->data === null) {
            http_response_code(400);
            return $this->response(response::estado400(['Datos JSON no válidos.']));
        }

        $required = ['chica_id', 'subtotal', 'total'];
        foreach ($required as $field) {
            if (empty($this->data[$field])) {
                http_response_code(400);
                return $this->response(response::estado400("El campo $field es obligatorio"));
            }
        }

        guard::validateToken($this->header, guard::secretKey());
        if (isset($this->data['productos']) && is_string($this->data['productos'])) {
            $productos = json_decode($this->data['productos'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->response(response::estado400(['Error al decodificar productos: ' . json_last_error_msg()]));
            }
        } else {
            return $this->response(response::estado400(['El campo productos debe ser una cadena JSON.']));
        }
        try {
            $this->data['mesero_id'] = $_SESSION['id_usuario'];
            $this->data['codigo'] = generarCodigoAleatorio(8);

            $pedido = $this->model->createPedido($this->data);
            if ($pedido !== 'ok') {
                return $this->response(response::estado500());
            }
            if ($pedido === 'ok') {
                $id_pedido = $this->model->getLastPedido();
                foreach ($productos as $value) {
                    $detalle = [
                        'pedido_id' => $id_pedido['id_pedido'],
                        'producto_id' => $value['id_producto'],
                        'precio' => $value['precio'],
                        'cantidad' => $value['cantidad'],
                        'subtotal' => $value['subtotal'],
                        'comision' => $value['comision']
                    ];
                    $this->model->createDetallePedido($detalle);
                }
                http_response_code(201);
                return $this->response(response::estado201());
            }
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function getPedidos()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        try {
            $pedido = $this->model->getPedidos();
            if (!empty($pedido)) {
                http_response_code(200);
                return $this->response(response::estado200($pedido));
            }
            http_response_code(204);
            return $this->response(response::estado204());
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }

    public function getDetallePedido(int $id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $pedido = $this->model->getDetallePedido($id);
            if (!empty($pedido)) {
                http_response_code(200);
                return $this->response(response::estado200($pedido));
            }
            http_response_code(204);
            return $this->response(response::estado204());
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado400($e));
        }
    }
}

function generarCodigoAleatorio($length)
{
    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';

    for ($i = 0; $i < $length; $i++) {
        $codigo .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $codigo;
}
