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

    public function getComisionesUsuario(int $usuario_id): array
    {
        $sql = 'SELECT DC.estado, D.fecha_crea, DC.comision, (SELECT SUM(DC1.comision) 
                FROM detalle_comisiones AS DC1 
                WHERE DC1.chica_id = DC.chica_id) AS total 
                FROM detalle_comisiones AS DC
                INNER JOIN comisiones AS D ON DC.comision_id = D.id_comision
                WHERE DC.chica_id = :usuario_id';
        $params = [':usuario_id' => $usuario_id];
        try {
            $result = $this->selectAll($sql, $params);
            return array_map(function ($row) {
                return [
                    'comision'    => (int) $row['comision'],
                    'fecha_crea'  => (string) $row['fecha_crea'],
                    'estado'      => (int) $row['estado'],
                    'total'       => (int) $row['total'],
                ];
            }, $result);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getDetatalleComisionUsuario(int $chica_id)
    {
        $sql = 'SELECT * FROM detalle_comisiones WHERE chica_id = :chica_id AND estado = 1';
        $params = [
            ':chica_id' => $chica_id
        ];
        try {
            return $this->selectAll($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function updateDetalleComision(array $datos)
    {
        $sql = 'UPDATE detalle_comisiones  SET estado = :estado WHERE id_detalle_comision = :id_detalle_comision';
        $params = [
            ':id_detalle_comision' => $datos['id_detalle_comision'],
            ':estado' => $datos['estado']
        ];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            error_log($e->getMessage());
            return response::estado500($e);
        }
    }

    public function getComision(int $id_comision)
    {
        $sql = 'SELECT * FROM comisiones WHERE id_comision = :id_comision';
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

    public function updateComision(array $datos)
    {
        $sql = 'UPDATE comisiones SET estado = :estado, fecha_mod = now() WHERE id_comision = :id_comision';
        $params = [
            ':id_comision' => $datos['id_comision'],
            ':estado' => $datos['estado']
        ];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getDetalleComisionUsuario(int $chica_id)
    {
        $sql = 'SELECT D.comision_id , C.monto
                FROM detalle_comisiones AS D
                INNER JOIN comisiones AS C ON 
                D.comision_id = C.id_comision WHERE D.chica_id = :chica_id AND D.estado = 1 AND C.estado = 1';
        $params = [
            ':chica_id' => $chica_id
        ];
        try {
            $data = $this->selectAll($sql, $params);
            return $data;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
