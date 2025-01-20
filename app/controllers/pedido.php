<?php

namespace app\controllers;

use app\config\cache;
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
        if ($_SESSION['rol'] == "Administrador" || $_SESSION['rol'] == "Cajero" || $_SESSION['rol'] == "Mesero") {
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
        }else{
            $this->response(response::estado403());
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
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (empty($data)) {
                return $this->response(response::estado400());
            }

            $required = ['chica_id', 'subtotal', 'total'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return $this->response(response::estado400("El campo $field es obligatorio"));
                }
            }

            if (isset($data['productos']) && is_string($data['productos'])) {
                $productos = json_decode($data['productos'], true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return $this->response(response::estado400(['Error al decodificar productos: ' . json_last_error_msg()]));
                }
            } else {
                return $this->response(response::estado400(['El campo productos debe ser una cadena JSON.']));
            }

            $data['mesero_id'] = $_SESSION['id_usuario'];
            $data['codigo'] = $this->generarCodigoAleatorio(8);

            $result = $this->model->createPedido($data);

            if ($result) {
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

                $message = json_encode([
                    'tipo' => 'pedido',
                    'accion' => 'createPedido',
                    'data' => $data
                ]);
                file_put_contents(__DIR__ . '/../../tmp/websocket_message.txt', $message);

                return $this->response(response::estado201());
            }

            return $this->response(response::estado400());
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
                return $this->response(response::estado200($pedido));
            }
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

    function generarCodigoAleatorio($length)
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = '';

        for ($i = 0; $i < $length; $i++) {
            $codigo .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $codigo;
    }
}
