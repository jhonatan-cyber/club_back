<?php
namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\devolucionModel;
use Exception;

class devolucion extends controller
{
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new devolucionModel();
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
                echo $view->render('devolucion', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            $this->response(Response::estado404($e));
        }
    }

    public function getAllServicios()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            $servicios = $this->model->getAllServicios();
            if (empty($servicios)) {
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($servicios));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function createDevolucion()
    {
        if ($this->method !== 'POST') {
            return $this->response(Response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            if (
                !is_numeric($this->data['servicio_id']) ||
                !is_numeric($this->data['pieza_id']) ||
                !is_numeric($this->data['cliente_id']) ||
                !is_numeric($this->data['total'])
            ) {
                return $this->response(response::estado400());
            }

            $usuario_id = $this->data['usuario_id'];
            $devolucion = $this->model->createDevolucion($this->data);

            if ($devolucion === 'ok') {
                $id_devolucion = $this->model->getLastDevolucion();

                if (!empty($usuario_id)) {
                    $usuarios = is_array($usuario_id) ? count($usuario_id) : 1;
                    $montoUsuario = $this->data['total'] / $usuarios;

                    if (!array($usuario_id)) {
                        if ($usuario_id > 0) {
                            $detalleDevolucion = [
                                'devolucion_id' => $id_devolucion['id_devolucion'],
                                'usuario_id' => $usuario_id,
                                'monto' => $montoUsuario
                            ];

                            $this->model->createDetalleDevolucion($detalleDevolucion);
                        }
                    } else {
                        foreach ($usuario_id as $key => $value) {
                            $detalleDevolucion = [
                                'devolucion_id' => $id_devolucion['id_devolucion'],
                                'usuario_id' => $value,
                                'monto' => $montoUsuario
                            ];

                            $this->model->createDetalleDevolucion($detalleDevolucion);
                        }
                    }

                    $servicio = $this->model->updateServicio($this->data['servicio_id']);

                    if ($servicio !== 'ok') {
                        return $this->response(response::estado500(' Error al actualizar el servicio '));
                    }

                    $comision = $this->model->updateComision($this->data['servicio_id']);

                    if ($comision !== 'ok') {
                        return $this->response(response::estado500('Error al actualizar la comision '));
                    }
                    $id_comision = $this->model->getComisionServicio($this->data['servicio_id']);

                    $detalle_comision = $this->model->updateDetalleComision($id_comision['id_comision']);

                    if ($detalle_comision !== 'ok') {
                        return $this->response(response::estado500('Error al actualizar el detalle de la comision '));
                    }
                }

                return $this->response(response::estado201());
            }
            return $this->response(response::estado500());
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function getDevolucion(int $id_devolucion)
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            $devolucion = $this->model->getDevolucion($id_devolucion);
            if (empty($devolucion)) {
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($devolucion));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function getDevoluciones()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            $devoluciones = $this->model->getDevoluciones();
            if (empty($devoluciones)) {
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($devoluciones));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function createDevolucionVenta()
    {
        if ($this->method !== 'POST') {
            return $this->response(Response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $devolucion = $this->model->createDevolucionVenta($this->data);

            if ($devolucion !== 'ok') {
                return $this->response(response::estado500($devolucion));
            }
            return $this->response(response::estado201());
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}
