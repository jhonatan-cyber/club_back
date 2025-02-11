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

    /**
     * Registro de caja 
     *
     * @param array $data
     * @return string
     */
    public function createCaja(array $data): string
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

    /**
     * Obtiene todas las cajas de la base de datos
     *
     * @return array
     */
    public function getCajas(): array
    {
        $sql = 'SELECT * FROM cajas ORDER BY fecha_apertura DESC';
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    /**
     * Cierra la caja abierta
     *
     * @param array $data
     * @return string
     */
    public function cerrarCaja(array $caja): string
    {
        $sql = 'UPDATE cajas SET estado = 0, monto_cierre = monto_cierre + monto_apertura, usuario_id_cierre = :usuario_id_cierre,
        fecha_cierre = now() WHERE id_caja = :id_caja';
        $params = [
            ':id_caja' => $caja['id_caja'],
            ':usuario_id_cierre' => $caja['usuario_id_cierre']
        ];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    /**
     * Obtiene una caja en especifico
     *
     * @param integer $id_caja
     * @return array
     */
    public function getDetalleCaja(int $id_caja): array
    {
        $sql = 'SELECT * FROM cajas WHERE id_caja = :id_caja';
        $params = [':id_caja' => $id_caja];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function updateCaja(array $data)
    {
        $sql = 'UPDATE cajas SET ventas_realizadas = ventas_realizadas + 1, monto_cierre = monto_cierre + :monto_cierre, monto_trasferencia = monto_trasferencia + :monto_trasferencia WHERE estado = 1';
        $params = [
            ':monto_cierre' => $data['monto_cierre'],
            ':monto_trasferencia' => $data['monto_trasferencia'],
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function updateCajaDevolucion(int $total)
    {
        $sql = "UPDATE cajas SET monto_cierre= monto_cierre - :total, ventas_realizadas=ventas_realizadas - 1 WHERE estado = 1";
        $params = [':total' => $total];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
