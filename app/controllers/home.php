<?php

namespace app\controllers;

use app\config\controller;
use app\models\homeModel;
use app\config\response;
use app\config\guard;
use app\config\view;
use Exception;

class home extends controller
{
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new homeModel();
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
                echo $view->render('home', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }
    public function getCodigo(){
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $codigo = $this->model->getCodigo();
            if (empty($codigo)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($codigo));
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }

}