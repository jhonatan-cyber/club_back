<?php

namespace app\controllers;

use app\config\controller;
use app\models\clienteModel;
use app\config\response;
use app\config\guard;
use app\config\view;
use Exception;

class cliente extends controller
{
    private $model;
    private static $validar_numero = '/^[0-9]+$/';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new clienteModel();
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
                echo $view->render('cliente', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(Response::estado404($e));
        }
    }

    public function getClientes()
    {

        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $cliente = $this->model->getClientes();
            if (empty($cliente)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($cliente));
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }

    public function getCliente(int $id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $cliente = $this->model->getCliente($id);
            if (empty($cliente)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($cliente));
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }
    public function createCliente()
    {
        if ($this->method !== 'POST') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        if ($this->data === null) {
            http_response_code(400);
            return $this->response(response::estado400(['Datos JSON no válidos.']));
        }

        $required = ['run', 'nombre', 'apellido', 'telefono'];
        foreach ($required as $field) {
            if (empty($this->data[$field])) {
                http_response_code(400);
                return $this->response(response::estado400("El campo $field es obligatorio"));
            }
        }

        foreach ($required as $field) {
            if ($field !== 'telefono') {
                $this->data[$field] = ucwords($this->data[$field]);
            }
        }
        if (!preg_match(self::$validar_numero, $this->data['telefono'])) {
            http_response_code(400);
            return $this->response(Response::estado400('El campo telefono solo puede contener números'));
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            if (empty($this->data['id_cliente'])) {
                $cliente = $this->model->createCliente($this->data);
            } else {
                $cliente = $this->model->updateCliente($this->data);
            }

            if ($cliente == 'ok') {

                http_response_code(201);
                return $this->response(Response::estado201());
            }

            if ($cliente == 'existe') {
                http_response_code(409);
                return $this->response(Response::estado409('El cliente ya existe'));
            }
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }

    public function deleteCliente(int $id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            $res = $this->model->deleteCliente($id);
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