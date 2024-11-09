<?php

namespace app\controllers;

use app\config\controller;
use app\models\servicioModel;
use app\config\response;
use app\config\guard;
use app\config\view;
use Exception;

class servicio extends controller
{
    private $model;
    private static $validar_numero = '/^[0-9]+$/';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new servicioModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        try {
            $view = new view();
            session_regenerate_id(true);

            if (!empty($_SESSION['activo'])) {
                echo $view->render('servicio', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(response::estado404($e));
        }
    }
    public function getServicio(string $codigo)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $servicio = $this->model->getServicio($codigo);
            if (!empty($servicio)) {
                http_response_code(200);
                return $this->response(response::estado200($servicio));
            }
            http_response_code(204);
            return $this->response(response::estado204());
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }
    public function createServicio()
    {
        if ($this->method !== 'POST') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        try {

            $productos = $this->data['productos'] ?? [];
            $usuarios = $this->data['usuario_id'] ?? [];
            $servicio = $this->model->createServicio($this->data);
            if ($servicio === "ok") {
                $id_servicio = $this->model->getServicioCodigo($this->data['codigo']);

                foreach ($usuarios as $value) {
                    if ($value !== 0) {
                        $detalle = [
                            'servicio_id' => $id_servicio['id_servicio'],
                            'usuario_id' => $value
                        ];
                        $this->model->createDetalleServicio($detalle);
                    }
                }

                if (!empty($productos)) {
                    $this->data['servicio_id'] = $id_servicio['id_servicio'];
                    $this->data['sub_total'] = $this->data['total'];

                    $cuenta = $this->model->createCuenta($this->data);
                    if ($cuenta === "ok") {

                        $id_cuenta = $this->model->getCuenta($this->data['codigo']);
                        foreach ($productos as $value) {
                            if ($value['id_producto'] !== 0) {
                                $detalle_producto = [
                                    "cuenta_id" => $id_cuenta["id_cuenta"],
                                    "producto_id" => $value['id_producto'],
                                    "precio" => $value['precio'],
                                    "cantidad" => $value['cantidad'] ?? 1,
                                    "comision" => $value['comision'] ?? 0,
                                    "subtotal" => $value['subtotal'] ?? 0,
                                    
                                ];
                                $this->model->createDetalleCuenta($detalle_producto);
                            }
                        }


                    }
                }
                $this->model->updatePieza($this->data['pieza_id']);
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

    public function getServicios()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $servicios = $this->model->getServicios();
            if (!empty($servicios)) {
                http_response_code(200);
                return $this->response(response::estado200($servicios));
            }
            http_response_code(204);
            return $this->response(response::estado204());
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function getCuenta(string $codigo)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $servicios = $this->model->getCuenta($codigo);
            if (!empty($servicios)) {
                http_response_code(200);
                return $this->response(response::estado200($servicios));
            }
            http_response_code(204);
            return $this->response(response::estado204());
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }
    public function updateServicio(int $id_servicio)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $servicio = $this->model->updateServicio($id_servicio);
            if ($servicio === "ok") {
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
    public function updatePieza(int $id_pieza)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $pieza = $this->model->updatePieza($id_pieza);
            if ($pieza === "ok") {
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
    public function getDetalleCuenta(int $id_cuenta)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $detalle = $this->model->getDetalleCuenta($id_cuenta);
            if (!empty($detalle)) {
                http_response_code(200);
                return $this->response(response::estado200($detalle));
            }
            http_response_code(204);
            return $this->response(response::estado204());
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function updateCuenta()
    {
        if ($this->method !== 'POST') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $cuenta = $this->model->updateCuenta($this->data);
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
}