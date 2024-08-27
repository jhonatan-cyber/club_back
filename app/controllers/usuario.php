<?php

namespace app\controllers;

use app\config\controller;
use app\models\usuarioModel;
use app\config\response;
use app\config\guard;
use Exception;

class usuario extends controller
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new usuarioModel();
    }

    public function getUsuarios()
    {

        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
           guard::validateToken($this->header, guard::secretKey());
        try {
            $usuarios = $this->model->getUsuarios();
            if (empty($usuarios)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($usuarios));
        } catch (Exception $e) {
            http_response_code(500);
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
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($usuario));
        } catch (Exception $e) {
            http_response_code(500);
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
            $data = json_decode(file_get_contents('php://input'), true); 
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->response(response::estado400("Error en el formato del JSON"));
            }
    
            $img = $_FILES['foto'] ?? null;
            $img_anterior = $data['img_ante'] ?? 'default.png';
            $foto_final = '';
    
            $this->data = [
                'id_usuario' => $data['id_usuario'] ?? null,
                'run' => $data['run'],
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'direccion' => $data['direccion'],
                'telefono' => $data['telefono'],
                'correo' => $data['correo'],
                'password' => $data['password'],
                'rol_id' => $data['rol_id'],
                'foto' => $img['name'] ?? $img_anterior,
            ];
    
            $required = ['run', 'nombre', 'apellido', 'direccion', 'telefono', 'correo', 'password', 'rol_id'];
            foreach ($required as $field) {
                if (empty($this->data[$field])) {
                    return $this->response(response::estado400("El campo $field es obligatorio"));
                }
            }
    
            if (!empty($img['name'])) {
                $extension = pathinfo($img['name'], PATHINFO_EXTENSION);
                $foto_final = uniqid() . '.webp';
                $this->data['foto'] = $foto_final;
            } else {
                $foto_final = $img_anterior;
            }
    
            if (empty($this->data['id_usuario'])) {
                $usuario = $this->model->createUsuario($this->data);
             
            } else {
                $usuario = $this->model->updateUsuario($this->data);
            }
    
            switch ($usuario) {
                case 'ok':
                    if (!empty($img['tmp_name'])) {
                        $destino = 'public/assets/img/usuarios/' . $foto_final;
                        $this->convertToWebP($img['tmp_name'], $destino, $extension);
    
                        if ($img_anterior !== 'default.png' && file_exists('public/assets/img/usuarios/' . $img_anterior)) {
                            unlink('public/assets/img/usuarios/' . $img_anterior);
                        }
                    }
                    http_response_code(201);
                    return $this->response(Response::estado201());
    
                case 'existe':
                    http_response_code(409);
                    return $this->response(response::estado409());
    
                case 'error':
                    http_response_code(500);
                    return $this->response(response::estado500());
            }
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }
    
    private function convertToWebP($sourcePath, $destinationPath, $extension)
    {
        switch (strtolower($extension)) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            default:
                $this->response(response::estado400("Formato de imagen no soportado para conversión a WebP."));
                return;
        }

        if ($image) {

            imagewebp($image, $destinationPath);
            imagedestroy($image);
        } else {
            $this->response(Response::estado500("Error al convertir la imagen a WebP."));
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
