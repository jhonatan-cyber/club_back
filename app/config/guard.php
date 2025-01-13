<?php

namespace app\config;

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class guard
{
    private static $jwt_data;
    private static $secretKey;

    public static function secretKey()
    {
        if (!isset(self::$secretKey)) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();
            self::$secretKey = $_ENV['SECRET_KEY'];
        }
        return self::$secretKey;
    }

    public static function createPassword(string $pw)
    {
        return password_hash($pw, PASSWORD_DEFAULT);
    }

    public static function validatePassword(string $pw, string $hash)
    {
        if (!password_verify($pw, $hash)) {
            throw new Exception('Contraseña incorrecta', 400);
        }
        return true;
    }

    public static function createToken(string $key, array $data)
    {
        $payload = [
            'iat' => time(),
            'exp' => time() + (60 * 60 * 8),
            'data' => $data
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public static function getDataJwt()
    {
        $jwt_decode = json_decode(json_encode(self::$jwt_data), true);
        return $jwt_decode['data'];
    }

    public static function validateToken(array $token, string $key, bool $autoRefresh = true)
    {
        $authorizationHeader = null;
        foreach ($token as $headerKey => $headerValue) {
            if (strtolower($headerKey) === 'authorization') {
                $authorizationHeader = trim($headerValue);
                break;
            }
        }

        if (!$authorizationHeader || !preg_match('/^Bearer\s+(\S+)$/i', $authorizationHeader, $matches)) {
            throw new Exception('Token de acceso no proporcionado o formato incorrecto', 400);
        }

        $jwt = $matches[1];

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            self::$jwt_data = $decoded;

            if ($autoRefresh && isset($decoded->exp) && ($decoded->exp - time()) < 60) {
                $refreshedToken = self::refreshToken(['Authorization' => 'Bearer ' . $jwt], $key);
                if ($refreshedToken) {
                    header('X-Refresh-Token: ' . $refreshedToken['token']);
                    self::$jwt_data = $refreshedToken['data'];
                    return [
                        'token' => $refreshedToken['token'],
                        'data' => $refreshedToken['data'],
                        'message' => 'Token refrescado correctamente',
                    ];
                }
                throw new Exception('No se pudo refrescar el token');
            }

            return [
                'token' => $jwt,
                'data' => $decoded,
            ];
        } catch (Exception $e) {
            throw new Exception('Token inválido', 401);
        }
    }

    public static function refreshToken(array $token, string $key)
    {
        $authorizationHeader = null;
        foreach ($token as $headerKey => $headerValue) {
            if (strtolower($headerKey) === 'authorization') {
                $authorizationHeader = trim($headerValue);
                break;
            }
        }

        if (!$authorizationHeader || !preg_match('/^Bearer\s+(\S+)$/i', $authorizationHeader, $matches)) {
            throw new Exception('Token de acceso no proporcionado o formato incorrecto', 400);
        }

        $jwt = $matches[1];

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            if (isset($decoded->exp) && $decoded->exp < time()) {
                throw new Exception('El token ya expiró, no se puede refrescar', 401);
            }

            $originalData = $decoded->data;
            $newToken = self::createToken($key, (array) $originalData);

            if (!$newToken) {
                throw new Exception('No se pudo refrescar el token');
            }

            return [
                'token' => $newToken,
                'data' => $originalData,
                'message' => 'Token refrescado correctamente',
            ];
        } catch (Exception $e) {
            throw new Exception('Error al refrescar token', 500);
        }
    }

    public static function checkTokenStatus(array $token, string $key)
    {
        $authorizationHeader = null;

        foreach ($token as $headerKey => $headerValue) {
            if (strtolower($headerKey) === 'authorization') {
                $authorizationHeader = trim($headerValue);
                break;
            }
        }

        if (!$authorizationHeader || !str_starts_with(strtolower($authorizationHeader), 'bearer ')) {
            return [
                'status' => false,
                'message' => 'Token de acceso no proporcionado o formato incorrecto'
            ];
        }

        try {
            $jwt = explode(' ', $authorizationHeader);

            if (count($jwt) < 2) {
                return [
                    'status' => false,
                    'message' => 'Formato de token incorrecto'
                ];
            }

            $decoded = JWT::decode($jwt[1], new Key($key, 'HS256'));
            $timeLeft = $decoded->exp - time();
            $response = [
                'status' => true,
                'message' => 'Token válido',
                'timeLeft' => $timeLeft,
                'expiresAt' => $decoded->exp,
                'tokenRefreshed' => false,
                'newToken' => null
            ];

            if ($timeLeft < 60) {
                $refreshedToken = self::refreshToken(['Authorization' => 'Bearer ' . $jwt[1]], $key);
                if ($refreshedToken) {
                    $response['tokenRefreshed'] = true;
                    $response['newToken'] = $refreshedToken['token'];
                } else {
                    throw new Exception('No se pudo refrescar el token');
                }
            }

            return $response;
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Token inválido o expirado',
                'error' => $e->getMessage(),
                'timeLeft' => 0
            ];
        }
    }
}
