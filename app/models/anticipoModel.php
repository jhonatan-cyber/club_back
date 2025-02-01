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

    public function getAnticipoUsuario(int $id_usuario): array
    {
        $sql = 'SELECT A.id_anticipo, A.monto, A.fecha_crea, A.estado, (SELECT SUM(monto) 
                FROM anticipos WHERE usuario_id = :id_usuario) AS total FROM anticipos AS A 
                INNER JOIN usuarios AS U ON A.usuario_id = U.id_usuario 
                WHERE A.usuario_id = :id_usuario ORDER BY A.fecha_crea DESC';
        $params = [
            ':id_usuario' => $id_usuario
        ];
        try {
            $result = $this->selectAll($sql, $params);

            return array_map(function ($row) {
                return [
                    'estado'          => (int) $row['estado'],
                    'fecha_crea'      => (string) $row['fecha_crea'],
                    'id_anticipo' => (int) $row['id_anticipo'],
                    'total'           => (int) $row['total'],
                    'monto'           => (int) $row['monto'],
                ];
            }, $result);
        } catch (Exception $e) {
            return response::estado500($e);
        }
        
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

    public function getLastAnticipo()
    {
        $sql = 'SELECT MAX(id_anticipo) AS id_anticipo FROM anticipos LIMIT 1';
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
