<?php

namespace app\controllers;

use app\config\controller;
use app\models\propinaModel;
use app\config\response;
use app\config\guard;
use app\config\view;
use app\config\cache;
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
            return $this->response(Response::estado405());
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
            $this->response(Response::estado404($e));
        }
    }
}