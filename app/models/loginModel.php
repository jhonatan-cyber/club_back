<?php

namespace app\models;

use app\config\query;
use app\config\response;
use app\config\guard;
use Exception;

class loginModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function login(array $usuario)
    {
        $data = ['correo', 'password'];
        foreach ($data as $field) {
            if (!array_key_exists($field, $usuario)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }
        $sql = "CALL login(:correo)";
        $params = [
            ':correo' => $usuario['correo']
        ];
        $password = $usuario['usuario'];

        try {
            $res = $this->select($sql, $params);
            if ($res == null) {
                return response::estado400('Usuario o contraseña incorrecta');
            } else {
                if (guard::validatePassword($password, $res['password'])) {
                    $payload = ['token' => ["Run: {$res['run']}", "Nombre: {$res['nombre']}", "Apellido: {$res['apellido']}", "Rol: {$res['rol']}", "Correo: {$res['correo']}"]];
                    $token = guard::createToken(guard::secretKey(), $payload);
                    $data = [
                        'id_usuario' => $res['id_usuario'],
                        'token' => $token,
                        'foto' => $res['foto'],
                        'estado' => $res['estado']
                    ];
                    return response::estado200($data);
                }
                return response::estado400('Usuario o contraseña incorrecta');
            }
        } catch (Exception $e) {
            error_log("LoginModel::login() -> " . $e);
            return response::estado500();
        }
    }
}
