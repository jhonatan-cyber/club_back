<?php

namespace app\controllers;

use app\config\cache;
use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\cuentaModel;
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
            $result = $this->model->getCuentas();
            if(!empty($result)){
                return $this->response(response::estado200($result));
            }
            return $this->response(response::estado204());
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function createCuenta()
    {
        if ($this->method !== 'POST') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());

        $this->data['pedido_id'] = $this->data['id_pedido'];
        $productos = $this->data['productos'] ?? [];

        if (empty($productos)) {
            return $this->response(response::estado400('No hay productos para procesar'));
        }

        try {
            $cuenta_cliente = $this->model->getCuentaCliente($this->data['cliente_id']);
            $id_cuenta = null;

            if (!empty($cuenta_cliente)) {
                $id_cuenta = $cuenta_cliente['id_cuenta'];
            } else {
                $cuenta = $this->model->createCuenta($this->data);
                if ($cuenta !== 'ok') {
                    return $this->response(response::estado500('Error al crear cuenta'));
                }
                $id_cuenta = $this->model->getLastCuenta()['id_cuenta'];
            }

            foreach ($productos as $value) {
                $detalle = [
                    'cuenta_id' => $id_cuenta,
                    'producto_id' => $value['id_producto'],
                    'precio' => $value['precio'],
                    'cantidad' => $value['cantidad'],
                    'subtotal' => $value['subtotal'],
                    'comision' => $value['comision']
                ];
                $cuenta_d = $this->model->createDetalleCuenta($detalle);
                if ($cuenta_d !== 'ok') {
                    return $this->response(response::estado500('Error al crear detalle de cuenta'));
                }

                $datos = [
                    'id_cuenta' => $id_cuenta,
                    'sub_total' => $value['subtotal'],
                    'total_comision' => $value['comision']
                ];
                $update_cuenta = $this->model->updateCuenta($datos);
                if ($update_cuenta !== 'ok') {
                    return $this->response(response::estado500('Error al actualizar cuenta'));
                }
            }

            if (!empty($this->data['id_pedido'])) {
                $this->model->updatePedido($this->data['id_pedido']);
            }

            return $this->response(response::estado201());
        } catch (Exception $e) {
            return $this->response(response::estado500($e->getMessage()));
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
                return $this->response(response::estado204());
            }

            return $this->response(response::estado200($cuenta));
        } catch (Exception $e) {
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
            if ($cuenta === 'ok') {
                return $this->response(response::estado201());
            }

            return $this->response(response::estado500());
        } catch (Exception $e) {
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
                        'cuenta_id' => $this->data['cuenta_id'] ?? null,
                        'producto_id' => $producto['id_producto'] ?? null,
                        'precio' => $producto['precio'] ?? 0,
                        'cantidad' => $producto['cantidad'] ?? 0,
                        'comision' => $producto['comision'] ?? 0,
                        'subtotal' => $producto['subtotal'] ?? 0,
                    ];
                    $cuenta_d = $this->model->createDetalleCuenta($detalle);
                    if ($cuenta_d === 'ok') {
                        $datos = [
                            'id_cuenta' => $this->data['cuenta_id'],
                            'sub_total' => $producto['subtotal'],
                            'total_comision' => $producto['comision'],
                        ];

                        $cuenta = $this->model->updateCuenta($datos);
                    }
                }
                if ($cuenta === 'ok') {
                    return $this->response(response::estado201());
                }

                return $this->response(response::estado500());
            }
        } catch (Exception $e) {
            $this->response(response::estado500($e));
        }
    }

    public function clearCuentasCache()
    {
        if ($this->method !== 'POST') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            cache::delete('cuentas_list');
            return $this->response(response::estado200('Cache limpiada correctamente'));
        } catch (Exception $e) {
            error_log("Error clearing cache: " . $e->getMessage());
            return $this->response(response::estado500($e));
        }
    }
}
