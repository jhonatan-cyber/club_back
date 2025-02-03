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

    public function createPedido(array $data): string
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

    public function createDetallePedido(array $data): string
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
            return response::estado500($e);
        }
    }
    public function getPedidos()
    {
        $sql = "SELECT P.id_pedido, P.codigo, P.subtotal, P.total, P.estado, CONCAT(C.nombre, ' ', C.apellido) AS cliente, 
                GROUP_CONCAT(DISTINCT CH.nick ORDER BY CH.nick SEPARATOR ', ') AS nicks, CONCAT(M.nombre, ' ', M.apellido) AS garzon 
                FROM pedidos AS P JOIN clientes AS C ON P.cliente_id = C.id_cliente 
                JOIN pedidos_usuarios AS PU ON PU.pedido_id = P.id_pedido JOIN usuarios AS CH ON CH.id_usuario = PU.usuario_id  
                JOIN usuarios AS M ON P.mesero_id = M.id_usuario WHERE P.estado = 1  
                GROUP BY P.id_pedido ORDER BY P.id_pedido";
        try {
            $result = $this->selectAll($sql);
            return array_map(function ($row) {
                return [
                    'cliente'          => (string) $row['cliente'],
                    'codigo'      => (string) $row['codigo'],
                    'estado'          => (int) $row['estado'],
                    'garzon'          => (string) $row['garzon'],
                    'id_pedido' => (int) $row['id_pedido'],
                    'nicks'           => (string) $row['nicks'],
                    'subtotal'       => (int) $row['subtotal'],
                    'total'           => (int) $row['total'],
                ];
            }, $result);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function getPedidosGarzon(int $usuario_id)
    {
        $params = [':usuario_id' => $usuario_id];
        $sql = "SELECT P.id_pedido, P.codigo, P.subtotal, P.total, P.estado, CONCAT(C.nombre, ' ', C.apellido) AS cliente, 
                GROUP_CONCAT(DISTINCT CH.nick ORDER BY CH.nick SEPARATOR ', ') AS nicks, CONCAT(M.nombre, ' ', M.apellido) AS garzon 
                FROM pedidos AS P JOIN clientes AS C ON P.cliente_id = C.id_cliente 
                JOIN pedidos_usuarios AS PU ON PU.pedido_id = P.id_pedido JOIN usuarios AS CH ON CH.id_usuario = PU.usuario_id  
                JOIN usuarios AS M ON P.mesero_id = M.id_usuario WHERE P.estado = 1 AND M.id_usuario = :usuario_id 
                GROUP BY P.id_pedido ORDER BY P.id_pedido";
        try {
            $result = $this->selectAll($sql, $params);
            return array_map(function ($row) {
                return [
                    'cliente'          => (string) $row['cliente'],
                    'codigo'      => (string) $row['codigo'],
                    'estado'          => (int) $row['estado'],
                    'garzon'          => (string) $row['garzon'],
                    'id_pedido' => (int) $row['id_pedido'],
                    'nicks'           => (string) $row['nicks'],
                    'subtotal'       => (int) $row['subtotal'],
                    'total'           => (int) $row['total'],
                ];
            }, $result);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function getDetallePedido(int $pedido_id)
    {
        $sql = "SELECT P.codigo, P.id_pedido, C.nombre AS categoria, PR.id_producto, 
                GROUP_CONCAT(DISTINCT CH.id_usuario ORDER BY CH.nombre SEPARATOR ', ') AS anfitriona_ids,
                PR.nombre AS producto, D.precio, D.comision, D.cantidad, D.subtotal, P.cliente_id,
                P.subtotal AS total_subtotal, P.total, P.total_comision,
                GROUP_CONCAT(DISTINCT CONCAT(CH.nick) ORDER BY CH.nombre SEPARATOR ', ') AS Anfitriona,
                CONCAT(CL.nombre, ' ', CL.apellido) AS cliente, CONCAT(M.nombre, ' ', M.apellido) AS garzon
                FROM detalle_pedidos AS D
                INNER JOIN pedidos AS P ON D.pedido_id = P.id_pedido
                INNER JOIN productos AS PR ON D.producto_id = PR.id_producto
                INNER JOIN categorias AS C ON PR.categoria_id = C.id_categoria
                INNER JOIN pedidos_usuarios AS PU ON PU.pedido_id = P.id_pedido
                INNER JOIN usuarios AS CH ON CH.id_usuario = PU.usuario_id
                INNER JOIN usuarios AS M ON P.mesero_id = M.id_usuario
                INNER JOIN clientes AS CL ON P.cliente_id = CL.id_cliente
                WHERE D.pedido_id = :pedido_id AND P.estado = 1
                GROUP BY P.id_pedido, PR.id_producto, D.id_detalle_pedido, CL.id_cliente, M.id_usuario;";
        $params = [
            ":pedido_id" => $pedido_id
        ];
        try {
            $result = $this->selectAll($sql, $params);
            return array_map(function ($row) {
                return [
                    'Anfitriona'      => (string) $row['Anfitriona'],
                    'cantidad'        => (int) $row['cantidad'],
                    'categoria'       => (string) $row['categoria'],
                    'cliente'         => (string) $row['cliente'],
                    'cliente_id'      => (int) $row['cliente_id'],
                    'codigo'          => (string) $row['codigo'],
                    'comision'        => (int) $row['comision'],
                    'garzon'          => (string) $row['garzon'],
                    'id_pedido'       => (int) $row['id_pedido'],
                    'id_producto'     => (int) $row['id_producto'],
                    'precio'          => (int) $row['precio'],
                    'producto'        => (string) $row['producto'],
                    'subtotal'        => (int) $row['subtotal'],
                    'total'           => (int) $row['total'],
                    'total_comision'  => (int) $row['total_comision'],
                    'total_subtotal'  => (int) $row['total_subtotal'],
                    'anfitriona_id'  => (string) $row['anfitriona_ids']
                ];
            }, $result);
        } catch (Exception $e) {

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
    public function updatePedido(int $id_pedido)
    {
        $sql = 'UPDATE pedidos SET estado = 0 WHERE id_pedido = :id_pedido';
        $params = [
            ':id_pedido' => $id_pedido
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }


}
