<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class servicioModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createServicio(array $data)
    {
        $requiredFields = ['codigo', 'cliente_id', 'pieza_id', 'precio_pieza', 'precio_servicio', 'iva', 'metodo_pago', 'tiempo'];
        foreach ($requiredFields as $requiredField) {
            if (!isset($data[$requiredField])) {
                return response::estado500('El campo ' . $requiredField . ' es requerido');
            }
        }

        $sql = "INSERT INTO servicios (codigo, cliente_id, pieza_id, precio_pieza, precio_servicio, iva, sub_total, total, tiempo, metodo_pago) 
        VALUES (:codigo, :cliente_id, :pieza_id, :precio_pieza, :precio_servicio, :iva, :sub_total, :total, :tiempo, :metodo_pago)";
        $params = [
            ":codigo" => $data['codigo'],
            ":cliente_id" => $data['cliente_id'],
            ':pieza_id' => $data['pieza_id'],
            ':precio_pieza' => $data['precio_pieza'],
            ':precio_servicio' => $data['precio_servicio'],
            ':iva' => $data['iva'],
            ':sub_total' => $data['precio_servicio'],
            ':total' => $data['precio_servicio'] + $data['iva'] + $data['precio_pieza'],
            ':metodo_pago' => $data['metodo_pago'],
            ':tiempo' => $data['tiempo'],
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }
    } 

    public function getServicioCodigo(string $codigo)
    {
        $sql = "SELECT id_servicio FROM servicios WHERE codigo=:codigo ";
        $params = [
            ":codigo" => $codigo,
        ];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function getServicio(string $codigo)
    {
        $sql = "SELECT D.id_detalle_servicio,S.pieza_id, S.id_servicio, U.nombre AS nombre_u,U.apellido AS apellido_u,
         C.nombre AS nombre_c,C.apellido AS apellido_c,S.codigo,S.precio_pieza,P.nombre AS habitacion,
          S.precio_servicio,S.iva,S.sub_total,S.total,S.tiempo,S.metodo_pago,S.fecha_crea,S.estado 
          FROM detalle_servicios AS D 
          JOIN usuarios AS U ON D.usuario_id = U.id_usuario 
          JOIN servicios AS S ON D.servicio_id = S.id_servicio 
          JOIN clientes AS C ON S.cliente_id = C.id_cliente 
          JOIN piezas AS P ON S.pieza_id = P.id_pieza WHERE S.codigo = :codigo";
        $params = [
            ":codigo" => $codigo
        ];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function createDetalleServicio(array $data)
    {
        $sql = "INSERT INTO detalle_servicios (usuario_id, servicio_id) VALUES (:usuario_id, :servicio_id)";
        $params = [
            ':usuario_id' => $data['usuario_id'],
            ':servicio_id' => $data['servicio_id']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }

    }
    public function getDetalleServicio(int $servicio_id)
    {
        $sql = "SELECT D.id_detalle_servicio, U.nombre AS nombre_u,U.apellido AS apellido_u,
        C.nombre AS nombre_c,C.apellido AS apellido_c,S.codigo,S.precio_pieza,P.nombre AS habitacion, 
        S.precio_servicio,S.iva,S.sub_total,S.total,S.tiempo,S.metodo_pago,S.fecha_crea,S.estado 
        FROM detalle_servicios AS D JOIN usuarios AS U ON D.usuario_id = U.id_usuario 
        JOIN servicios AS S ON D.servicio_id = S.id_servicio JOIN clientes AS C ON S.cliente_id = C.id_cliente 
        JOIN piezas AS P ON S.pieza_id = P.id_pieza WHERE D.servicio_id= :servicio_id";
        $params = [
            ":servicio_id" => $servicio_id
        ];
        try {
            return $this->selectAll($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function getServicios()
    {
        $sql = "SELECT S.id_servicio,S.codigo,S.tiempo,S.fecha_crea,S.precio_servicio,
        S.precio_pieza,S.iva,P.id_pieza,S.sub_total,S.total,S.metodo_pago ,P.nombre AS habitacion 
        FROM servicios AS S JOIN piezas AS P ON S.pieza_id = P.id_pieza WHERE S.estado = 1";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function getCuenta(string $codigo)
    {
        $sql = "SELECT * FROM cuentas WHERE codigo = :codigo";
        $params = [
            ":codigo" => $codigo
        ];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return response::estado500($e);
        }
    }
    public function updateServicio(int $id_servico)
    {
        $sql = "UPDATE servicios SET estado = 0 WHERE id_servicio = :id_servicio";
        $params = [
            ":id_servicio" => $id_servico
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {

            return response::estado500($e);
        }
    }
    public function updatePieza(int $id_pieza)
    {
        $sqlSelect = "SELECT estado FROM piezas WHERE id_pieza = :id_pieza";
        $params = [
            ":id_pieza" => $id_pieza
        ];
        try {
            $currentEstado = $this->select($sqlSelect, $params);
            $nuevoEstado = ($currentEstado['estado'] == 1) ? 0 : 1;
            $sqlUpdate = "UPDATE piezas SET estado = :nuevo_estado WHERE id_pieza = :id_pieza";
            $paramsUpdate = [
                ":nuevo_estado" => $nuevoEstado,
                ":id_pieza" => $id_pieza
            ];
            $resp = $this->save($sqlUpdate, $paramsUpdate);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function createCuenta(array $data)
    {
        $requiredFields = ['codigo','cliente_id', 'servicio_id',  'total_comision', 'total'];
        foreach ($requiredFields as $requiredField) {
            if (!isset($data[$requiredField])) {
                error_log('El campo ' . $requiredField . ' es requerido');
                return response::estado500('El campo ' . $requiredField . ' es requerido');
            }
        }

        $sql = "INSERT INTO cuentas (codigo, cliente_id, servicio_id, total_comision , total) 
                VALUES (:codigo, :cliente_id, :servicio_id, :total_comision, :total)";
        $params = [
            ':codigo' => $data['codigo'],
            ':cliente_id' => $data['cliente_id'],
            ':servicio_id' => $data['servicio_id'],
            ':total_comision' => $data['total_comision'],
            ':total' => $data['total']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? "ok" : "error";
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
    public function getDetalleCuenta(int $cuenta_id)
    {
        $sql = "SELECT D.id_detalle_cuenta, D.cuenta_id, D.producto_id, D.precio, D.cantidad, D.subtotal, D.comision,
        P.nombre AS nombre_producto
        FROM detalle_cuentas AS D JOIN productos AS P ON D.producto_id = P.id_producto
        WHERE D.cuenta_id = :cuenta_id";
        $params = [
            ":cuenta_id" => $cuenta_id
        ];
        try {
            return $this->selectAll($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function updateCuenta(array $cuenta)
    {

        $sql = "UPDATE cuentas SET metodo_pago = :metodo_pago, fecha_mod = now(), estado = 0 WHERE id_cuenta = :id_cuenta";
        $params = [
            ":id_cuenta" => $cuenta['id_cuenta'],
            ":metodo_pago" => $cuenta['metodo_pago']
        ];

        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log($e->getMessage());
            return response::estado500($e);
        }
    }
    public function createComision(array $data)
    {
        $requiredFields = ["monto"];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                error_log("El campo $field es requerido");
                return response::estado400("El campo $field es requerido");
            }
        }

        $sql = "INSERT INTO comisiones (servicio_id, monto) VALUES (:servicio_id, :monto)";
        $params = [
            ":servicio_id" => $data['servicio_id'],
            ":monto" => $data['monto']
        ];

        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log("Error en createComision: " . $e->getMessage());
            return response::estado500("Error al crear la comisión. Por favor, intenta de nuevo.");
        }
    }
    public function cretaeDetalleComision(array $data)
    {
        $requiredFields = ["comision_id", "chica_id", "comision"];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                error_log("El campo $field es requerido");
                return response::estado400("El campo $field es requerido");
            }
        }

        $sql = "INSERT INTO detalle_comisiones (comision_id, chica_id, comision) 
        VALUES (:comision_id, :chica_id, :comision)";
        $params = [
            ":comision_id" => $data['comision_id'],
            ":chica_id" => $data['chica_id'],
            ":comision" => $data['comision']
        ];

        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log("Error en createDetalleServicio: " . $e->getMessage());
            return response::estado500("Error al crear el detalle del servicio. Por favor, intenta de nuevo.");
        }
    }
    public function getLastventa()
    {
        $sql = "SELECT MAX(id_venta) AS venta_id FROM ventas";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function getLastServicio()
    {
        $sql = "SELECT MAX(id_servicio) AS servicio_id FROM servicios";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function getLastComision()
    {
        $sql = "SELECT MAX(id_comision) AS comision_id FROM comisiones";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function getCuentaServicio(string $servicio_id)
    {
        $sql = "SELECT * FROM cuentas WHERE servicio_id = :servicio_id";
        $params = [
            ":servicio_id" => $servicio_id
        ];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return response::estado500($e);
        }
    }
    public function getAnticipoUsuario(int $id_usuario)
    {
        $sql = 'SELECT id_anticipo, usuario_id, monto, estado 
                FROM anticipos 
                WHERE usuario_id = :id_usuario 
                AND estado = 1';
        $params = [
            ':id_usuario' => $id_usuario
        ];
        try {
            return $this->selectAll($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function updateAnticipo(int $id_anticipo)
    {
        $sql = 'UPDATE anticipos SET estado = 0 WHERE id_anticipo = :id_anticipo';
        $params = [
            ':id_anticipo' => $id_anticipo
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

}