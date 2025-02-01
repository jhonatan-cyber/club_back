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

    public function getPropina(int $usuario_id): array
    {
        try {
            $sql = 'SELECT COALESCE(SUM(DP.monto), 0) as total 
                    FROM detalle_propinas DP
                    LEFT JOIN propinas P ON DP.propina_id = P.id_propina
                    WHERE DP.usuario_id = :id_usuario AND P.estado = 1';

            $params = [':id_usuario' => $usuario_id];

            $result = $this->select($sql, $params);

            return ['total' => (int) $result['total']];
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
