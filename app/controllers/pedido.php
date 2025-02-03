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
            return $this->response(response::estado405());
        }
        if ($_SESSION['rol'] !== "Administrador" && $_SESSION['rol'] !== "Cajero" && $_SESSION['rol'] !== "Mesero") {
            return $this->response(response::estado403());
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

            return response::estado404($e);
        }
    }

    public function createPedido()
    {
        if ($this->method !== 'POST') {
            return $this->response(response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());


        $productos = $this->data['productos'];
        $chicas = $this->data['chica_id'];


        if ($this->data['cliente_id'] == 0) {
            $this->data['cliente_id'] = 1;
        }

        $this->data['mesero_id'] = $_SESSION['id_usuario'];

        $this->data['codigo'] = $this->generarCodigoAleatorio(8);

        if (count($productos) === 0) {

            $this->response(response::estado400('Selecione productos para realizar el pedido.'));
        }

        $minChica = 120000;
        $maxChica = 1;

        if ($this->data['total'] >= $minChica) {
            $maxChica = floor(($this->data['total'] - $minChica) / 40000) + 2;
        }

        if ($this->data['total'] == 120000) {
            $maxChica = 2;
        }

        if (count($chicas) > $maxChica) {
            return $this->response(response::estado400('Solo puede seleccionar ' . $maxChica . ' anfitriona(s).'));
        }

        if (count($chicas) === 0) {
            $this->data['total_comision'] = 0;
            $chicas = [];
        }

        try {
            $pedido = $this->model->createPedido($this->data);
            if ($pedido !== 'ok') {
                return $this->response(response::estado500('Error al crear el pedido'));
            }

            $id_pedido = $this->model->getLastPedido();

            foreach ($productos as $value) {
                $detalle = [
                    'pedido_id' => $id_pedido['id_pedido'],
                    'producto_id' => $value['id_producto'],
                    'precio' => $value['precio'],
                    'cantidad' => $value['cantidad'],
                    'subtotal' => $value['subtotal'],
                    'comision' => $value['comision'],
                ];
                $detalle_pedido = $this->model->createDetallePedido($detalle);
                if ($detalle_pedido !== 'ok') {
                    return $this->response(response::estado500('Error al crear el detalle del pedido'));
                }
            }

            if (!empty($chicas)) {
                $usuarios = !is_array($chicas) ? [$chicas] : $chicas;
                foreach ($usuarios as $usuario_id) {
                    if ($usuario_id > 0) {
                        $detalle = [
                            'pedido_id' => $id_pedido['id_pedido'],
                            'usuario_id' => $usuario_id,
                        ];
                        $pedido_usuario = $this->model->createPedidoUsuario($detalle);
                        if ($pedido_usuario !== 'ok') {
                            return $this->response(response::estado500('Error al creal el pedido con los usuarios'));
                        }
                    }
                }
            }
            return $this->response(response::estado201());
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

    public function getPedidosGarzon()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        $usuario_id = $_SESSION['id_usuario'];
        try {
            $pedido = $this->model->getPedidosGarzon($usuario_id);
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
