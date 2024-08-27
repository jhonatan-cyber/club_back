<?php

namespace app\config;

class response
{
    public static function json($msg, $estado, $codigo)
    {
        http_response_code($codigo);
        $response = [
            'data' => $msg,
            'estado' => $estado,
            'codigo' => $codigo
        ];
        return $response;
    }

    public static function estado200($res)
    {
        http_response_code(200);
        return self::json($res, 'ok', 200);
    }

    public static function estado201($res = 'Recurso creado correctamente.')
    {
        http_response_code(201);
        return self::json($res, 'ok', 201);
    }
    public static function estado204($res = 'No, existen recursos que mostrar.')
    {
        http_response_code(204);
        return self::json($res, 'ok', 204);
    }

    public static function estado400($res = 'Error en la petición o datos incorrectos. Verifique los datos ingresados.')
    {
        http_response_code(400);
        return self::json($res, 'error', 400);
    }

    public static function estado401($res = 'No tiene privilegios para acceder al recurso solicitado.')
    {
        http_response_code(401);
        return self::json($res, 'error', 401);
    }

    public static function estado403($res = 'No tiene permisos para realizar esta acción. Verifique su token de acceso.')
    {
        http_response_code(403);
        return self::json($res, 'error', 403);
    }

    public static function estado404($res = 'No se encontró el recurso solicitado. Verifique la ruta o el método de la petición.')
    {
        http_response_code(404);
        return self::json($res, 'error', 404);
    }
    public static function estado405($res = 'Método no permitidoo. Verifique el método de la petición.')
    {
        http_response_code(405);
        return self::json($res, 'error', 405);
    }
    public static function estado409($res = 'Conflicto. El recurso ya existe.')
    {
        http_response_code(409);
        return self::json($res, 'error', 409);
    }
    public static function estado500($res = 'Error en el servidor.')
    {
        http_response_code(500);
        return self::json($res, 'error', 500);
    }
}
