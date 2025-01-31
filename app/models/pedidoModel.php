<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class pedidoModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }



    public function createPedido(array $data)
    {
        $requiredFields = ['mesero_id', 'subtotal', 'total', 'codigo'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }
        $sql = 'INSERT INTO pedidos (codigo, mesero_id, cliente_id, subtotal, total, total_comision) 
        VALUES (:codigo, :mesero_id, :cliente_id, :subtotal, :total, :total_comision)';
        $params = [
            ':codigo' => $data['codigo'],
            ':mesero_id' => $data['mesero_id'],
            ':cliente_id' => $data['cliente_id'],
            ':subtotal' => $data['subtotal'],
            ':total' => $data['total'],
            ':total_comision' => $data['total_comision']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? "ok" : "error";
        } catch (Exception $e) {
    
            return response::estado500("asdasd");
        }
    }

    public function createDetallePedido(array $data)
    {
        $requiredFields = ["pedido_id", "producto_id", "precio", "cantidad", "comision", "subtotal"];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return response::estado400("El campo $field es requerido");
            }
        }
        $sql = 'INSERT INTO detalle_pedidos (pedido_id, producto_id, precio, comision, cantidad, subtotal)
                VALUES (:pedido_id, :producto_id, :precio, :comision, :cantidad, :subtotal)';
        $params = [
            ':pedido_id' => $data['pedido_id'],
            ':producto_id' => $data['producto_id'],
            ':precio' => $data['precio'],
            ':comision' => $data['comision'],
            ':cantidad' => $data['cantidad'],
            ':subtotal' => $data['subtotal']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function getLastPedido()
    {
        $sql = "SELECT id_pedido FROM pedidos ORDER BY id_pedido DESC LIMIT 1";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500("asdasd");
        }
    }
    public function getPedidos()
    {
        $sql = "SELECT P.id_pedido,P.codigo,P.subtotal,P.total, C.nombre AS nombre_c,
         C.apellido AS apellido_c, CH.nombre AS nombre_ch, CH.apellido AS apellido_ch,
         M.nombre AS nombre_m,M.apellido AS apellido_m 
         FROM pedidos AS P 
         JOIN clientes AS C ON P.cliente_id = C.id_cliente 
         JOIN usuarios AS CH ON P.chica_id = CH.id_usuario 
         JOIN usuarios AS M ON P.mesero_id = M.id_usuario 
         WHERE P.estado = 1";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            error_log("PedidoModel::getPedidos() -> " . $e);
            return response::estado500($e);
        }
    }
    public function getDetallePedido(int $pedido_id)
    {
        $sql = "SELECT P.codigo, P.id_pedido, C.nombre AS categoria,PR.id_producto,
        PR.nombre, D.precio, D.comision, D.cantidad, D.subtotal, P.chica_id, P.cliente_id,
        P.subtotal AS total_subtotal, P.total, P.total_comision, CH.nombre AS nombre_ch,
        CH.apellido AS apellido_ch, CL.nombre AS nombre_cl, CL.apellido AS apellido_cl,
         M.nombre AS nombre_m, M.apellido AS apellido_m 
         FROM detalle_pedidos AS D 
         INNER JOIN pedidos AS P ON D.pedido_id = P.id_pedido 
         INNER JOIN productos AS PR ON D.producto_id = PR.id_producto 
         INNER JOIN categorias AS C ON PR.categoria_id = C.id_categoria 
         INNER JOIN usuarios AS CH ON P.chica_id = CH.id_usuario 
         INNER JOIN usuarios AS M ON P.mesero_id = M.id_usuario 
         INNER JOIN clientes AS CL ON P.cliente_id = CL.id_cliente 
         WHERE D.pedido_id = :pedido_id AND P.estado = 1";
        $params = [
            ":pedido_id" => $pedido_id
        ];
        try {
            return $this->selectAll($sql, $params);
        } catch (Exception $e) {
            error_log("PedidoModel::getDetallePedido() -> " . $e);
            return response::estado500($e);
        }
    }
    public function createPedidoUsuario(array $datos)
    {
        $sql = 'INSERT INTO pedidos_usuarios (usuario_id,pedido_id) VALUES (:usuario_id,:pedido_id)';
        $params = [
            ':usuario_id' => $datos['usuario_id'],
            ':pedido_id' => $datos['pedido_id']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
