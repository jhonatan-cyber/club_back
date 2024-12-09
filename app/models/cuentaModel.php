<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class cuentaModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCuentas()
    {
        $sql = 'SELECT 
                C.id_cuenta,
                C.codigo,
                C.total,
                C.fecha_crea,
                CONCAT(CL.nombre, " ", CL.apellido) as cliente,
                CASE 
                    WHEN C.servicio_id != 0 THEN "Servicio"
                    WHEN C.pedido_id != 0 THEN "Pedido"
                END as tipo
                FROM cuentas AS C 
                JOIN clientes AS CL ON C.cliente_id = CL.id_cliente 
                WHERE C.estado = 1 
                AND (C.servicio_id != 0 OR C.pedido_id != 0)
                ORDER BY C.fecha_crea DESC';
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createCuenta(array $data)
    {
        $sql = 'INSERT INTO cuentas (codigo, cliente_id, servicio_id, pedido_id, total_comision, total) 
        VALUES (:codigo, :cliente_id, :servicio_id, :pedido_id, :total_comision, :total)';
        $params = [
            ':codigo' => $data['codigo'],
            ':cliente_id' => $data['cliente_id'],
            ':servicio_id' => $data['servicio_id'],
            ':pedido_id' => $data['pedido_id'],
            ':total_comision' => $data['total_comision'],
            ':total' => $data['total']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getDetalleCuentas(int $cuenta_id)
    {
        $sql = 'SELECT D.cuenta_id, D.precio, D.cantidad, D.subtotal, D.comision, C.fecha_crea, 
                C.cliente_id, C.codigo, C.total_comision, C.total, C.metodo_pago, CL.nombre AS nombre_cliente, 
                CL.apellido AS apellido_cliente, CL.id_cliente, U.id_usuario, U.nombre AS nombre_usuario, 
                U.apellido AS apellido_usuario, PR.id_producto, PR.nombre AS nombre_producto, 
                CT.nombre AS nombre_categoria FROM detalle_cuentas AS D 
                JOIN cuentas AS C ON D.cuenta_id = C.id_cuenta 
                LEFT JOIN servicios AS S ON C.servicio_id = S.id_servicio 
                JOIN clientes AS CL ON C.cliente_id = CL.id_cliente 
                LEFT JOIN detalle_servicios AS DS ON C.servicio_id = DS.servicio_id 
                LEFT JOIN pedidos AS P ON C.pedido_id = P.id_pedido 
                LEFT JOIN usuarios AS U ON 
                CASE 
                    WHEN DS.usuario_id IS NOT NULL THEN DS.usuario_id
                    WHEN P.cliente_id IS NOT NULL THEN P.cliente_id
                    ELSE C.servicio_id
                END = U.id_usuario
                JOIN productos AS PR ON D.producto_id = PR.id_producto 
                JOIN categorias AS CT ON PR.categoria_id = CT.id_categoria 
                WHERE (C.servicio_id != 0 OR C.pedido_id != 0) AND D.cuenta_id = :cuenta_id';
        $params = [':cuenta_id' => $cuenta_id];
        try {
            return $this->selectAll($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function cobrarCuenta(array $cuenta)
    {
        $sql = 'UPDATE cuentas SET metodo_pago = :metodo_pago, estado = 0, fecha_mod = now() WHERE id_cuenta = :id_cuenta';
        $params = [
            ':metodo_pago' => $cuenta['metodo_pago'],
            ':id_cuenta' => $cuenta['id_cuenta']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createDetalleCuenta(array $data)
    {
        $requiredFields = ['cuenta_id', 'producto_id', 'precio', 'cantidad', 'comision', 'subtotal'];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                error_log("El campo $field es requerido");
                return response::estado400("El campo $field es requerido");
            }
        }

        $sql = 'INSERT INTO detalle_cuentas (cuenta_id, producto_id, precio, cantidad, subtotal, comision) 
                VALUES (:cuenta_id, :producto_id, :precio, :cantidad, :subtotal, :comision)';

        $params = [
            ':cuenta_id' => $data['cuenta_id'],
            ':producto_id' => $data['producto_id'],
            ':precio' => $data['precio'],
            ':comision' => $data['comision'],
            ':cantidad' => $data['cantidad'],
            ':subtotal' => $data['subtotal']
        ];

        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            error_log('Error en createDetalleCuenta: ' . $e->getMessage());
            return response::estado500('Error al crear el detalle de la cuenta. Por favor, intenta de nuevo.');
        }
    }

    public function updateCuenta(array $cuenta)
    {
        $sql = 'UPDATE cuentas 
                SET 
                    total_comision = total_comision + :total_comision,
                    total = total + :sub_total
                WHERE 
                    id_cuenta = :id_cuenta';

        $params = [
            ':sub_total' => $cuenta['sub_total'],
            ':total_comision' => $cuenta['total_comision'],
            ':id_cuenta' => $cuenta['id_cuenta']
        ];

        try {
            $result = $this->save($sql, $params);
            return $result == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function updatePedido(int $id_pedido)
    {
        $sql = 'UPDATE pedidos SET estado = 0 WHERE id_pedido = :id_pedido';
        $params = [
            ':id_pedido' => $id_pedido
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getLastCuenta()
    {
        $sql = 'SELECT id_cuenta FROM cuentas ORDER BY id_cuenta DESC LIMIT 1';
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500('asdasd');
        }
    }

    public function getCuentaCliente(int $cliente_id)
    {
        $sql = 'SELECT * FROM cuentas WHERE cliente_id = :cliente_id';
        $params = [':cliente_id' => $cliente_id];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
