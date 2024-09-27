<?php

namespace app\models;

use app\config\query;
use app\config\response;
use app\config\guard;
use app\controllers\usuario;
use Exception;

class loginModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function login(array $usuario)
    {
        $requiredFields = ['correo', 'password'];

        // Verificar que todos los campos requeridos estén presentes
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $usuario)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }

        $sql = "CALL login(:correo)";
        $params = [':correo' => $usuario['correo']];
        $password = $usuario['password'];

        try {
            $res = $this->select($sql, $params);
            if (empty($res)) {
                return response::estado400('Usuario no encontrado');
            }
            if (guard::validatePassword($password, $res['password'])) {
                $payload = [
                    'token' => [
                        "{$res['id_usuario']}",
                        "{$res['run']}",
                        "{$res['nombre']}",
                        "{$res['apellido']}",
                        "{$res['rol']}",
                        "{$res['correo']}"
                    ]
                ];
                $token = guard::createToken(guard::secretKey(), $payload);
                $data = [
                    'id_usuario' => $res['id_usuario'],
                    'token' => $token,
                    'estado' => $res['estado']
                ];

                return response::estado200($data);
            }

            return response::estado400('Contraseña incorrecta');
        } catch (Exception $e) {
            return response::estado500('Error en el servidor: ' . $e->getMessage());
        }
    }
    public function createCodigo(string $codigo)
    {
        try {
            $sql = "INSERT INTO codigos (codigo) VALUES (:codigo)";
            $params = [
                ':codigo' => $codigo,
            ];
            $data = $this->save($sql, $params);
            return $data == 1 ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }

    }
    public function updateCodigo()
    {
        try {
            $sql = "UPDATE codigos SET estado = 0 WHERE estado=:estado LIMIT 1";
            $params = [
                ':estado' => 1,
            ];

            $data = $this->save($sql, $params);
            return $data == 1 ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }

    }
    public function createAsistencia(int $usuario_id)
    {
        try {
            $sql = "INSERT INTO asistencia (usuario_id) VALUES (:usuario_id)";
            $params = [
                ':usuario_id' => $usuario_id,
            ];
            $data = $this->save($sql, $params);
            return $data == 1 ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }

    }
    public function validarCodigo(string $codigo)
    {
        try {
            $sql = "SELECT * FROM codigos WHERE codigo = :codigo";
            $params = [
                ':codigo' => $codigo,
            ];
            $data = $this->select($sql, $params);
            return $data;
        } catch (Exception $e) {
            return response::estado500($e);
        }

    }
    public function createLogin($usuario_id)
    {
        $sql = "SELECT * FROM logins WHERE usuario_id = :usuario_id AND estado = 1";
        $params = [
            ":usuario_id" => $usuario_id,
        ];

        $data = $this->select($sql, $params);
        if ($data) {
            return "ya activo";
        }
        $sql = "SELECT * FROM logins WHERE usuario_id = :usuario_id AND estado = 0";
        $data = $this->select($sql, $params);

        if ($data) {
            $sql = "UPDATE logins SET estado = 1 WHERE usuario_id = :usuario_id AND estado = 0";
            try {
                $data = $this->save($sql, $params);
                return $data == 1 ? "ok" : "error";
            } catch (Exception $e) {
                return response::estado500($e);
            }
        } else {
            $sql = "INSERT INTO logins (usuario_id) VALUES (:usuario_id)";
            try {
                $data = $this->save($sql, $params);
                return $data == 1 ? "ok" : "error";
            } catch (Exception $e) {
                return response::estado500($e);
            }
        }
    }
    public function updateLogin($usuario_id)
    {
        $sql = "UPDATE logins SET estado = 0 WHERE usuario_id = :usuario_id AND estado = 1";
        $params = [
            ":usuario_id" => $usuario_id,
        ];
        try {
            $data = $this->save($sql, $params);
            return $data == 1 ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
   

}