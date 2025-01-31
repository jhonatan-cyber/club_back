<?php

declare(strict_types=1);

namespace app\controllers;

use app\config\cache;
use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\rolModel;
use Exception;

class rol extends controller
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new rolModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }
        if ($_SESSION['rol'] !== "Administrador") {
            return $this->response(response::estado403());
        }
        try {
            $view = new view();
            session_regenerate_id(true);
            $template = !empty($_SESSION['activo']) ? 'rol' : 'auth';
            echo $view->render($template, 'index');
        } catch (Exception $e) {
            return $this->response(Response::estado404($e));
        }
    }

    public function getRoles()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());

        try {
            $cacheKey = 'roles_list';
            $roles = cache::get($cacheKey);

            if (!$roles) {
                $roles = $this->model->getRoles();
                cache::set($cacheKey, $roles, 0);
            }

            if (empty($roles)) {
                return $this->response(response::estado204('No se encontraron roles'));
            }

            return $this->response(response::estado200($roles));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function getRol(int $id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return $this->response(response::estado400('El id del rol debe ser un numero entero.'));
        }

        if ($id <= 0) {
            return $this->response(response::estado400('El id del rol debe ser un numero entero positivo.'));
        }

        try {
            $rol = $this->model->getRol($id);
            if (empty($rol)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($rol));
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }

    public function createRol()
    {
        if ($this->method !== 'POST') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        if ($this->data === null) {
            return $this->response(response::estado400('No se recibieron datos'));
        }

        if (empty($this->data['nombre'])) {
            return $this->response(Response::estado400('El nombre es requerido.'));
        }
        $this->data['nombre'] = ucwords($this->data['nombre']);
        try {
            if (empty($this->data['id_rol'])) {
                $rol = $this->model->createRol($this->data['nombre']);
            } else {
                $rol = $this->model->updateRol($this->data);
            }


            if ($rol === 'ok') {
                return $this->response(response::estado201());
            }

            if ($rol === 'existe') {
                return $this->response(response::estado409());
            }

            return $this->response(response::estado500('No se pudo crear el rol'));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function deleteRol(int $id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return $this->response(response::estado400('El id del rol debe ser un numero entero.'));
        }

        if ($id <= 0) {
            return $this->response(response::estado400('El id del rol debe ser un numero entero positivo.'));
        }
        try {
            $rol = $this->model->deleteRol($id);
            if ($rol === 'ok') {
                return $this->response(response::estado200('ok'));
            }
            return $this->response(response::estado500('No se pudo eliminar el rol'));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function highRol(int $id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return $this->response(response::estado400('El id del rol debe ser un numero entero.'));
        }

        if ($id <= 0) {
            return $this->response(response::estado400('El id del rol debe ser un numero entero positivo.'));
        }
        try {
            $rol = $this->model->highRol($id);
            if ($rol === 'ok') {
                return $this->response(response::estado200('ok'));
            }
            return $this->response(response::estado500('No se pudo activar el rol'));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}
