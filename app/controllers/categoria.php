<?php

namespace app\controllers;

use app\config\controller;
use app\models\categoriaModel;
use app\config\response;
use app\config\guard;
use app\config\view;
use app\config\cache;
use Exception;

class categoria extends controller
{
    private $model;

    public function __construct()
    {
         if (session_status() === PHP_SESSION_NONE) {
            session_start();
        } 
        parent::__construct();
        $this->model = new categoriaModel();
    }
    public function index()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        try {
            $view = new view();
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) { 
            echo $view->render('categoria', 'index');
            } else {
                echo $view->render('auth', 'index');
            } 
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }

    public function getCategorias()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());

        try {
            $cacheKey = 'categorias_list';
            $categorias = cache::get($cacheKey);

            if (!$categorias) {
                $categorias = $this->model->getCategorias();
                cache::set($cacheKey, $categorias, 600);
            }

            if (empty($categorias)) {
                return $this->response(response::estado204('No se encontraron categorías'));
            }

            return $this->response(response::estado200($categorias));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function getCategoria($id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $categoria = $this->model->getCategoria($id);
            if (empty($categoria)) {
                return $this->response(response::estado204());
            }
            return $this->response(response::estado200($categoria));
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }
    public function createCategoria()
    {
        if ($this->method !== 'POST') {
            return $this->response(response::estado405());
        }

        if ($this->data === null) {
            return $this->response(response::estado400(['Datos JSON no válidos.']));
        }
        if ($this->data['descripcion'] == "") {
            $this->data['descripcion'] = "Categoria sin descripción";
        }

        $this->data['nombre'] = ucwords($this->data['nombre']);
        $this->data['descripcion'] = ucfirst($this->data['descripcion']);
        guard::validateToken($this->header, guard::secretKey());
        try {
            if (empty($this->data['id_categoria'])) {
                $categoria = $this->model->createCategoria($this->data);
            } else {
                $categoria = $this->model->updateCategoria($this->data);
            }
            switch ($categoria) {
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

    public function deleteCategoria(int $id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $res = $this->model->deleteCategoria($id);
            if ($res === "ok") {
                return $this->response(response::estado200('ok'));
            }
            return $this->response(response::estado500());
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}