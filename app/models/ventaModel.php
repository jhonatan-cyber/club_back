<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class ventaModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createVenta(array $venta)
    {
        $requiredFields = ['codigo', 'cliente_id', 'usuario_id', 'metodo_pago', 'total', 'total_comision'];
        foreach ($requiredFields as $field) {
            if (!isset($venta[$field])) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }
        try {
            $sql = "INSERT INTO ventas (codigo, cliente_id, usuario_id, metodo_pago, total, total_comision) VALUES (:codigo, :cliente_id, :usuario_id, :metodo_pago, :total, :total_comision)";
            $params = [
                ':codigo' => $venta['codigo'],
                ':cliente_id' => $venta['cliente_id'],
                ':usuario_id' => $venta['usuario_id'],
                ':metodo_pago' => $venta['metodo_pago'],
                ':total' => $venta['total'],
                ':total_comision' => $venta['total_comision']
            ];
            $res = $this->save($sql, $params);
            return $res == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log('VentaModel::createVenta() -> ' . $e);
            return response::estado500($e);
        }

    }
    public function lastVenta()
    {
        $sql = "SELECT id_venta FROM ventas ORDER BY id_venta DESC LIMIT 1";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            error_log("VentaModel::lastVenta() -> " . $e);
            return response::estado500($e);
        }
    }
    public function createDetalleVenta(array $data)
    {
        $requiredFields = ["venta_id", "producto_id", "precio", "cantidad", 'comision', "sub_total"];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return response::estado400("El campo $field es requerido");

            }
        }
        $sql = "INSERT INTO detalle_ventas (venta_id, producto_id, precio, comision, cantidad, sub_total) VALUES (:venta_id, :producto_id, :precio, :comision, :cantidad, :sub_total)";
        $params = [
            ":venta_id" => $data['venta_id'],
            ':producto_id' => $data['producto_id'],
            ':precio' => $data['precio'],
            ':comision' => $data['comision'],
            ':cantidad' => $data['cantidad'],
            ':sub_total' => $data['sub_total']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log('VentaModel::createDetalleVenta() -> ' . $e);
            return response::estado500($e);
        }
    }

    public function updatePedido(int $id_pedido)
    {
        $sql = "UPDATE pedidos SET estado = 0 WHERE id_pedido = :id_pedido";
        $params = [
            ':id_pedido' => $id_pedido
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log("PedidoModel::updatePedido() -> " . $e);
            return response::estado500($e);
        }
    }
}