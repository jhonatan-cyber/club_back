<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class cajaModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createCaja(array $data)
    {
         $required = ['monto_apertura', 'usuario_id_apertura'];
        foreach ($required as $value) {
            if (!isset($data[$value])) {
                return response::estado400('El campo ' . $value . ' es requerido');
            }
        }
        $sql = "INSERT INTO cajas (usuario_id_apertura, monto_apertura) VALUES (:usuario_id_apertura, :monto_apertura)";
        $params = [
            ':usuario_id_apertura' => $data['usuario_id_apertura'],
            ':monto_apertura' => $data['monto_apertura'],
        ];
        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getCajas()
    {
        $sql = 'SELECT * FROM cajas';
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function cerrarCaja(array $data)
    {
        $sql = 'UPDATE cajas SET estado = 0, monto_cierre = monto_cierre + monto_apertura, usuario_id_cierre = :usuario_id_cierre,
        fecha_cierre = now() WHERE id_caja = :id_caja';
        $params = [
            ':id_caja'=> $data['id_caja'],
            ':usuario_id_cierre'=> $data['usuario_id_cierre']
        ];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
