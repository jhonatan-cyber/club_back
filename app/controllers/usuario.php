<?php

namespace app\controllers;

use Exception;
use app\config\view;
use app\config\guard;
use app\config\response;
use app\config\controller;
use app\models\usuarioModel;
use app\config\cache;

class usuario extends controller
{
    private $model;
    private static $validar_numero = '/^[0-9]+$/';
    public function __construct()
    {
        parent::__construct();
        $this->model = new usuarioModel();
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
                echo $view->render('usuario', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
       
            $this->response(Response::estado404($e));
        }
    }
    public function getUsuarios()
    {

        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        try {
            $cacheKey = 'usuarios_list';
            $usuarios = cache::get($cacheKey);

            if (!$usuarios) {
                $usuarios = $this->model->getUsuarios();
                cache::set($cacheKey, $usuarios, 600);
            }

            if (empty($usuarios)) {
                return $this->response(response::estado204('No se encontraron usuarios'));
            }

            return $this->response(response::estado200($usuarios));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function getUsuario(int $id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $usuario = $this->model->getUsuario($id);
            if (empty($usuario)) {
                return $this->response(response::estado204());
            }
            return $this->response(response::estado200($usuario));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function createUsuario()
    {
        if ($this->method !== 'POST') {
      
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $data = $_POST;
            $img = $_FILES['foto'] ?? null;
            $img_anterior = $data['img_anterior'] ?? 'default.png';
            $foto_final = '';
            $this->data = [
                'id_usuario' => $data['id_usuario'] ?? null,
                'run' => $data['run'],
                'nick' => $data['nick'],
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'direccion' => $data['direccion'],
                'telefono' => $data['telefono'],
                'estado_civil' => $data['estado_civil'],
                'afp' => $data['afp'],
                'aporte' => $data['aporte'],
                'sueldo' => $data['sueldo'],
                'correo' => $data['correo'],
                'password' => $data['password'],
                'rol_id' => $data['rol_id'],
                'foto' => $img['name'] ?? $img_anterior,
            ];

            $required = ['run', 'nick', 'nombre', 'apellido', 'direccion', 'telefono', 'estado_civil', 'afp', 'aporte', 'sueldo', 'correo', 'password', 'rol_id'];
            foreach ($required as $field) {
                if (empty($this->data[$field])) {
                    return $this->response(response::estado400("El campo $field es obligatorio"));
                }
            }

            if (!preg_match(self::$validar_numero, $this->data['run'])) {
                return $this->response(Response::estado400('El campo run solo puede contener números'));
            }
            if (!preg_match(self::$validar_numero, $this->data['aporte'])) {
                return $this->response(Response::estado400('El campo aporte solo puede contener números'));
            }
            if (!preg_match(self::$validar_numero, $this->data['sueldo'])) {
                return $this->response(Response::estado400('El campo sueldo solo puede contener números'));
            }
            if (!preg_match(self::$validar_numero, $this->data['telefono'])) {
                return $this->response(Response::estado400('El campo telefono solo puede contener números'));
            }
            if (!filter_var($this->data['correo'], FILTER_VALIDATE_EMAIL)) {
                return $this->response(Response::estado400('El campo correo no es válido'));
            }

            if (!empty($img['name'])) {
                $extension = pathinfo($img['name'], PATHINFO_EXTENSION);
                $foto_final = uniqid() . '.webp';
                $this->data['foto'] = $foto_final;
            } else {
                $foto_final = $img_anterior;
            }

            $this->data['nombre'] = ucwords($this->data['nombre']);
            $this->data['apellido'] = ucwords($this->data['apellido']);
            $this->data['nick'] = ucwords($this->data['nick']);
    
            if (empty($this->data['id_usuario']) || $this->data['id_usuario'] == null) {
                $usuario = $this->model->createUsuario($this->data);
            } else {
                $usuario = $this->model->updateUsuario($this->data);
            }
            if ($usuario === 'ok') {
                if (!empty($img['tmp_name'])) {
                    $destino = 'public/assets/img/usuarios/' . $foto_final;
                    $this->convertToWebP($img['tmp_name'], $destino, $extension);

                    if ($img_anterior !== 'default.png' && file_exists('public/assets/img/usuarios/' . $img_anterior)) {
                        unlink('public/assets/img/usuarios/' . $img_anterior);
                    }
                }
                return $this->response(Response::estado201());
            }
            if ($usuario === 'existe') {
                return $this->response(Response::estado409('El usuario ya existe'));
            }

            return $this->response(Response::estado500());
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
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
            $this->response(Response::estado500("Error al convertir la imagen a WebP."));
            imagedestroy($image);
        }
    }
    public function deleteUsuario(int $id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $res = $this->model->deleteUsuario($id);
            if ($res == 'ok') {
                return $this->response(response::estado201());
            }
            return $this->response(response::estado500());
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function getChicas()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $res = $this->model->getChicas(); 
            if (empty($res)) {
                return $this->response(response::estado204());
            }
            return $this->response(response::estado200($res));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}