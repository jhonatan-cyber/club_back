<?php

namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;
use app\Config\view;
use app\models\loginModel;
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

    public function index()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        $view = new view();

        try {
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('home', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            $this->response(response::estado404($e));
        }
    }

    public function login()
    {
        if ($this->method !== 'POST') {
            return $this->response(response::estado405());
        }

        $datos = ['correo', 'password'];
        $data = $this->data;
        foreach ($datos as $field) {
            if (!isset($data[$field])) {
                return $this->response(response::estado400('Falta el campo ' . $field));
            }
        }
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            return $this->response(response::estado400('El correo no es valido'));
        }
        try {
            $res = $this->model->login($data);

            if ($res['estado'] == 'error') {
                return $this->response(response::estado400());
            }

            if ($res['estado'] === 'ok') {
                if ($res['data']['estado'] === 0) {
                    return $this->response(response::estado400('El usuario no esta activo'));
                }
                $_SESSION['id_usuario'] = $res['data']['id_usuario'];
                if ($res['data']['rol'] === 'Administrador' || $res['data']['rol'] === 'Cajero') {
                    if ($res['data']['rol'] === 'Cajero') {
                        $login = $this->model->createLogin($_SESSION['id_usuario']);
                        if ($login !== 'ok') {
                            return $this->response(response::estado500('No se pudo crear el login'));
                        }
                        $asistencias = $this->model->getAsistenciaUsuario($_SESSION['id_usuario']);

                        if ($asistencias > 0) {
                            return $this->response(response::estado200('Ya registraste tu asistencia hoy'));
                        }

                        $asistencia = $this->model->createAsistencia($_SESSION['id_usuario']);
                        if ($asistencia !== 'ok') {
                            return $this->response(response::estado500('No se pudo crear la asistencia'));
                        }
                    }
                    if ($res['data']['estado'] === 0) {
                        $_SESSION['activo'] = false;
                    } else {
                        $_SESSION['activo'] = true;
                    }
                    return $this->response($res);
                }

                if ($res['data']['rol'] !== 'Administrador' && $res['data']['rol'] !== 'Cajero') {
                    return $this->response($res);
                }
            }
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function validarCodigo($codigo)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        try {
            $res = $this->model->validarCodigo($codigo);
            if (!empty($res)) {
                if (!empty($_SESSION['id_usuario'])) {
                    $login = $this->model->createLogin($_SESSION['id_usuario']);
                    $_SESSION['activo'] = true;
                    if ($login === 'activo') {
                        return $this->response(response::estado200($res));
                    }
                    if ($login !== 'ok') {
                        return $this->response(response::estado500('No se pudo crear el login'));
                    }
                }
                return $this->response(response::estado200($res));
            }
            return $this->response(response::estado204('No se pudo validar el codigo'));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function createAsistencia($usuario_id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        try {
            date_default_timezone_set('America/La_Paz');
            $hora_actual = date('H:i');
            $hora_inicio = '00:00';
            $hora_limite = '23:00';

            $timestamp_actual = strtotime($hora_actual);
            $timestamp_inicio = strtotime($hora_inicio);
            $timestamp_limite = strtotime($hora_limite);

            if ($timestamp_actual < $timestamp_inicio) {
                return $this->response(response::estado200('Solo se puede registrar asistencia después de las 8:00 PM'));
            }

            if ($timestamp_actual > $timestamp_limite) {
                return $this->response(response::estado200('No se puede registrar asistencia después de las 11:00 PM'));
            }

            $asistencias = $this->model->getAsistenciaUsuario($usuario_id);

            if ($asistencias > 0) {
                return $this->response(response::estado200('Ya registraste tu asistencia hoy'));
            }

            $asistencia = $this->model->createAsistencia($usuario_id);
            if ($asistencia !== 'ok') {
                return $this->response(response::estado500('No se pudo crear la asistencia'));
            }

            return $this->response(response::estado201());
        } catch (Exception $e) {
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
            $this->model->updateLogin($_SESSION['id_usuario']);
            session_destroy();
        } else {
            $this->model->updateLogin($_SESSION['id_usuario']);
        }
        http_response_code(200);
        return $this->response(response::estado200('ok'));
    }

    public function createCodigo()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        try {
            $codigo = mt_rand(1000, 9999);
            $res = $this->model->createCodigo($codigo);
            if ($res === 'ok') {
                return $this->response(response::estado201());
            }
            return $this->response(response::estado500('No se pudo crear el codigo'));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function updateCodigo()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        try {
            $res = $this->model->updateCodigo();
            if ($res === 'ok') {
                return $this->response(response::estado201());
            }
            return $this->response(response::estado500('No se pudo actualizar el codigo'));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}
