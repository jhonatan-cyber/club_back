<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class anticipoModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAnticipos()
    {
        $sql = 'SELECT A.id_anticipo,A.monto,A.fecha_crea,A.estado,U.id_usuario,U.nombre,U.apellido 
        FROM anticipos AS A INNER JOIN usuarios AS U ON A.usuario_id = U.id_usuario ORDER BY A.fecha_crea DESC';
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getAnticipoUsuario(int $id_usuario)
    {
        $sql = 'SELECT 
                    A.id_anticipo, 
                    A.monto, 
                    A.fecha_crea, 
                    A.estado, 
                    (SELECT SUM(monto) 
                     FROM anticipos 
                     WHERE usuario_id = :id_usuario) AS total
                FROM anticipos AS A 
                INNER JOIN usuarios AS U 
                ON A.usuario_id = U.id_usuario 
                WHERE A.usuario_id = :id_usuario 
                ORDER BY A.fecha_crea DESC';
        $params = [
            ':id_usuario' => $id_usuario
        ];
        $data = $this->selectAll($sql, $params);
        return $data;
    }


    public function getAnticipo($id_anticipo)
    {
        $sql = 'SELECT * FROM anticipos  WHERE id_anticipo = :id_anticipo';
        $params = [
            ':id_anticipo' => $id_anticipo
        ];
        $data = $this->select($sql, $params);
        return $data;
    }

    public function createAnticipo(array $data)
    {
        try {
            $sql = 'INSERT INTO anticipos (usuario_id, monto) VALUES (:usuario_id,:monto)';
            $params = [
                ':usuario_id' => $data['usuario_id'],
                ':monto' => $data['monto']
            ];
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function updateAnticipo(int $id_anticipo)
    {
        try {
            $sql = 'UPDATE anticipos SET estado = 0, fecha_mod = now() WHERE id_anticipo = :id_anticipo';
            $params = [
                ':id_anticipo' => $id_anticipo
            ];
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
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

    public function getLastAnticipo()
    {
        $sql = 'SELECT MAX(id_anticipo) AS id_anticipo FROM anticipos LIMIT 1';
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getComisionUsuario(int $chica_id)
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
