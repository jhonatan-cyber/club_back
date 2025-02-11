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

    public function createPropina(int $propina)
    {
        $sql1 = 'SELECT * FROM propinas WHERE fecha = CURDATE() AND estado = 1';
        try {
            $data = $this->select($sql1);

            if ($data) {
                $sql2 = 'UPDATE propinas SET propina = propina + :propina WHERE fecha = CURDATE() AND estado = 1';
                $params = [
                    ':propina' => $propina
                ];
                $resp = $this->save($sql2, $params);
                return $resp === true ? 'ok' : 'error';
            } else {
                $sql3 = 'INSERT INTO propinas (propina, fecha) VALUES (:propina, CURDATE())';
                $params = [
                    ':propina' => $propina
                ];
                $resp = $this->save($sql3, $params);
                return $resp === true ? 'ok' : 'error';
            }
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createDetallePropina(array $data)
    {

        $checkSql = 'SELECT id_detalle_propina FROM detalle_propinas WHERE usuario_id = :usuario_id AND estado = 1';
        $checkParams = [':usuario_id' => $data['usuario_id']];

        try {
            $existing = $this->select($checkSql, $checkParams);

            if ($existing) {
                $updateSql = 'UPDATE detalle_propinas SET monto = monto + :monto WHERE id_detalle_propina = :id_detalle_propina';
                $updateParams = [
                    ':monto' => $data['monto'],
                    ':id_detalle_propina' => $existing['id_detalle_propina']
                ];
                $resp = $this->save($updateSql, $updateParams);
            } else {
                $insertSql = 'INSERT INTO detalle_propinas (propina_id, usuario_id, monto) 
                             VALUES (:propina_id, :usuario_id, :monto)';
                $insertParams = [
                    ':propina_id' => $data['propina_id'],
                    ':usuario_id' => $data['usuario_id'],
                    ':monto' => $data['monto']
                ];
                $resp = $this->save($insertSql, $insertParams);
            }

            return $resp === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getLastPropina()
    {
        $sql = 'SELECT MAX(id_propina) AS id_propina FROM propinas WHERE estado = 1';
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function updatePropinaDevolcion(int $propina)
    {
        $querySelect = "SELECT propina FROM propinas WHERE estado = 1";
        $queryUpdate = "UPDATE propinas SET propina = propina - :propina, fecha_mod = now() WHERE estado = 1";
        $params = [':propina' => $propina];

        try {
            $data = $this->select($querySelect);

            if (!empty($data)) {
                $propinaActual = (int)$data['propina'];

                if ($propinaActual > 0) {

                    if ($propinaActual >= $propina) {

                        $result = $this->save($queryUpdate, $params);
                        return $result === true ? 'ok' : 'error';
                    }
                }
            }

        } catch (Exception $e) {
            return response::estado500($e->getMessage());
        }
    }

    public function getPropinaVenta(int $venta_id)
    {
        $sql = "SELECT V.total - (SUM(DV.cantidad * DV.precio) + V.iva) AS propina
                FROM detalle_ventas AS DV INNER JOIN ventas AS V ON DV.venta_id = V.id_venta
                WHERE V.id_venta = :venta_id";
        $params = [':venta_id' => $venta_id];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
