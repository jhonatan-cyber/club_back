<?php

namespace app\controllers;

use app\config\controller;
use app\models\piezaModel;
use app\config\response;
use app\config\guard;
use app\config\view;
use Exception;

class pieza extends controller
{
    private $model;
    private static $validar_numero = '/^[0-9]+$/';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new piezaModel();
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
                echo $view->render('pieza', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(Response::estado404($e));
        }
    }

    public function createPieza()
    {
        if ($this->method !== 'POST') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        if ($this->data === null) {
            http_response_code(400);
            return $this->response(response::estado400('Datos JSON no válidos.'));
        }
        $requiredFields = ['nombre', 'precio'];
        foreach ($requiredFields as $field) {
            if (empty($this->data[$field])) {
                http_response_code(400);
                return $this->response(response::estado400("El campo $field es obligatorio"));
            }
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            if (empty($this->data['id_pieza'])) {
                $pieza = $this->model->createPieza($this->data);
            } else {
                $pieza = $this->model->updatePieza($this->data);
            }
            if ($pieza == 'ok') {
                http_response_code(201);
                return $this->response(response::estado201());
            }
            if ($pieza == 'existe') {
                http_response_code(409);
                return $this->response(response::estado409());
            }
            http_response_code(500);
            return $this->response(response::estado500());

        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));

        }
    }

    public function getPiezas()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $piezas = $this->model->getPiezas();
            if (empty($piezas)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($piezas));
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }

    public function getPieza(int $id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $pieza = $this->model->getPieza($id);
            if (empty($pieza)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($pieza));
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }

    public function deletePieza(int $id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $res = $this->model->deletePieza($id);
            if ($res === "ok") {
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
    public function getPiezasLibres()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $piezas = $this->model->getPiezasLibres();
            if (empty($piezas)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($piezas));
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }
}