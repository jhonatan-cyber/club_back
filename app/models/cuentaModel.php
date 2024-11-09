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
        $sql = "SELECT C.id_cuenta, C.codigo, C.total , C.fecha_crea,
         CL.nombre ,CL.apellido FROM cuentas AS C 
         LEFT JOIN servicios AS S ON C.servicio_id = S.id_servicio 
         AND C.servicio_id != 0 LEFT JOIN pedidos AS P ON C.pedido_id = P.id_pedido 
         AND C.pedido_id != 0 JOIN clientes AS CL ON C.cliente_id = CL.id_cliente 
         WHERE (C.servicio_id != 0 OR C.pedido_id != 0) AND C.estado=1";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }

    }
    public function getDetalleCuentas(int $cuenta_id)
    {
        $sql = "SELECT D.cuenta_id, D.precio, D.cantidad, D.subtotal, D.comision,C.fecha_crea,C.cliente_id,DS.usuario_id,
          C.codigo, C.sub_total, C.total_comision, C.total, C.metodo_pago, CL.nombre AS nombre_cl, 
          CL.apellido AS apellido_cl, U.nombre AS nombre_u, U.apellido AS apellido_u, PR.nombre AS producto,
          CT.nombre AS categoria FROM detalle_cuentas AS D 
          JOIN cuentas AS C ON D.cuenta_id = C.id_cuenta 
          LEFT JOIN servicios AS S ON C.servicio_id = S.id_servicio 
          JOIN clientes AS CL ON C.cliente_id = CL.id_cliente 
          LEFT JOIN detalle_servicios AS DS ON C.servicio_id = DS.servicio_id 
          LEFT JOIN usuarios AS U ON DS.usuario_id = U.id_usuario 
          LEFT JOIN pedidos AS P ON C.pedido_id = P.id_pedido 
          JOIN productos AS PR ON D.producto_id = PR.id_producto 
          JOIN categorias AS CT ON PR.categoria_id = CT.id_categoria 
          WHERE (C.servicio_id != 0 OR C.pedido_id != 0) AND D.cuenta_id = :cuenta_id";
        $params = [':cuenta_id' => $cuenta_id];
        try {
            return $this->selectAll($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function cobrarCuenta(array $cuenta)
    {
        $sql = "UPDATE cuentas SET metodo_pago = :metodo_pago, estado = 0, fecha_mod = now() WHERE id_cuenta = :id_cuenta";
        $params = [
            ':metodo_pago' => $cuenta['metodo_pago'],
            ':id_cuenta' => $cuenta['id_cuenta']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result == 1 ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function createDetalleCuenta(array $data)
    {
        $requiredFields = ["cuenta_id", "producto_id", "precio", "cantidad", 'comision', "subtotal"];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                error_log("El campo $field es requerido");
                return response::estado400("El campo $field es requerido");
            }
        }

        $sql = "INSERT INTO detalle_cuentas (cuenta_id, producto_id, precio, cantidad, subtotal, comision) 
                VALUES (:cuenta_id, :producto_id, :precio, :cantidad, :subtotal, :comision)";

        $params = [
            ":cuenta_id" => $data['cuenta_id'],
            ":producto_id" => $data['producto_id'],
            ":precio" => $data['precio'],
            ":comision" => $data['comision'],
            ":cantidad" => $data['cantidad'],
            ":subtotal" => $data['subtotal']
        ];

        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {

            error_log("Error en createDetalleCuenta: " . $e->getMessage());
            return response::estado500("Error al crear el detalle de la cuenta. Por favor, intenta de nuevo.");
        }
    }
    public function updateCuenta(array $cuenta)
    {
        $sql = "UPDATE cuentas 
                SET 
                    sub_total = sub_total + :sub_total, 
                    total_comision = total_comision + :total_comision,
                    total = total + :sub_total
                WHERE 
                    id_cuenta = :id_cuenta";

        $params = [
            ':sub_total' => $cuenta['sub_total'],
            ':total_comision' => $cuenta['total_comision'],
            ':id_cuenta' => $cuenta['id_cuenta']
        ];

        try {
            $result = $this->save($sql, $params);
            return $result == 1 ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

}