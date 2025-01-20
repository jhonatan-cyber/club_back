<?php

namespace app\controllers;


use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\asistenciaModel;
use Exception;

class asistencia extends controller
{
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new asistenciaModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        if ($_SESSION['rol_id'] !== "Administrador") {
            return $this->response(response::estado403());
        }

        try {
            $view = new view();
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('asistencia', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }

    public function getAsistencias()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $asistencias = $this->model->getAsistencias();
            if (!empty($asistencias)) {
                return $this->response(response::estado200($asistencias));
            }
            return $this->response(response::estado204());
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }

    public function getAsistencia($id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $asistencia = $this->model->getAsistencia($id);
            if (!empty($asistencia)) {
                return $this->response(response::estado200($asistencia));
            }
            return $this->response(response::estado204());
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }
}
