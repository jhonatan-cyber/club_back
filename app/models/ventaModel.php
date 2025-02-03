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

    public function getVentas()
    {
        $sql = 'SELECT V.id_venta,V.pieza_id,V.codigo,V.metodo_pago,V.total_comision,V.total, V.fecha_crea, C.nombre AS nombre_c,C.apellido AS apellido_c FROM ventas AS V 
        JOIN clientes AS C ON V.cliente_id = C.id_cliente  WHERE V.estado=1 ORDER BY V.fecha_crea DESC';
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createVenta(array $venta)
    {
        $requiredFields = ['codigo', 'cliente_id', 'metodo_pago', 'total', 'total_comision'];
        foreach ($requiredFields as $field) {
            if (!isset($venta[$field])) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }

            $venta['pieza_id'] = isset($venta['pieza_id']) ? $venta['pieza_id'] : 0;
            $venta['iva'] = isset($venta['iva']) ? $venta['iva'] : 0;
        }

        try {
            $sql = 'INSERT INTO ventas (codigo, cliente_id, pieza_id, metodo_pago, iva, total, total_comision) 
            VALUES (:codigo, :cliente_id, :pieza_id, :metodo_pago, :iva, :total, :total_comision)';
            $params = [
                ':codigo' => $venta['codigo'],
                ':cliente_id' => $venta['cliente_id'],
                ':pieza_id' => $venta['pieza_id'],
                ':metodo_pago' => $venta['metodo_pago'],
                ':iva' => $venta['iva'],
                ':total' => $venta['total'],
                ':total_comision' => $venta['total_comision']
            ];
            $res = $this->save($sql, $params);
            return $res === true ? 'ok' : 'error';
        } catch (Exception $e) {
            error_log('VentaModel::createVenta() -> ' . $e);
            return response::estado500($e);
        }
    }

    public function getLastVenta()
    {
        $sql = 'SELECT MAX(id_venta) AS id_venta FROM ventas';
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createDetalleVenta(array $data)
    {
        $requiredFields = ['venta_id', 'producto_id', 'precio', 'cantidad', 'comision', 'sub_total'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return response::estado400("El campo $field es requerido");
            }
        }
        $sql = 'INSERT INTO detalle_ventas (venta_id, producto_id, precio, comision, cantidad, sub_total) VALUES (:venta_id, :producto_id, :precio, :comision, :cantidad, :sub_total)';
        $params = [
            ':venta_id' => $data['venta_id'],
            ':producto_id' => $data['producto_id'],
            ':precio' => $data['precio'],
            ':comision' => $data['comision'],
            ':cantidad' => $data['cantidad'],
            ':sub_total' => $data['sub_total']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? 'ok' : 'error';
        } catch (Exception $e) {
            error_log('VentaModel::createDetalleVenta() -> ' . $e);
            return response::estado500($e);
        }
    }

    public function getVenta(int $id_venta)
    {
        $sql = 'SELECT D.id_detalle_venta, D.precio, D.comision, D.cantidad, D.sub_total,
                V.codigo, V.metodo_pago, V.total, V.total_comision, V.fecha_crea, 
                P.nombre AS producto, C.nombre AS categoria, 
                CL.nombre AS nombre_c, CL.apellido AS apellido_a, 
                U.nombre AS nombre_u, U.apellido AS apellido_u 
                FROM detalle_ventas AS D 
                JOIN ventas AS V ON D.venta_id = V.id_venta 
                JOIN productos AS P ON D.producto_id = P.id_producto 
                JOIN categorias AS C ON P.categoria_id = C.id_categoria 
                JOIN clientes AS CL ON V.cliente_id = CL.id_cliente 
                LEFT JOIN usuario_venta AS UV ON V.id_venta = UV.venta_id 
                LEFT JOIN usuarios AS U ON UV.usuario_id = U.id_usuario 
                WHERE V.id_venta = :id_venta';
        $params = [
            ':id_venta' => $id_venta
        ];
        try {
            return $this->selectAll($sql, $params);
        } catch (Exception $e) {
            error_log('DetalleVentaModel::getVenta() -> ' . $e);
            return response::estado500($e);
        }
    }

    public function createUsuarioVenta(array $datos)
    {
        $sql = 'INSERT INTO usuario_venta (usuario_id,venta_id) VALUES (:usuario_id,:venta_id)';
        $params = [
            ':usuario_id' => $datos['usuario_id'],
            ':venta_id' => $datos['venta_id']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? 'ok' : 'error';
        } catch (Exception $e) {
            error_log('UsuarioVentaModel::createUsuarioVenta() -> ' . $e);
            return response::estado500($e);
        }
    }

    public function getMeserosCajera()
    {
        $sql = 'SELECT L.* FROM logins AS L 
                INNER JOIN usuarios AS U ON L.usuario_id =U.id_usuario
                INNER JOIN roles AS R ON U.rol_id = R.id_rol
                WHERE L.estado = 1 AND R.nombre = "Mesero" OR R.nombre = "Cajero"';
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

  
    
}
