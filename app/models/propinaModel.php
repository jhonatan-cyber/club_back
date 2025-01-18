<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class propinaModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPropinas()
    {
        $sql = 'SELECT U.id_usuario, U.nombre, U.apellido, DP.fecha_crea, SUM(DP.monto) as monto_total
                FROM usuarios U
                LEFT JOIN detalle_propinas DP ON U.id_usuario = DP.usuario_id
                LEFT JOIN propinas P ON DP.propina_id = P.id_propina
                WHERE P.estado = 1
                GROUP BY U.id_usuario, U.nombre, U.apellido
                ORDER BY monto_total DESC';

        try {
            $data = $this->selectAll($sql);
            return $data;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getPropina(int $usuario_id){
        try {
            $sql = 'SELECT U.id_usuario, U.nombre, U.apellido, DP.fecha_crea, SUM(DP.monto) as monto_total
                    FROM usuarios U
                    LEFT JOIN detalle_propinas DP ON U.id_usuario = DP.usuario_id
                    LEFT JOIN propinas P ON DP.propina_id = P.id_propina
                    WHERE U.id_usuario = :id_usuario AND P.estado = 1
                    GROUP BY U.id_usuario, U.nombre, U.apellido
                    ORDER BY monto_total DESC';
            $params = ['id_usuario' => $usuario_id];
            $data = $this->select($sql, $params);
            return $data;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    
}
