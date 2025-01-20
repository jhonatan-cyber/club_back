<?php

namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\servicioModel;
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
        if ($_SESSION['rol'] !== "Administrador" || $_SESSION['rol'] !== "Cajero") {
            return $this->response(response::estado403());
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
                return $this->response(response::estado200($servicio));
            }

            return $this->response(response::estado204());
        } catch (Exception $e) {
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
                return $this->response(response::estado200($servicios));
            }

            return $this->response(response::estado204());
        } catch (Exception $e) {
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
                return $this->response(response::estado200($servicios));
            }

            return $this->response(response::estado204());
        } catch (Exception $e) {
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
            if ($servicio === 'ok') {
                http_response_code(201);
                return $this->response(response::estado201());
            }

            return $this->response(response::estado500());
        } catch (Exception $e) {
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
            if ($pieza === 'ok') {
                http_response_code(201);
                return $this->response(response::estado201());
            }

            return $this->response(response::estado500());
        } catch (Exception $e) {
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
                return $this->response(response::estado200($detalle));
            }

            return $this->response(response::estado204());
        } catch (Exception $e) {
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
            if ($cuenta === 'ok') {
                http_response_code(201);
                return $this->response(response::estado201());
            }

            return $this->response(response::estado500());
        } catch (Exception $e) {
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
            // Validate required fields
            $requiredFields = ['usuario_id', 'pieza_id', 'precio_servicio'];
            foreach ($requiredFields as $field) {
                if (!isset($this->data[$field])) {
                    return $this->response(response::estado400("El campo $field es requerido"));
                }
            }

            $productos = isset($this->data['productos']) ? $this->data['productos'] : [];
            if (!empty($productos) && !is_array($productos)) {
                return $this->response(response::estado400('El campo productos debe ser un array'));
            }

            $chicas = is_array($this->data['usuario_id']) ? $this->data['usuario_id'] : [$this->data['usuario_id']];
            $servicio = $this->model->createServicio($this->data);

            if ($servicio !== 'ok') {
                return $this->response(response::estado500('Error al crear el servicio'));
            }
            if ($servicio === 'ok') {
                $id_servicio = $this->model->getServicioCodigo($this->data['codigo']);

                foreach ($chicas as $value) {
                    if ($value !== 0) {
                        $detalle = [
                            'servicio_id' => $id_servicio['id_servicio'],
                            'usuario_id' => $value
                        ];
                        $detalle_servicio = $this->model->createDetalleServicio($detalle);

                        if ($detalle_servicio !== 'ok') {
                            return $this->response(response::estado500('Error al crear el detalle del servicio.'));
                        }
                    }
                }

                $comision = [
                    'servicio_id' => $id_servicio['id_servicio'],
                    'monto' => $this->data['precio_servicio'] ?? 0
                ];

                $comision_s = $this->model->createComision($comision);

                if ($comision_s !== 'ok') {
                    return $this->response(response::estado500('Error al crear la comisión.'));
                }

                $id_comision = $this->model->getLastComision();

                if (!empty($chicas)) {
                    $numUsuarios = count($chicas);
                    $comisionPorUsuario = $this->data['precio_servicio'] / $numUsuarios;
                    foreach ($chicas as $usuario_id) {
                        if ($usuario_id !== 0) {
                            $detalle_comision = [
                                'comision_id' => $id_comision['comision_id'],
                                'chica_id' => $usuario_id,
                                'comision' => $comisionPorUsuario
                            ];
                            $detalle_comi = $this->model->cretaeDetalleComision($detalle_comision);
                            if ($detalle_comi !== 'ok') {
                                return $this->response(response::estado500('Error al crear el detalle de la comisión.'));
                            }
                        }
                    }
                    foreach ($chicas as $usuario_id) {
                        if ($usuario_id !== 0) {
                            $anticipos = $this->model->getAnticipoUsuario($usuario_id);
                            if (!empty($anticipos) && is_array($anticipos)) {
                                foreach ($anticipos as $anticipo) {
                                    if (isset($anticipo['id_anticipo'])) {
                                        if ($comisionPorUsuario > $anticipo['monto']) {
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
                }

                if (!empty($productos)) {
                    $detalle_cuenta = [
                        'codigo' => $this->data['codigo'],
                        'cliente_id' => $this->data['cliente_id'],
                        'servicio_id' => $id_servicio['id_servicio'],
                        'total_comision' => $this->data['total_comision'],
                        'total' => $this->data['total']
                    ];
                    $cuenta = $this->model->createCuenta($detalle_cuenta);

                    if ($cuenta !== 'ok') {
                        return $this->response(response::estado500('Error al crear la cuenta'));
                    }

                    if ($cuenta === 'ok') {
                        $id_cuenta = $this->model->getCuenta($this->data['codigo']);
                        foreach ($productos as $value) {
                            if ($value['id_producto'] !== 0) {
                                $detalle_producto = [
                                    'cuenta_id' => $id_cuenta['id_cuenta'],
                                    'producto_id' => $value['id_producto'],
                                    'precio' => $value['precio'],
                                    'cantidad' => $value['cantidad'] ?? 1,
                                    'comision' => $value['comision'] ?? 0,
                                    'subtotal' => $value['subtotal'] ?? 0,
                                ];
                                $deta_cuenta = $this->model->createDetalleCuenta($detalle_producto);
                                if ($deta_cuenta !== 'ok') {
                                    return $this->response(response::estado500('Error al crear el detalle de la cuenta'));
                                }
                            }
                        }
                    }
                }
                $pieza = $this->model->updatePieza($this->data['pieza_id']);
                if ($pieza !== 'ok') {
                    return $this->response(response::estado500('Error al actualizar la pieza'));
                }
                return $this->response(response::estado201());
            }
        } catch (Exception $e) {
            return $this->response(response::estado500($e->getMessage()));
        }
    }

    public function getCuentaServicio(int $servicio_id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $servicios = $this->model->getCuentaServicio($servicio_id);
            if (!empty($servicios)) {
                return $this->response(response::estado200($servicios));
            }

            return $this->response(response::estado204());
        } catch (Exception $e) {
            $this->response(response::estado500($e));
        }
    }
    public function getServicioUsuario(int $id_usuario)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $servicios = $this->model->getServicioUsuario($id_usuario);
   
            if (!empty($servicios)) {
                return $this->response(response::estado200($servicios));
            }

            return $this->response(response::estado204());
        } catch (Exception $e) {
            $this->response(response::estado500($e));
        }
    }
}
