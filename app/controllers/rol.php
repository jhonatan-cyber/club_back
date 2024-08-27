<?php

namespace app\controllers;

use app\config\controller;
use app\models\rolModel;
use app\config\response;
use app\config\guard;
use Exception;

class rol extends controller
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new rolModel();
    }
   
    public function getRoles()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
     /*    guard::validateToken($this->header, guard::secretKey()); */
        try {
            $roles = $this->model->getRoles();
            if (empty($roles)) {
                return $this->response(response::estado204());
            }
            return $this->response(response::estado200($roles));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
    public function getRol($id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
       /*  guard::validateToken($this->header, guard::secretKey()); */
        try {
            $rol = $this->model->getRol($id);
            if (empty($rol)) {
                return $this->response(response::estado204());
            }
            return $this->response(response::estado200($rol));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function createRol()
    {
        if ($this->method !== 'POST') {
            return $this->response(response::estado405());
        }
/*         guard::validateToken($this->header, guard::secretKey()); */
        if ($this->data === null) {
            return $this->response(Response::estado400('Datos JSON no válidos.'));
        }

        if (empty($this->data['nombre'])) {
            return $this->response(Response::estado400('El nombre es requerido.'));
        }
        $this->data['nombre'] = ucwords($this->data['nombre']);
        try {

            if (empty($this->data['id_rol'])) {
                $rol = $this->model->createRol($this->data['nombre']);
            } else {
                $rol = $this->model->updateRol($this->data[]);
            }
            switch ($rol) {
                case "ok":
                    return $this->response(response::estado201());
                case "existe":
                    return $this->response(response::estado409());
                case "error":
                    return $this->response(response::estado500());
            }
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
    public function deleteRol($id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
       /*  guard::validateToken($this->header, guard::secretKey()); */
        try {
            $rol = $this->model->deleteRol($id);
            if ($rol === "ok") {
                return $this->response(response::estado200('ok'));
            } else {
                return $this->response(response::estado500());
            }
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}
