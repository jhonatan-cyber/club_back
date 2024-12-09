<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class asistenciaModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAsistencias()
    {
        try {
            $sql_fechas = "SELECT 
                DATE_FORMAT(MIN(fercha_asistencia), '%d/%m/%Y') as primera_asistencia_global,
                DATE_FORMAT(MAX(fercha_asistencia), '%d/%m/%Y') as ultima_asistencia_global
            FROM asistencia 
            WHERE estado = 1";
            $fechas_globales = $this->select($sql_fechas);
            $sql_usuarios = "SELECT A.usuario_id,
                CONCAT(U.nombre, ' ', U.apellido) as nombre_completo,
                COUNT(CASE WHEN A.estado = 1 THEN 1 END) as total_asistencias,
                (U.sueldo * COUNT(CASE WHEN A.estado = 1 THEN 1 END)) as sueldo_total,
                (U.aporte * COUNT(CASE WHEN A.estado = 1 THEN 1 END)) as aporte_total,
                ((U.sueldo * COUNT(CASE WHEN A.estado = 1 THEN 1 END)) - 
                (U.aporte * COUNT(CASE WHEN A.estado = 1 THEN 1 END))) as total_final
            FROM asistencia AS A 
            INNER JOIN usuarios AS U ON A.usuario_id = U.id_usuario
            WHERE A.fercha_asistencia BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND LAST_DAY(CURDATE())
            GROUP BY A.usuario_id, U.nombre, U.apellido, U.sueldo, U.aporte
            ORDER BY U.apellido ASC";

            $usuarios = $this->selectAll($sql_usuarios);
            return [
                'fechas_globales' => $fechas_globales,
                'usuarios' => $usuarios
            ];
        } catch (Exception $e) {
            return response::estado500($e->getMessage());
        }
    }

    public function getAsistencia(int $usuario_id)
    {
        try {
            $sql_totales = "SELECT 
                SUM(U.sueldo) as total_sueldos,
                SUM(U.aporte) as total_aportes,
                SUM(U.sueldo - U.aporte) as gran_total
            FROM asistencia AS A 
            INNER JOIN usuarios AS U ON A.usuario_id = U.id_usuario 
            WHERE A.usuario_id = :usuario_id 
            AND A.estado = 1
            AND A.fercha_asistencia BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND LAST_DAY(CURDATE())";

            $totales = $this->select($sql_totales, [':usuario_id' => $usuario_id]);

            $sql = "SELECT A.*, U.nombre, U.apellido, U.sueldo, U.aporte,
                (U.sueldo) AS sueldo_total,
                (U.aporte) AS aporte_total,
                ((U.sueldo) - (U.aporte)) as total_final
            FROM asistencia AS A 
            INNER JOIN usuarios AS U ON A.usuario_id = U.id_usuario 
            WHERE A.usuario_id = :usuario_id 
            AND A.estado = 1
            AND A.fercha_asistencia BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND LAST_DAY(CURDATE())
            ORDER BY A.fercha_asistencia DESC";

            $paramas = [
                ':usuario_id' => $usuario_id
            ];

            $asistencias = $this->selectAll($sql, $paramas);

            return [
                'asistencias' => $asistencias,
                'totales' => [
                    'total_sueldos' => $totales['total_sueldos'],
                    'total_aportes' => $totales['total_aportes'],
                    'gran_total' => $totales['gran_total']
                ]
            ];
        } catch (Exception $e) {
            return response::estado500($e->getMessage());
        }
    }

  
}
