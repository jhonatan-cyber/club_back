<?php
namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\horaExtraModel;
use Exception;

class horaExtra extends controller
{
    private $model;
    private static $valdiate_number = '/^[0-9]+$/';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new horaExtraModel();
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
                echo $view->render('horaExtra', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            $this->response(Response::estado404($e));
        }
    }

    public function createHoraExtra()
    {
        if ($this->method !== 'POST') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $asistencia = $this->model->createHoraExtra($this->data);
            if ($asistencia !== 'ok') {
                return $this->response(response::estado500('No se pudo crear la hora extra'));
            }
            return $this->response(response::estado201());
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }

    public function getHorasExtras()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $asistencias = $this->model->getHorasExtras();
            if (!empty($asistencias)) {
                return $this->response(response::estado200($asistencias));
            }
            return $this->response(response::estado204());
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }

    public function getHoraExtra(int $id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $asistencia = $this->model->getHoraExtra($id);
            if (!empty($asistencia)) {
                return $this->response(response::estado200($asistencia));
            }
            return $this->response(response::estado204());
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }
}
