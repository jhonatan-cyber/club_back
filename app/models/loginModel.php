<?php

namespace app\models;

use app\config\guard;
use app\config\query;
use app\config\response;
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
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $usuario)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }

        $sql = 'SELECT U.id_usuario, U.run, U.nombre, U.apellido, U.correo, U.password, U.foto, U.estado, R.nombre AS rol
                FROM usuarios AS U JOIN roles AS R ON U.rol_id = R.id_rol 
                WHERE U.correo = :correo LIMIT 1';
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
                        'id_usuario' => $res['id_usuario'],
                        'run' => $res['run'],
                        'nombre' => $res['nombre'],
                        'apellido' => $res['apellido'],
                        'rol' => $res['rol'],
                        'foto' => $res['foto'],
                        'correo' => $res['correo']
                    ]
                ];
                $token = guard::createToken(guard::secretKey(), $payload);
                $data = [
                    'id_usuario' => $res['id_usuario'],
                    'token' => $token,
                    'estado' => $res['estado'],
                    'rol' => $res['rol'],
                ];

                return response::estado200($data);
            }

            return response::estado400('Contraseña incorrecta');
        } catch (Exception $e) {
            return response::estado500('Error en el servidor: ' . $e->getMessage());
        }
    }

    public function createAsistencia(int $usuario_id)
    {
        try {
            $sql1 = 'SELECT id_asistencia FROM asistencia WHERE usuario_id = :usuario_id AND DATE(fercha_asistencia) = CURDATE() LIMIT 1';
            $params1 = [
                ':usuario_id' => $usuario_id
            ];
            $result = $this->select($sql1, $params1);

            if (!empty($result)) {
                return 'existe';
            } else {
                $sql = 'INSERT INTO asistencia (usuario_id, hora_asistencia, fercha_asistencia) VALUES (:usuario_id, CURTIME(), CURDATE())';
                $params = [
                    ':usuario_id' => $usuario_id
                ];
                $data = $this->save($sql, $params);
                return $data == 1 ? 'ok' : 'error';
            }
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function validarCodigo(string $codigo)
    {
        try {
            $sql = 'SELECT * FROM codigos WHERE codigo = :codigo';
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
        $sql = 'SELECT * FROM logins WHERE usuario_id = :usuario_id AND estado = 1';
        $params = [
            ':usuario_id' => $usuario_id,
        ];

        $data = $this->select($sql, $params);
        if ($data) {
            return 'activo';
        }
        $sql = 'SELECT * FROM logins WHERE usuario_id = :usuario_id AND estado = 0';
        $data = $this->select($sql, $params);

        if ($data) {
            $sql = 'UPDATE logins SET estado = 1 WHERE usuario_id = :usuario_id AND estado = 0';
            try {
                $data = $this->save($sql, $params);
                return $data == 1 ? 'ok' : 'error';
            } catch (Exception $e) {
                return response::estado500($e);
            }
        } else {
            $sql = 'INSERT INTO logins (usuario_id) VALUES (:usuario_id)';
            try {
                $data = $this->save($sql, $params);
                return $data == 1 ? 'ok' : 'error';
            } catch (Exception $e) {
                return response::estado500($e);
            }
        }
    }

    public function updateLogin($usuario_id)
    {
        $sql = 'UPDATE logins SET estado = 0 WHERE usuario_id = :usuario_id AND estado = 1';
        $params = [
            ':usuario_id' => $usuario_id,
        ];
        try {
            $data = $this->save($sql, $params);
            return $data == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
