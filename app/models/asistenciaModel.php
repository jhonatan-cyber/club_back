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
                            CAST(SUM(U.sueldo) AS UNSIGNED) AS total_sueldos, 
                            CAST(SUM(U.aporte) AS UNSIGNED) AS total_aportes,
                            CAST(COALESCE((SELECT SUM(AN.monto) FROM anticipos AS AN 
                            WHERE AN.usuario_id = A.usuario_id AND AN.estado = 0), 0) AS UNSIGNED) AS total_anticipos,
                            CAST(SUM(U.sueldo - U.aporte) - COALESCE((SELECT SUM(AN.monto) 
                            FROM anticipos AS AN WHERE AN.usuario_id = A.usuario_id AND AN.estado = 0), 0) 
                            AS UNSIGNED) AS gran_total 
                        FROM asistencia AS A 
                        INNER JOIN usuarios AS U ON A.usuario_id = U.id_usuario 
                        WHERE A.estado = 1 
                        AND A.usuario_id = :usuario_id 
                        AND A.fercha_asistencia BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND LAST_DAY(CURDATE())";

        $totales = $this->select($sql_totales, [':usuario_id' => $usuario_id]);

        if (empty($totales)) {
            return response::estado500("No se encontraron resultados para los totales.");
        }

        $sql = "SELECT A.*, U.nombre, U.apellido, U.sueldo, U.aporte,
                       CAST(U.sueldo AS UNSIGNED) AS sueldo_total,
                       CAST(U.aporte AS UNSIGNED) AS aporte_total,
                       CAST((U.sueldo - U.aporte) AS UNSIGNED) AS total_final
                FROM asistencia AS A
                INNER JOIN usuarios AS U ON A.usuario_id = U.id_usuario
                WHERE A.usuario_id = :usuario_id 
                AND A.estado = 1
                AND A.fercha_asistencia BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND LAST_DAY(CURDATE())
                ORDER BY A.fercha_asistencia DESC";

        $paramas = [':usuario_id' => $usuario_id];
        $asistencias = $this->selectAll($sql, $paramas);

        return [
            'asistencias' => $asistencias,
            'totales' => [
                'total_sueldos' => (int) $totales['total_sueldos'],
                'total_aportes' => (int) $totales['total_aportes'],
                'total_anticipos' => (int) $totales['total_anticipos'],  
                'gran_total' => (int) $totales['gran_total']
            ]
        ];
    } catch (Exception $e) {
        return response::estado500($e->getMessage());
    }
}


    public function createAsistencia(int $usuario_id)
    {
        $params = [
            ':usuario_id' => $usuario_id
        ];
        try {
            $sqlUpdateAnticipo = 'UPDATE anticipos SET estado = 0, fecha_mod = now() WHERE usuario_id = :usuario_id AND estado = 1';
            $this->save($sqlUpdateAnticipo, $params);
            $sqlSelectAsistencia = 'SELECT id_asistencia FROM asistencia WHERE usuario_id = :usuario_id AND DATE(fercha_asistencia) = CURDATE() LIMIT 1';
            $resp = $this->select($sqlSelectAsistencia, $params);
            if (!empty($resp)) {
                return 'existe';
            }
            $sqlAddAsistencia = 'INSERT INTO asistencia (usuario_id, hora_asistencia, fercha_asistencia) VALUES (:usuario_id, CURTIME(), CURDATE())';
            $result = $this->save($sqlAddAsistencia, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
