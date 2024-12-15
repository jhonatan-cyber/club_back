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
        $sql = 'INSERT INTO cajas (monto_apertura,usuario_id_apertura) VALUES (:monto_apertura,:usuario_id_apertura)';
        $params = [
            ':monto_apertura' => $data['monto_apertura'],
            ':usuario_id_apertura' => $data['usuario_id_apertura']
        ];
        try {
            $data = $this->save($sql, $params);
            return $data == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getCajas()
    {
        $sql = 'SELECT * FROM cajas';
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function cerrarCaja(array $data)
    {
        $sql = 'UPDATE cajas SET estado = 0,monto_cierre = monto_cierre + monto_apertura, usuario_id_cierre = :usuario_id_cierre,
        fecha_cierre = now() WHERE id_caja = :id_caja';
        $params = [
            ':id_caja'=> $data['id_caja'],
            ':usuario_id_cierre'=> $data['usuario_id_cierre']
        ];
        try {
            $data = $this->save($sql, $params);
            return $data == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
