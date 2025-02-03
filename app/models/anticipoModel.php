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
        $sql = "SELECT A.id_anticipo, A.usuario_id, A.monto, A.fecha_crea, A.estado
                FROM anticipos AS A
                WHERE A.usuario_id = :id_usuario
                ORDER BY A.fecha_crea DESC";
        
        $sqlTotal = "SELECT SUM(monto) AS total FROM anticipos WHERE usuario_id = :id_usuario";
        
        $params = [':id_usuario' => $id_usuario];
    
        try {
            $result = $this->selectAll($sql, $params);
            
            $totalResult = $this->select($sqlTotal, $params);
            $total = $totalResult['total'] ?? 0; 
            
            return [
                'total' => (int) $total,
                'anticipos' => array_map(function ($row) {
                    return [
                        'id_anticipo' => (int) $row['id_anticipo'],
                        'usuario_id'  => (int) $row['usuario_id'],
                        'monto'       => (int) $row['monto'],
                        'fecha_crea'  => (string) $row['fecha_crea'],
                        'estado'      => (int) $row['estado'],
                    ];
                }, $result),
            ];
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

   /*  public function getAnticipoUsuario(int $id_usuario)
    {
        $sql = 'SELECT id_anticipo, usuario_id, monto, estado 
                FROM anticipos 
                WHERE usuario_id = :id_usuario 
                AND estado = 1';
        $params = [
            ':id_usuario' => $id_usuario
        ];
        try {
            return $this->selectAll($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    } */

}
