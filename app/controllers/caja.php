<?php

namespace app\controllers;

use app\config\cache;
use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\cajaModel;
use Exception;

class caja extends controller
{
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new cajaModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        if ($_SESSION['rol'] !== "Administrador" && $_SESSION['rol'] !== "Cajero") {
            return $this->response(response::estado403());
        }
        try {
            $view = new view();
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('caja', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }

    public function createCaja()
    {
        if ($this->method !== 'POST') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        if ($this->data === null) {
            return $this->response(response::estado400('No se recibieron datos'));
        }
        $this->data['usuario_id_apertura'] = $_SESSION['id_usuario'];
        try {
            $res = $this->model->createCaja($this->data);

            if ($res !== 'ok') {
                return $this->response(response::estado500('No se pudo crear la caja'));
            }
            return $this->response(response::estado201());
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function getCajas()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $cajas = $this->model->getCajas();
            if (empty($cajas)) {
                return $this->response(response::estado204());
            }
            return $this->response(response::estado200($cajas));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function cerrarCaja(int $id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return $this->response(response::estado400('El id de la caja debe ser un numero entero.'));
        }

        if ($id <= 0) {
            return $this->response(response::estado400('El id de la caja debe ser un numero entero positivo.'));
        }
        try {
            $detalle = [
                'id_caja' => $id,
                'usuario_id_cierre' => $_SESSION['id_usuario']
            ];
            $res = $this->model->cerrarCaja($detalle);
            if ($res !== 'ok') {
                return $this->response(response::estado500('No se pudo cerrar la caja.'));
            }
            return $this->response(response::estado201('Caja cerrada exitosamente.'));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
    public function getDetalleCaja(int $id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return $this->response(response::estado400('El id de la caja debe ser un numero entero.'));
        }

        if ($id <= 0) {
            return $this->response(response::estado400('El id de la caja debe ser un numero entero positivo.'));
        }
        try {
            $detalle = $this->model->getDetalleCaja($id);
            if (empty($detalle)) {
                return $this->response(response::estado204());
            }
            return $this->response(response::estado200($detalle));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}
