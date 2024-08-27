<?php

namespace app\controllers;

use app\config\controller;
use app\models\loginModel;
use app\config\response;
use app\config\guard;
use Exception;

class login extends controller
{
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new loginModel();
    }

    public function login()
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            return $this->response(response::estado400('Error al decodificar JSON: ' . json_last_error_msg()));
        }
        $datos = ['correo', 'password'];
        $data= $this->data;
        foreach ($datos as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                return $this->response(response::estado400('Falta el campo ' . $field));
            }
        }
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            return $this->response(response::estado400('El correo no es valido'));
        }
        try {
            $res = $this->model->login($data);
            if ($res['estado'] === "ok") {
                $_SESSION['id_usuario'] = $res['data']['id_usuario'];
                $_SESSION['token'] = $res['data']['token'];
                $_SESSION['foto'] = $res['data']['foto'];
                if ($res['data']['estado'] === 0) {
                    $_SESSION['activo'] = false;
                } else {
                    $_SESSION['activo'] = true;
                }
            }
            http_response_code(200);
            return $this->response(response::estado200($res));
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }
    public function logout()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
          guard::validateToken($this->header, guard::secretKey()); 
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
            http_response_code(200);
            return $this->response(response::estado200('ok'));
        }
        http_response_code(200);
        return $this->response(response::estado200('ok'));
    }
}
