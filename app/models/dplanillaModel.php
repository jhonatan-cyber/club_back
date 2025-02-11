<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class dplanillaModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPlanillasFecha(string $fecha): array
    {
        try {
            $params = [':fecha' => $fecha];
            $totales = 'SELECT COALESCE(SUM(V.total), 0) AS total_ventas,
                        COALESCE((SELECT SUM(P.propina) FROM propinas P WHERE DATE(P.fecha) = :fecha), 0) AS total_propinas,
                        COALESCE((SELECT SUM(S.total) FROM servicios S WHERE DATE(S.fecha_crea) = :fecha), 0) AS total_servicios,
                        COALESCE((SELECT SUM(A.monto) FROM anticipos A WHERE DATE(A.fecha_crea)= :fecha),0) AS total_anticipos
                        FROM ventas V WHERE DATE(V.fecha_crea) = :fecha AND V.estado = 1';

            $totales = $this->select($totales, $params);

            $sql = 'SELECT U.id_usuario, CONCAT(U.nombre, " ", U.apellido) AS usuario, COALESCE((U.sueldo * COUNT(DISTINCT A.id_asistencia)), 0) AS sueldo,
                COALESCE((U.aporte * COUNT(DISTINCT A.id_asistencia)), 0) AS aporte, COALESCE(SUM(DV.comision), 0) AS comision, COALESCE(SUM(DP.monto), 0) AS propina,
                COALESCE(SUM(S.precio_servicio), 0) AS servicio, COALESCE(SUM(H.hora), 0) AS horas, COALESCE(SUM(H.monto), 0) AS extras, COALESCE(SUM(AN.monto), 0) AS anticipo,
                COALESCE((U.sueldo * COUNT(DISTINCT A.id_asistencia)), 0) + COALESCE(SUM(DP.monto), 0) + COALESCE(SUM(DV.comision), 0) + COALESCE(SUM(S.precio_servicio), 0) + 
                COALESCE(SUM(H.monto), 0) - COALESCE(SUM(AN.monto), 0) - COALESCE((U.aporte * COUNT(DISTINCT A.id_asistencia)), 0) AS total_pagar
                FROM usuarios U INNER JOIN asistencia A ON A.usuario_id = U.id_usuario AND A.fercha_asistencia = :fecha 
                LEFT JOIN usuario_venta US ON U.id_usuario = US.usuario_id LEFT JOIN detalle_ventas DV ON US.venta_id = DV.venta_id AND DV.estado = 1
                LEFT JOIN detalle_propinas DP ON A.fercha_asistencia = DATE(DP.fecha_crea) AND U.id_usuario = DP.usuario_id
                LEFT JOIN detalle_servicios DS ON A.fercha_asistencia = DATE(DS.fecha_crea) AND U.id_usuario = DS.usuario_id LEFT JOIN servicios S ON DS.servicio_id = S.id_servicio AND S.estado = 1
                LEFT JOIN horas_extras H ON A.fercha_asistencia = DATE(H.fecha_crea) AND U.id_usuario = H.usuario_id LEFT JOIN anticipos AN ON A.fercha_asistencia = DATE(AN.fecha_crea) AND U.id_usuario = AN.usuario_id
                GROUP BY U.id_usuario';

            $planilla = $this->selectAll($sql, $params);

            return [
                'planilla' => array_map(function ($row) {
                    return [
                        'id_usuario' => (int) $row['id_usuario'],
                        'usuario'    => (string) $row['usuario'],
                        'sueldo'     => (int) $row['sueldo'],
                        'aporte'     => (int) $row['aporte'],
                        'comision'   => (int) $row['comision'],
                        'propina'    => (int) $row['propina'],
                        'servicio'   => (int) $row['servicio'],
                        'horas'      => (int) $row['horas'],
                        'extras'     => (int) $row['extras'],
                        'anticipo'   => (int) $row['anticipo'],
                        'total_pagar' => (int) $row['total_pagar']
                    ];
                }, $planilla),
                'totales' => [
                    'total_propinas'   => (int) ($totales['total_propinas'] ?? 0),
                    'total_servicios'  => (int) ($totales['total_servicios'] ?? 0),
                    'total_anticipos'  => (int) ($totales['total_anticipos'] ?? 0),
                    'total_ventas'     => (int) ($totales['total_ventas'] ?? 0),
                ],
            ];
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
