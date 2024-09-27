<?php

namespace app\controllers;

use Exception;
use app\config\view;
use app\config\guard;
use app\config\response;
use app\models\productoModel;
use app\config\controller;

class producto extends controller
{
    private $model;
    private static $valdiate_number = '/^[0-9]+$/';

    public function __construct()
    {
        parent::__construct();
        $this->model = new productoModel();
    }
    public function index()
    {
        if ($this->method !== 'GET') {
            $this->response(response::estado405());
        }

        try {
            $view = new view();
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('producto', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(response::estado404($e));
        }
    }

    public function getProductoCategoria(int $id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $producto = $this->model->getProductosCategoria($id);
            if (!empty($producto) || $producto !== "") {
                http_response_code(200);
                return $this->response(response::estado200($producto));
            }
            http_response_code(204);
            return $this->response(response::estado204());
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500());
        }

    }
    public function getProductos()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $productos = $this->model->getProductos();
            if (empty($productos)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($productos));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500());
        }
    }
    public function getProducto(int $id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $producto = $this->model->getProducto($id);
            if (empty($producto)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($producto));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500());
        }
    }
    private function convertToWebP($sourcePath, $destinationPath, $extension)
    {

        if (!extension_loaded('gd')) {
            $this->response(response::estado500("La extensión GD no está habilitada en el servidor."));
            return;
        }

        switch (strtolower($extension)) {
            case 'jpeg':
            case 'jpg':
                if (function_exists('imagecreatefromjpeg')) {
                    $image = imagecreatefromjpeg($sourcePath);
                } else {
                    $this->response(response::estado500("La función imagecreatefromjpeg no está disponible."));
                    return;
                }
                break;
            case 'png':
                if (function_exists('imagecreatefrompng')) {
                    $image = imagecreatefrompng($sourcePath);
                } else {
                    $this->response(response::estado500("La función imagecreatefrompng no está disponible."));
                    return;
                }
                break;
            case 'gif':
                if (function_exists('imagecreatefromgif')) {
                    $image = imagecreatefromgif($sourcePath);
                } else {
                    $this->response(response::estado500("La función imagecreatefromgif no está disponible."));
                    return;
                }
                break;
            default:
                $this->response(response::estado400("Formato de imagen no soportado para conversión a WebP."));
                return;
        }

        if ($image === false) {
            $this->response(response::estado500("Error al cargar la imagen. Verifica el archivo de origen."));
            return;
        }

        if (imagewebp($image, $destinationPath)) {
            imagedestroy($image);
        } else {
            $this->response(response::estado500("Error al convertir la imagen a WebP."));
            imagedestroy($image);
        }
    }
    public function createProducto()
    {
        if ($this->method !== 'POST') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $data = $_POST;
            $img = $_FILES['foto'] ?? null;
            $img_anterior = $data['img_anterior'] ?? 'default.png';
            $foto_final = '';
            $this->data = [
                'id_producto' => $data['id_producto'] ?? null,
                'codigo' => $data['codigo'],
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'precio' => $data['precio'],
                'categoria_id' => $data['categoria_id'],
                'foto' => $img['name'] ?? $img_anterior,
            ];

            $required = ['codigo', 'nombre', 'descripcion', 'precio', 'categoria_id'];
            foreach ($required as $field) {
                if (empty($this->data[$field])) {
                    http_response_code(400);
                    return $this->response(response::estado400("El campo $field es obligatorio"));
                }
            }

            if (!preg_match(self::$valdiate_number, $this->data['categoria_id'])) {
                return $this->response(response::estado400('El campo categoria_id solo puede contener números'));
            }
            if (!preg_match(self::$valdiate_number, $this->data['precio'])) {
                return $this->response(response::estado400('El campo precio solo puede contener números'));
            }

            if (!empty($img['name'])) {
                $extension = pathinfo($img['name'], PATHINFO_EXTENSION);
                $foto_final = uniqid() . '.webp';
                $this->data['foto'] = $foto_final;
            } else {
                $foto_final = $img_anterior;
            }


            if (empty($this->data['id_producto']) || $this->data['id_producto'] == null) {
                $producto = $this->model->createProducto($this->data);
            } else {
                $producto = $this->model->updateProducto($this->data);
            }

            if ($producto == 'ok') {

                if (!empty($img['tmp_name'])) {
                    $destino = 'public/assets/img/productos/' . $foto_final;
                    $this->convertToWebP($img['tmp_name'], $destino, $extension);

                    if ($img_anterior !== 'default.png' && file_exists('public/assets/img/productos/' . $img_anterior)) {
                        unlink('public/assets/img/productos/' . $img_anterior);
                    }
                }
                http_response_code(201);
                return $this->response(response::estado201());
            }

            if ($producto == 'existe') {
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
    public function deleteProducto(int $id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $res = $this->model->deleteProducto($id);
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

    public function getProductosPrecio()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $productos = $this->model->getProductosPrecio();
            if (empty($productos)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($productos));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500());
        }
    }

    public function getBebidasPrecio($precio)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $productos = $this->model->getBebidasPrecio($precio);
            if (empty($productos)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($productos));
        } catch (Exception $e) {
            http_response_code(500);
            $this->response(response::estado500());
        }
    }
}