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
        $sql = 'SELECT C.id_cuenta, C.codigo, C.total, C.fecha_crea, C.estado,
         CONCAT(CL.nombre, " ", CL.apellido) as cliente FROM cuentas AS C 
         JOIN clientes AS CL ON C.cliente_id = CL.id_cliente 
         WHERE C.estado = 1 ORDER BY C.fecha_crea DESC';
        try {
            $result = $this->selectAll($sql);

            return array_map(function ($row) {
                return [
                    'id_cuenta'  => (int) $row['id_cuenta'],
                    'codigo'     => (string) $row['codigo'],
                    'total'      => (int) $row['total'],
                    'fecha_crea' => (string) $row['fecha_crea'],
                    'estado'      => (int) $row['estado'],
                    'cliente'    => (string) $row['cliente'],
                ];
            }, $result);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createCuenta(array $data)
    {
        $requiredFields = ['codigo', 'cliente_id', 'total_comision', 'total'];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return response::estado400("El campo $field es requerido");
            }
        }


        $sql = 'INSERT INTO cuentas (codigo, cliente_id, total_comision, total) 
        VALUES (:codigo, :cliente_id,  :total_comision, :total)';
        $params = [
            ':codigo' => $data['codigo'],
            ':cliente_id' => $data['cliente_id'],
            ':total_comision' => $data['total_comision'],
            ':total' => $data['total']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getDetalleCuentas(int $cuenta_id)
    {
        try {
            $params = [':cuenta_id' => $cuenta_id];

            $sql_detalle = "SELECT D.precio, D.cantidad, D.subtotal, D.comision, D.fecha_crea, 
                                   GROUP_CONCAT(CONCAT(U.nombre, ' ', U.apellido) SEPARATOR ', ') AS anfitrionas,
                                   GROUP_CONCAT(U.id_usuario SEPARATOR ', ') AS id_usuario, PR.nombre AS producto, PR.id_producto
                               FROM detalle_cuentas AS D
                               JOIN cuentas_usuarios AS CU ON CU.cuenta_id = D.cuenta_id
                               LEFT JOIN usuarios AS U ON U.id_usuario = CU.usuario_id
                               JOIN productos AS PR ON PR.id_producto = D.producto_id
                               WHERE D.cuenta_id = :cuenta_id AND D.fecha_crea = CU.fecha_crea
                               GROUP BY D.cuenta_id, D.fecha_crea, D.precio, D.cantidad, D.subtotal, D.comision, PR.nombre, PR.id_producto
                               ORDER BY D.fecha_crea DESC";

            $detalle_cuenta = $this->selectAll($sql_detalle, $params);
            if (empty($detalle_cuenta)) {
                return response::estado500("No se encontraron resultados para los detalles.");
            }

            $sql_cuenta = "SELECT C.id_cuenta, C.codigo, C.total_comision, C.fecha_crea AS fecha, C.total,
                                   CONCAT(CL.nombre, ' ', CL.apellido) AS cliente, CL.id_cliente 
                               FROM cuentas AS C
                               JOIN clientes AS CL ON C.cliente_id = CL.id_cliente 
                               WHERE C.id_cuenta = :cuenta_id";

            $cuenta = $this->select($sql_cuenta, $params);
            if (empty($cuenta)) {
                return response::estado500("No se encontraron resultados para la cuenta.");
            }

            return [
                'detalle_cuenta' => array_map(function ($row) {
                    return [
                        'precio'       => (int) $row['precio'],
                        'cantidad'     => (int) $row['cantidad'],
                        'subtotal'     => (int) $row['subtotal'],
                        'comision'     => (int) $row['comision'],
                        'fecha_crea'   => (string) $row['fecha_crea'],
                        'anfitrionas'  => (array) $row['anfitrionas'],
                        'id_usuario'   => (array) $row['id_usuario'],
                        'producto'     => (string) $row['producto'],
                        'id_producto'  => (int) $row['id_producto']
                    ];
                }, $detalle_cuenta),
                'cuenta' => [
                    'id_cuenta'      => (int) $cuenta['id_cuenta'],
                    'id_cliente'     => (int) $cuenta['id_cliente'],
                    'codigo'         => (string) $cuenta['codigo'],
                    'total_comision' => (int) $cuenta['total_comision'],
                    'fecha'          => (string) $cuenta['fecha'],
                    'total'          => (int) $cuenta['total'],
                    'cliente'        => (string) $cuenta['cliente']
                ]
            ];
        } catch (Exception $e) {
            return response::estado500("Error: " . $e->getMessage());
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
            return $result === true ? 'ok' : 'error';
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

        $sql = 'INSERT INTO detalle_cuentas (cuenta_id, producto_id, precio, cantidad, subtotal, comision, pieza_id, pedido_id, servicio_id) 
                VALUES (:cuenta_id, :producto_id, :precio, :cantidad, :subtotal, :comision, :pieza_id, :pedido_id, :servicio_id)';

        $params = [
            ':cuenta_id' => $data['cuenta_id'],
            ':producto_id' => $data['producto_id'],
            ':precio' => $data['precio'],
            ':comision' => $data['comision'],
            ':cantidad' => $data['cantidad'],
            ':subtotal' => $data['subtotal'],
            ':pieza_id' => $data['pieza_id'],
            ':pedido_id' => $data['pedido_id'],
            ':servicio_id' => $data['servicio_id']
        ];

        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? 'ok' : 'error';
        } catch (Exception $e) {
            error_log('Error en createDetalleCuenta: ' . $e->getMessage());
            return response::estado500('Error al crear el detalle de la cuenta. Por favor, intenta de nuevo.');
        }
    }

    public function updateCuenta(array $cuenta)
    {
        $sql = 'UPDATE cuentas SET total_comision = total_comision + :total_comision, total = total + :total
                WHERE id_cuenta = :id_cuenta';

        $params = [
            ':total' => $cuenta['total'],
            ':total_comision' => $cuenta['total_comision'],
            ':id_cuenta' => $cuenta['id_cuenta']
        ];

        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
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
            return response::estado500($e);
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

    public function createCuentaUsuario(array $data)
    {
        $sql = 'INSERT INTO cuentas_usuarios (cuenta_id, usuario_id) VALUES (:cuenta_id, :usuario_id)';
        $params = [
            ':cuenta_id' => $data['cuenta_id'],
            ':usuario_id' => $data['usuario_id']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
