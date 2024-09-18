<?php

namespace app\config;

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class guard
{
    private static $jwt_data;
    public static function secretKey()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();
        return $_ENV['SECRET_KEY'];
    }
    public static function createPassword(string $pw)
    {
        $pass = password_hash($pw, PASSWORD_DEFAULT);
        return $pass;
    }
    public static function validatePassword(string $pw, string $hash)
    {
        if (password_verify($pw, $hash)) {
            return true;
        } else {
            error_log('Contraseña incoreccta');
            return false;
        }
    }
    public static function createToken(string $key, array $data)
    {
        $payload = [
            'iat' => time(),  
            'exp' => time() + (60 * 60 * 8),
            'data' => $data
        ];
    
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }
    
    public static function validateToken(array $token, string $key)
    {
        if (!isset($token['Authorization'])) {
            die(json_encode(response::estado400('El token de acceso es requerido')));
        }

        try {
            $jwt = explode(" ", $token['Authorization']);
            $decoded = JWT::decode($jwt[1], new Key($key, 'HS256'));
            self::$jwt_data = $decoded;
            return $decoded;
        } catch (\Exception $e) {
            error_log('Token inválido');
            session_destroy();
            die(json_encode(response::estado401('Token inválido o expirado')));
        }
    }
    public static function getDataJwt()
    {
        $jwt_decode = json_decode(json_encode(self::$jwt_data), true);
        return $jwt_decode['data'];
    }
}
