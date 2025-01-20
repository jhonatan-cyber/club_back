<?php

namespace app\controllers;

use app\config\cache;
use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\propinaModel;
use Exception;

class propina extends controller
{
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new propinaModel();
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
                echo $view->render('propina', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(response::estado404($e));
        }
    }

    public function getPropinas()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            $propinas = $this->model->getPropinas();
            if (!empty($propinas)) {
                return $this->response(response::estado200($propinas));
            }
            return $this->response(response::estado204());
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
    public function getPropina($id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            $propina = $this->model->getPropina($id);
            if (!empty($propina)) {
                return $this->response(response::estado200($propina));
            }
            return $this->response(response::estado204());
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}
