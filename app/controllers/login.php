<?php

namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
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

            if ($res['estado'] === 'error') {
                return $this->response(response::estado500('No se pudo iniciar sesion'));
            }

            if ($res['estado'] === 'ok') {
                if ($res['data']['estado'] === 0) {
                    return $this->response(response::estado400('El usuario no esta activo, contacte al administrador'));
                } 

                $_SESSION['id_usuario'] = $res['data']['id_usuario'];
                $_SESSION['rol'] = $res['data']['rol'];

                if ($res['data']['rol'] === 'Administrador' || $res['data']['rol'] === 'Cajero') {
                    if ($res['data']['rol'] === 'Cajero') {
                        $login = $this->model->createLogin($_SESSION['id_usuario']);
                        if ($login === 'error') {
                            return $this->response(response::estado500('No se pudo crear el login'));
                        }

                        $asistencia = $this->model->createAsistencia($_SESSION['id_usuario']);
                        if ($asistencia === 'error') {
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
            $_SESSION=[];
            return $this->response(response::estado500($e));
        }
    }

    public function validarCodigo($codigo)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());
        $token = guard::getDataJwt();

        $token = json_encode($token);
        $datosToken = json_decode($token, true);
        $tokenData = $datosToken['token'];
        $usuario_id = $tokenData['id_usuario'];

        try {
            $validadacion = $this->model->validarCodigo($codigo);

            if (empty($validadacion)) {
                return $this->response(response::estado204('No se pudo validar el código'));
            }

            if (!isset($usuario_id)) {
                return $this->response(response::estado401('Sesión no iniciada'));
            }

            if (empty($_SESSION['activo'])) {
                $login = $this->model->createLogin($usuario_id);

                if ($login === 'error') {
                    return $this->response(response::estado500('No se pudo crear el login'));
                }

                $_SESSION['activo'] = true;
            }

            date_default_timezone_set('America/La_Paz');
            $hora_actual = date('H:i');
            $hora_inicio = '00:00';
            $hora_limite = '23:00';

            if ($hora_actual < $hora_inicio) {
                return $this->response(response::estado200('Solo se puede registrar asistencia después de las 8:00 PM'));
            }
            if ($hora_actual > $hora_limite) {
                return $this->response(response::estado200('No se puede registrar asistencia después de las 11:00 PM'));
            }

            $asistencia = $this->model->createAsistencia($usuario_id);

            if ($asistencia === 'existe') {
                return $this->response(response::estado200('Ya registraste tu asistencia hoy'));
            }
            if ($asistencia === 'error') {
                $_SESSION=[];
                return $this->response(response::estado500('No se pudo crear la asistencia'));
            }
            if ($asistencia === 'ok') {
                return $this->response(response::estado200('Codigo validado y asistencia registrada'));
            }
        } catch (Exception $e) {
            $_SESSION=[];
            return $this->response(response::estado500($e));
        }
    }

    public function logout()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());

        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['id_usuario'])) {
            $this->model->updateLogin($_SESSION['id_usuario']);
            $_SESSION = [];

            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params['path'], $params['domain'],
                    $params['secure'], $params['httponly']);
            }

            session_destroy();
        }

        return $this->response(response::estado200('Sesión cerrada correctamente'));
    }
}
