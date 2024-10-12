<?php

namespace app\controllers;

use app\config\controller;
use app\models\contratoModel;
use app\config\response;
use app\config\guard;
use app\config\view;
use Exception;

class contrato extends controller
{
    private $model;
    private static $valdiate_number = '/^[0-9]+$/';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new contratoModel();
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
                echo $view->render('contrato', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(Response::estado404($e));
        }
    }

    public function getContratos()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $contratos = $this->model->getContratos();
            if (empty($contratos)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($contratos));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function getContrato(int $id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $contrato = $this->model->getContrato($id);
            if (empty($contrato)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($contrato));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500($e));
        }
    }

    public function createContrato()
    {
        if ($this->method !== 'POST') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        if ($this->data === null) {
            http_response_code(400);
            return $this->response(response::estado400(['Datos JSON no válidos.']));
        }

        $requiredFields = ['usuario_id', 'sueldo', 'fonasa'];
        foreach ($requiredFields as $field) {
            if (empty($this->data[$field])) {
                http_response_code(400);
                return $this->response(response::estado400("El campo $field es obligatorio"));
            }
        }
        guard::validateToken($this->header, guard::secretKey());

        if (!preg_match(self::$valdiate_number, subject: $this->data['sueldo'])) {
            return $this->response(response::estado400('El campo sueldo solo puede contener números'));
        }
        try {
            if (empty($this->data['id_contrato'])) {
                $contrato = $this->model->createContrato($this->data);
            } else {
                $contrato = $this->model->createContrato($this->data[]);
            }

            if ($contrato == 'ok') {
                http_response_code(201);
                return $this->response(response::estado201());
            }
            http_response_code(500);
            return $this->response(response::estado500());
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }

    public function deleteContrato(int $id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $res = $this->model->deleteContrato($id);
            if ($res === "ok") {
                http_response_code(200);
                return $this->response(response::estado200('ok'));
            }
            http_response_code(500);
            return $this->response(response::estado500());
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }
}