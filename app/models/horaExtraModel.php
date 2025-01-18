<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class horaExtraModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getHorasExtras()
    {
        try {
            $sql = 'SELECT 
                    U.id_usuario,
                    U.nombre,
                    U.apellido,
                    SUM(H.hora) as total_horas,
                    SUM(H.monto) as total_monto
                FROM horas_extras AS H 
                JOIN usuarios AS U ON H.usuario_id = U.id_usuario 
                WHERE H.estado = 1
                GROUP BY U.id_usuario, U.nombre, U.apellido
                ORDER BY U.apellido ASC';

            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createHoraExtra(array $datos)
    {
        $sql = 'INSERT INTO horas_extras (usuario_id, hora, monto) VALUES (:usuario_id, :hora, :monto)';
        $params = [
            ':usuario_id' => $datos['usuario_id'],
            ':hora' => $datos['hora'],
            ':monto' => $datos['monto']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getHoraExtra(int $usuario_id){
        try {
            $sql_totales = "SELECT 
                SUM(H.hora) as total_horas,
                SUM(H.monto) as total_monto
            FROM horas_extras H 
            WHERE H.usuario_id = :usuario_id AND H.estado = 1";
            
            $totales = $this->select($sql_totales, [':usuario_id' => $usuario_id]);

            $sql_detalle = "SELECT 
                H.*,
                U.nombre,
                U.apellido,
                DATE_FORMAT(H.fecha_crea, '%d/%m/%Y') as fecha
            FROM horas_extras H
            JOIN usuarios U ON H.usuario_id = U.id_usuario 
            WHERE H.usuario_id = :usuario_id AND H.estado = 1
            ORDER BY H.fecha_crea DESC";
            
            $registros = $this->selectAll($sql_detalle, [':usuario_id' => $usuario_id]);

            return [
                'totales' => $totales,
                'registros' => $registros
            ];
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    
}
