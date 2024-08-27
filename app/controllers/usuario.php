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
        /*    guard::validateToken($this->header, guard::secretKey()); */
        try {
            $usuarios = $this->model->getUsuarios();
            if (empty($usuarios)) {
                return $this->response(response::estado204());
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
        /*    guard::validateToken($this->header, guard::secretKey()); */
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
        /*    guard::validateToken($this->header, guard::secretKey()); */
        try {
            $img = $_FILES['foto'] ?? null;
            $img_anterior = $_POST['img_ante'] ?? 'default.png';
            $foto_final = '';
            $this->data = [
                'id_usuario' => $_POST['id_usuario'] ?? null,
                'run' => $_POST['run'] ?? null,
                'nombre' => $_POST['nombre'] ?? null,
                'apellido' => $_POST['apellido'] ?? null,
                'direccion' => $_POST['direccion'] ?? null,
                'telefono' => $_POST['telefono'] ?? null,
                'correo' => $_POST['correo'] ?? null,
                'password' => $_POST['password'] ?? null,
                'rol_id' => $_POST['rol_id'] ?? null,
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
                    return $this->response(Response::estado201());

                case 'existe':
                    return $this->response(response::estado409());

                case 'error':
                    return $this->response(response::estado500());
            }
        } catch (Exception $e) {
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
        /*    guard::validateToken($this->header, guard::secretKey()); */
        try {
            $res = $this->model->deleteUsuario($id);
            if ($res == 'ok') {
                return $this->response(response::estado200('ok'));
            }
            return $this->response(response::estado500());
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}
