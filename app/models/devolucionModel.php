<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class devolucionModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllServicios()
    {
        $sql = "SELECT D.servicio_id, S.precio_pieza, S.iva,S.sub_total, S.total, S.metodo_pago, S.fecha_crea, S.pieza_id,S.cliente_id,
                GROUP_CONCAT(U.id_usuario ORDER BY U.id_usuario SEPARATOR ' , ') AS id_usuario,
                GROUP_CONCAT(U.nick ORDER BY U.nick SEPARATOR ' , ') AS chica, P.nombre AS pieza, C.nombre, C.apellido 
                FROM detalle_servicios AS D
                JOIN servicios AS S ON D.servicio_id = S.id_servicio
                JOIN usuarios AS U ON D.usuario_id = U.id_usuario
                JOIN piezas AS P ON S.pieza_id = P.id_pieza
                JOIN clientes AS C ON S.cliente_id = C.id_cliente WHERE S.estado = 1 
                GROUP BY D.servicio_id, S.precio_pieza, S.iva,S.sub_total, S.total, S.metodo_pago, S.fecha_crea, P.nombre, C.nombre, C.apellido
                ORDER BY D.servicio_id ASC";
        try {
            $res = $this->selectAll($sql);
            return $res;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createDevolucionServicio(array $data) 
    {
        $sql = 'INSERT INTO devoluciones (servicio_id,pieza_id,cliente_id,total) 
               VALUES(:servicio_id, :pieza_id, :cliente_id, :total )';
        $params = [
            ':servicio_id' => $data['servicio_id'],
            ':pieza_id' => $data['pieza_id'],
            ':cliente_id' => $data['cliente_id'],
            ':total' => $data['total']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getLastDevolucion()
    {
        $sql = 'SELECT MAX(id_devolucion) AS id_devolucion FROM devoluciones';
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createDetalleDevolucion(array $data)
    {
        $sql = 'INSERT INTO detalle_devoluciones (devolucion_id, usuario_id, monto)
                VALUES(:devolucion_id, :usuario_id, :monto)';
        $params = [
            ':devolucion_id' => $data['devolucion_id'],
            ':usuario_id' => $data['usuario_id'],
            ':monto' => $data['monto']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function updateServicio(int $id_servicio)
    {
        $sql = 'UPDATE servicios SET estado = 0 WHERE id_servicio = :id_servicio';
        $params = [
            ':id_servicio' => $id_servicio
        ];
        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function updateComision($servicio_id)
    {
        $sql = 'UPDATE comisiones SET estado = 0, fecha_mod = now() WHERE servicio_id = :servicio_id';
        $params = [
            ':servicio_id' => $servicio_id,
        ];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getComisionServicio(int $servicio_id)
    {
        $sql = 'SELECT id_comision FROM comisiones WHERE servicio_id = :servicio_id';
        $params = [
            ':servicio_id' => $servicio_id
        ];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function updateDetalleComision($comision_id)
    {
        $sql = 'UPDATE detalle_comisiones  SET estado = 0 WHERE comision_id = :comision_id';
        $params = [
            ':comision_id' => $comision_id,
        ];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            error_log($e->getMessage());
            return response::estado500($e);
        }
    }

    public function getDevoluciones()
    {
        $sql = 'SELECT D.id_devolucion, D.total, D.fecha_crea, P.nombre AS pieza, C.nombre, C.apellido
            FROM devoluciones AS D
            JOIN piezas AS P ON D.pieza_id = P.id_pieza
            JOIN clientes AS C ON D.cliente_id = C.id_cliente
            ORDER BY D.id_devolucion DESC';
        try {
            $res = $this->selectAll($sql);
            return $res;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getDevolucion(int $id_devolucion)
    {
        $sql = 'SELECT D.fecha_crea, D.total, U.nick, DT.monto, C.nombre, C.apellido ,P.nombre AS pieza 
        FROM detalle_devoluciones AS DT 
        JOIN devoluciones AS D ON DT.devolucion_id = D.id_devolucion 
        JOIN usuarios AS U ON DT.usuario_id = U.id_usuario 
        JOIN piezas AS P ON D.pieza_id = P.id_pieza 
        JOIN clientes AS C ON D.cliente_id = C.id_cliente 
        WHERE DT.devolucion_id = :id_devolucion';
        $params = [
            ':id_devolucion' => $id_devolucion
        ];
        try {
            $res = $this->selectAll($sql, $params);
            return $res;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function anularVenta($id_venta)
    {
        $sql = 'UPDATE ventas SET estado = 0 WHERE id_venta = :id_venta';
        $params = [
            ':id_venta' => $id_venta
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function createDevolucionVenta(array $data)
    {
        $sql = 'INSERT INTO devoluciones_ventas (cliente_id, venta_id, total)
                VALUES(:cliente_id, :venta_id, :total)';
        $params = [
            ':cliente_id' => $data['cliente_id'],
            ':venta_id' => $data['venta_id'],
            ':total' => $data['total']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createDetalleDevolucionVenta(array $data): string
    {

        $sql = 'INSERT INTO detalle_devoluciones_ventas (devolucion_venta_id, producto_id, cantidad, precio, comision)
                VALUES(:devolucion_venta_id, :producto_id, :cantidad, :precio, :comision)';
        $params = [
            ':devolucion_venta_id' => $data['devolucion_venta_id'],
            ':producto_id' => $data['producto_id'],
            ':cantidad' => $data['cantidad'],
            ':precio' => $data['precio'],
            ':comision' => $data['comision']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createDevolucionVentaUsuario(array $data): string
    {
        $sql = 'INSERT INTO devoluciones_ventas_usuarios (detalle_devolucion_venta_id, usuario_id)
                VALUES(:detalle_devolucion_venta_id, :usuario_id)';
        $params = [
            ':detalle_devolucion_venta_id' => $data['detalle_devolucion_venta_id'],
            ':usuario_id' => $data['usuario_id']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function getDevolucionesVenta()
    {
        $sql = "SELECT DV.venta_id, V.codigo, V.total, V.fecha_mod, V.estado, CONCAT(C.nombre, ' ', C.apellido) AS cliente 
                FROM detalle_ventas AS DV 
                JOIN ventas AS V ON DV.venta_id = V.id_venta 
                JOIN clientes AS C ON V.cliente_id = C.id_cliente 
                WHERE V.estado = 0 AND DV.estado = 0";
        try {
            $res = $this->selectAll($sql);
            return array_map(function ($row) {
                return [
                    'venta_id' => (int) $row['venta_id'],
                    'codigo' => (string) $row['codigo'],
                    'total' => (int) $row['total'],
                    'fecha_mod' => (string) $row['fecha_mod'],
                    'cliente' => (string) $row['cliente'],
                    'estado' => (int) $row['estado']
                ];
            }, $res);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getLastDevolucionVenta()
    {
        $sql = 'SELECT id_devolucion_venta 
            FROM devoluciones_ventas
            ORDER BY id_devolucion_venta DESC
            LIMIT 1';
        try {
            $res = $this->select($sql);
            return $res;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
    public function getLastDetalleDevolucionVenta()
    {
        $sql = 'SELECT id_detalle_devolucion 
            FROM detalle_devoluciones_ventas
            ORDER BY id_detalle_devolucion DESC
            LIMIT 1';
        try {
            $res = $this->select($sql);
            return $res;
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }








































    public function getLastDetalleComisionChica(int $chica_id)
    {
        $sql = 'SELECT comision_id, comision
                FROM detalle_comisiones
                WHERE chica_id = :chica_id AND estado = 1
                ORDER BY comision_id DESC
                LIMIT 1';
        $params = [
            ':chica_id' => $chica_id,
        ];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function updateDetalleComisionMonto(array $data)
    {
        $sql = 'UPDATE detalle_comisiones SET comision = :comision, estado = :estado WHERE chica_id = :chica_id AND comision_id = :comision_id';
        $params = [
            ':comision' => $data['comision'],
            ':estado' => $data['estado'],
            ':chica_id' => $data['chica_id'],
            ':comision_id' => $data['comision_id'],
        ];
        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }





    public function updateComisionMonto(array $data)
    {
        $sql = 'UPDATE comisiones SET monto = :monto, estado = :estado, fecha_mod = now() WHERE id_comision = :id_comision';
        $params = [
            ':monto' => $data['monto'],
            ':estado' => $data['estado'],
            ':id_comision' => $data['id_comision'],
        ];
        try {
            $result = $this->save($sql, $params);
            return $result === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getComision(int $id_comision)
    {
        $sql = 'SELECT id_comision, monto
        FROM comisiones
        WHERE id_comision = :id_comision AND estado = 1';
        $params = [
            ':id_comision' => $id_comision,
        ];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }
}
