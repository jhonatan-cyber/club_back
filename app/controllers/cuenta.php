<?php

namespace app\controllers;

use app\config\cache;
use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\cuentaModel;
use app\models\pedidoModel;
use Exception;

class cuenta extends controller
{
    private $cuenta;
    private $pedido;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->cuenta = new cuentaModel();
        $this->pedido = new pedidoModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }
        if ($_SESSION['rol'] !== "Administrador" && $_SESSION['rol'] !== "Cajero") {
            return $this->response(response::estado403());
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
            $result = $this->cuenta->getCuentas();
            if (!empty($result)) {
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

        $productos = $this->data['productos'] ?? [];
        $chicas = $this->data['usuario_id'] ?? [];

        if (empty($productos)) {
            return $this->response(response::estado400('No hay productos para procesar'));
        }

        if (!is_numeric($this->data['cliente_id'])) {
            return $this->response(Response::estado400('Cliente inválido'));
        }

        if (!is_numeric($this->data['total'])) {
            return $this->response(Response::estado400('Total inválido'));
        }

        if (!is_numeric($this->data['total_comision'])) {
            return $this->response(Response::estado400('Total de comisión inválido'));
        }
        $cuenta_cliente = $this->cuenta->getCuentaCliente($this->data['cliente_id']);

        try {

               $cuenta_cliente = $this->cuenta->getCuentaCliente($this->data['cliente_id']);
            $id_cuenta = null;
            $this->data['servicio_id'] = $this->data['servicio_id'] ?? 0;
            $this->data['pieza_id'] = $this->data['pieza_id'] ?? 0;
            $this->data['id_pedido'] = $this->data['id_pedido'] ?? 0;

            if (!empty($cuenta_cliente)) {

                $id_cuenta = $cuenta_cliente['id_cuenta'];

                $d_cuenta_update = [
                    'id_cuenta' => (int)$id_cuenta,
                    'total' => (int)$this->data['total'],
                    'total_comision' => (int)$this->data['total_comision'],
                ];

                $update_cuenta = $this->cuenta->updateCuenta($d_cuenta_update);

                if ($update_cuenta !== 'ok') {
                    return $this->response(response::estado500('Error al actualizar cuenta'));
                }
            } else {

                $d_cuenta = [
                    'cliente_id' => (int)$this->data['cliente_id'],
                    'total' => (int)$this->data['total'],
                    'total_comision' => (int)$this->data['total_comision'],
                    'codigo' => (string)$this->data['codigo'],

                ];

                $cuenta = $this->cuenta->createCuenta($d_cuenta);

                if ($cuenta !== 'ok') {
                    return $this->response(response::estado500('Error al crear cuenta'));
                }

                $lastCuenta = $this->cuenta->getLastCuenta();
                if (!$lastCuenta || !isset($lastCuenta['id_cuenta'])) {
                    return $this->response(response::estado500('Error al obtener cuenta'));
                }

                $id_cuenta = $lastCuenta['id_cuenta'];
            }

            foreach ($productos as $value) {
                $d_detalle_cuenta = [
                    'cuenta_id' => (int)$id_cuenta,
                    'producto_id' => (int)$value['id_producto'],
                    'precio' => (int)$value['precio'],
                    'cantidad' => (int)$value['cantidad'],
                    'subtotal' => (int)$value['subtotal'],
                    'comision' => (int)$value['comision'],
                    'pieza_id' => (int)$this->data['pieza_id'],
                    'pedido_id' => (int)$this->data['id_pedido'],
                    'servicio_id' => (int)$this->data['servicio_id']
                ];

                $detalle_cuenta = $this->cuenta->createDetalleCuenta($d_detalle_cuenta);

                if ($detalle_cuenta !== 'ok') {
                    return $this->response(response::estado500('Error al crear detalle de cuenta'));
                }

                if (!empty($chicas)) {
                    $usuarios = !is_array($chicas) ? [$chicas] : $chicas;
                    foreach ($usuarios as $usuario_id) {
                        if ($usuario_id > 0) {
                            $d_cuenta_usuario = [
                                'cuenta_id' => $id_cuenta,
                                'usuario_id' => $usuario_id
                            ];
                            $cuenta_usuario = $this->cuenta->createCuentaUsuario($d_cuenta_usuario);
                            
                            if ($cuenta_usuario !== 'ok') {
                                return $this->response(response::estado500('Error al crear cuenta usuario'));
                            }
                        }
                    }
                }
            }

            if (!empty($this->data['id_pedido'])) {
                $this->pedido->updatePedido($this->data['id_pedido']);
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
            $cuenta = $this->cuenta->getDetalleCuentas($cuenta_id);
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
            $cuenta = $this->cuenta->cobrarCuenta($this->data);
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
                    $cuenta_d = $this->cuenta->createDetalleCuenta($detalle);
                    if ($cuenta_d === 'ok') {
                        $datos = [
                            'id_cuenta' => $this->data['cuenta_id'],
                            'sub_total' => $producto['subtotal'],
                            'total_comision' => $producto['comision'],
                        ];

                        $cuenta = $this->cuenta->updateCuenta($datos);
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

}
