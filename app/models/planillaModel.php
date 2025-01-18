<?php
namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class planillaModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPlanillas()
    {
        $sql = 'SELECT U.id_usuario, CONCAT(U.nombre, " ", U.apellido) as nombre_completo,
                COALESCE((U.sueldo * COUNT(DISTINCT A.id_asistencia)), 0) as sueldo,
                COALESCE((U.aporte * COUNT(DISTINCT A.id_asistencia)), 0) as aporte,
                COALESCE(COM.total_venta, 0) as ventas, COALESCE(COM.total_servicio, 0) as servicios,
                COALESCE(COM.total_anticipo, 0) as anticipos, COALESCE(P.propina_total, 0) as propinas,
                (
                    COALESCE((U.sueldo * COUNT(DISTINCT A.id_asistencia)), 0) + 
                    COALESCE(COM.total_venta, 0) + COALESCE(COM.total_servicio, 0) + 
                    COALESCE(P.propina_total, 0) - COALESCE(COM.total_anticipo, 0) - 
                    COALESCE((U.aporte * COUNT(DISTINCT A.id_asistencia)), 0)
                ) as total
FROM usuarios U 
LEFT JOIN asistencia A ON U.id_usuario = A.usuario_id 
    AND A.fercha_asistencia BETWEEN DATE_FORMAT(CURDATE(), "%Y-%m-01") AND LAST_DAY(CURDATE())
    AND A.estado = 1
LEFT JOIN (SELECT D.chica_id, 
                  COALESCE(SUM(CASE WHEN C.venta_id != 0 THEN C.monto ELSE 0 END), 0) AS total_venta,
                  COALESCE(SUM(CASE WHEN C.servicio_id != 0 THEN C.monto ELSE 0 END), 0) AS total_servicio,
                  COALESCE(A.anticipo, 0) AS total_anticipo 
           FROM comisiones C
           INNER JOIN detalle_comisiones D ON C.id_comision = D.comision_id
           LEFT JOIN (
               SELECT usuario_id, SUM(monto) AS anticipo 
               FROM anticipos 
               WHERE estado = 0 
               GROUP BY usuario_id
           ) A ON D.chica_id = A.usuario_id
           WHERE C.estado = 1
           GROUP BY D.chica_id, A.anticipo
    ) COM ON U.id_usuario = COM.chica_id
LEFT JOIN (
    SELECT DP.usuario_id, SUM(DP.monto) as propina_total
    FROM detalle_propinas DP
    INNER JOIN propinas P ON DP.propina_id = P.id_propina
    WHERE P.estado = 1
    GROUP BY DP.usuario_id
) P ON U.id_usuario = P.usuario_id
WHERE U.id_usuario != 1
GROUP BY 
    U.id_usuario, U.nombre, U.apellido, U.sueldo, U.aporte,
    COM.total_venta, COM.total_servicio, COM.total_anticipo,
    P.propina_total
HAVING total > 0
ORDER BY total DESC;';

        try {
            $data = $this->selectAll($sql);
            return $data;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }


    public function getComision_id(int $id_usuario)
    {
        $sql = "SELECT comision_id, comision  FROM detalle_comisiones WHERE chica_id = :id_usuario AND estado = 1";
        $params = [
            ':id_usuario' => $id_usuario
        ];
        try {
            $data = $this->selectAll($sql, $params);
            return $data;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getComision(int $id_comision)
    {
        $sql = "SELECT * FROM comisiones WHERE id_comision = :id_comision AND estado = 1";
        $params = [
            ':id_comision' => $id_comision
        ];
        try {
            $data = $this->select($sql, $params);
            return $data;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function updateComsion(array $datos)
    {
        $sql = "UPDATE comisiones SET monto = :monto , estado = :estado, fecha_mod = now() WHERE id_comision = :id_comision";
        $params = [
            ':monto' => $datos['monto'],
            ':estado' => $datos['estado'],
            ':id_comision' => $datos['id_comision']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function updateDetalleComision(int $id_usuario)
    {
        $sql = 'UPDATE detalle_comisiones SET comision = 0, estado = 0 WHERE chica_id = :chica_id AND estado = 1';
        $params = [
            ':chica_id' => $id_usuario
        ];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }


    public function getPropina_id(int $id_usuario)
    {
        $sql = "SELECT propina_id, monto FROM detalle_propinas WHERE usuario_id = :id_usuario AND estado = 1";
        $params = [
            ':id_usuario' => $id_usuario
        ];
        try {
            $data = $this->selectAll($sql, $params);
            return $data;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getPropina(int $id_propina)
    {
        $sql = 'SELECT * FROM propinas WHERE id_propina = :id_propina AND estado = 1';
        $params = [
            ':id_propina' => $id_propina
        ];

        try {
            $data = $this->select($sql, $params);
            return $data;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function updatePropina(array $datos)
    {
        $sql = "UPDATE propinas SET propina = :propina , estado = :estado, fecha_mod = now() WHERE id_propina = :id_propina";
        $params = [
            ':propina' => $datos['propina'],
            ':estado' => $datos['estado'],
            ':id_propina' => $datos['id_propina']
        ];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function updatedetallePropina(int $id_usuario)
    {
        $sql = "UPDATE detalle_propinas SET monto = 0, estado = 0 WHERE usuario_id = :usuario_id";
        $params = [
            ':usuario_id' => $id_usuario
        ];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getHorasExtra(int $id_usuario)
    {
        $sql = "SELECT * FROM horas_extras WHERE usuario_id = :id_usuario AND estado = 1";
        $params = [
            ':id_usuario' => $id_usuario
        ];
        try {
            $data = $this->selectAll($sql, $params);
            return $data;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function updateHorasExtra(int $id_usuario)
    {
        $sql = 'UPDATE horas_extras SET monto = 0, estado = 0 WHERE usuario_id = :id_usuario AND estado = 1';
        $params = [
            ':id_usuario' => $id_usuario
        ];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function updateSueldo(int $id_usuario)
    {
        $sql = 'UPDATE asistencia SET estado = 0 WHERE usuario_id = :id_usuario AND estado = 1';
        $params = [
            ':id_usuario' => $id_usuario
        ];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
