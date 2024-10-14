<?php

namespace app\controllers;

use app\config\controller;
use app\models\comisionModel;
use app\config\response;
use app\config\guard;
use app\config\view;
use Exception;

class comision extends controller
{
    private $model;
    private static $valdiate_number = '/^[0-9]+$/';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new comisionModel();
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
                echo $view->render('comision', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(Response::estado404($e));
        }
    }
    public function getComisionUsuario(){
        if($this->method !== 'GET'){
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $usuario_id = $_SESSION['usuario_id'];
            $comision = $this->model->getComisionUsuario($usuario_id);
            if(!empty($comision)){
                http_response_code(200);
                return $this->response(response::estado200($comision));
            }
            http_response_code(204);
            return $this->response(response::estado204());
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }
    public function getComisiones(){
        if($this->method !== 'GET'){
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $comision = $this->model->getComisiones();
            if(!empty($comision)){
                http_response_code(200);
                return $this->response(response::estado200($comision));
            }
            http_response_code(204);
            return $this->response(response::estado204());
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }
}