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
        $sql = 'SELECT D.estado, COALESCE(SUM(CASE WHEN C.venta_id != 0 THEN C.monto ELSE 0 END), 0) AS total_venta, 
        COALESCE(SUM(CASE WHEN C.servicio_id != 0 THEN C.monto ELSE 0 END), 0) AS total_servicio, 
        COALESCE(A.anticipo, 0) AS anticipo, COALESCE(SUM(C.monto) - COALESCE(A.anticipo, 0), 0) AS total, D.chica_id, 
        CONCAT(U.nombre, " ", U.apellido) AS chica FROM comisiones AS C 
        INNER JOIN detalle_comisiones AS D ON C.id_comision = D.comision_id 
        INNER JOIN usuarios AS U ON D.chica_id = U.id_usuario 
        LEFT JOIN (SELECT usuario_id, SUM(monto) AS anticipo FROM anticipos WHERE estado = 0 GROUP BY usuario_id) AS A ON D.chica_id = A.usuario_id 
        WHERE C.estado = 1 GROUP BY D.chica_id, D.estado, A.anticipo, U.nombre, U.apellido ORDER BY total DESC';
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

}