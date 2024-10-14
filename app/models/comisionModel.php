<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class comisionModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getComisionUsuario(int $usuario_id)
    {
        $sql = "SELECT * FROM comisiones WHERE usuario_id = :usuario_id AND estado=1";
        $params = [
            ":usuario_id" => $usuario_id
        ];
        try {
            return $this->selectAll($sql, $params);

        } catch (Exception $e) {
            return response::estado500($e);
        }

    }
    public function getComisiones()
    {
        $sql = "SELECT C.usuario_id,U.nombre ,U.apellido, SUM(C.monto) AS total_comision,C.estado 
        FROM comisiones AS C JOIN usuarios AS U ON C.usuario_id = U.id_usuario GROUP BY usuario_id,estado";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}