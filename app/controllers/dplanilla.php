<?php

namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\dplanillaModel;
use app\models\pedidoModel;
use Exception;

class dplanilla extends controller
{
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new dplanillaModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }
        if ($_SESSION['rol'] !== "Administrador") {
            return $this->response(response::estado403());
        }
        try {
            $view = new view();
            session_regenerate_id(true);

            if (!empty($_SESSION['activo'])) {
                echo $view->render('dplanilla', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            $this->response(Response::estado404($e));
        }
    }

    public function getPlanillaFecha(string $fecha)
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $planillas = $this->model->getPlanillasFecha($fecha);
            if (empty($planillas['planilla'])) {
                $planillas = "No hay planillas para la fecha $fecha";
            }
            return $this->response(response::estado200($planillas));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}
