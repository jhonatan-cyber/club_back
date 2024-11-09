<?php

namespace app\controllers;

use app\config\controller;
use app\models\cuentaModel;
use app\config\response;
use app\config\guard;
use app\config\view;
use Exception;

class cuenta extends controller
{
    private $model;
    private static $valdiate_number = '/^[0-9]+$/';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new cuentaModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        try {
            $view = new view();
            session_regenerate_id(true);

            if (!empty($_SESSION['activo'])) {
                echo $view->render('cuenta', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(Response::estado404($e));
        }
    }
    public function getCuentas()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            $cuentas = $this->model->getCuentas();
            if (empty($cuentas)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($cuentas));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }
    public function getDetalleCuentas(int $cuenta_id)
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            $cuenta = $this->model->getDetalleCuentas($cuenta_id);
            if (empty($cuenta)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($cuenta));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function cobrarCuenta()
    {
        if ($this->method !== 'POST') {
            return $this->response(Response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {

            $cuenta = $this->model->cobrarCuenta($this->data);
            if ($cuenta === "ok") {
                http_response_code(201);
                return $this->response(response::estado201());
            }
            http_response_code(500);
            return $this->response(response::estado500());
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function createDetalleCuenta()
    {
        if ($this->method !== 'POST') {
            return $this->response(Response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        try {
            if (isset($this->data['cuenta_id']) && isset($this->data['productos']) && is_array($this->data['productos'])) {
                $productos = $this->data['productos'];
                foreach ($productos as $producto) {
                    $detalle = [
                        "cuenta_id" => $this->data['cuenta_id'] ?? null,
                        "producto_id" => $producto['id_producto'] ?? null,
                        "precio" => $producto['precio'] ?? 0,
                        "cantidad" => $producto['cantidad'] ?? 0,
                        "comision" => $producto['comision'] ?? 0,
                        "subtotal" => $producto['subtotal'] ?? 0,
                    ];
                    $cuenta_d = $this->model->createDetalleCuenta($detalle);
                    if ($cuenta_d === "ok") {
                        $datos = [
                            "id_cuenta" => $this->data['cuenta_id'],
                            "sub_total" => $producto['subtotal'],
                            "total_comision" => $producto['comision'],

                        ];

                        $cuenta = $this->model->updateCuenta($datos);
                    }

                }
                if ($cuenta === "ok") {

                    http_response_code(201);
                    return $this->response(response::estado201());
                }
                http_response_code(500);
                return $this->response(response::estado500());
            }
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }
}