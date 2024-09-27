<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class productoModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProductosCategoria(int $categoria_id)
    {
        $sql = "SELECT * FROM productos WHERE categoria_id = :categoria_id AND estado = 1";
        $params = [
            ':categoria_id' => $categoria_id
        ];
        try {
            return $this->selectAll($sql, $params);
        } catch (Exception $e) {
            error_log('ProductoModel::getProductosCategoria() -> ' . $e);
            return response::estado500($e);
        }
    }
    public function getProductos()
    {
        $sql = "SELECT * FROM productos WHERE estado = 1";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            error_log('ProductoModel::getProductos() -> ' . $e);
            return response::estado500($e);
        }
    }

    public function getProducto(int $id_producto)
    {
        $sql = "SELECT * FROM productos WHERE id_producto = :id_producto AND estado = 1";
        $params = [
            ':id_producto' => $id_producto
        ];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            error_log('ProductoModel::getProducto() -> ' . $e);
            return response::estado500($e);
        }
    }

    public function createProducto(array $producto)
    {
        $requiredFields = ['nombre', 'descripcion', 'precio', 'categoria_id'];
        foreach ($requiredFields as $field) {
            if (!isset($producto[$field])) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }

        $sql = 'SELECT * FROM productos WHERE codigo= :codigo AND nombre = :nombre AND precio = :precio';
        $params = [
            ':codigo' => $producto['codigo'],
            ':nombre' => $producto['nombre'],
            ':precio' => $producto['precio']
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        }
        $sql = "INSERT INTO productos (codigo, nombre, categoria_id, precio, descripcion, foto) VALUES (:codigo, :nombre, :categoria_id, :precio,:descripcion, :foto)";
        $params = [
            ':codigo' => $producto['codigo'],
            ':nombre' => $producto['nombre'],
            ':categoria_id' => $producto['categoria_id'],
            ':precio' => $producto['precio'],
            ':descripcion' => $producto['descripcion'],
            ':foto' => $producto['foto']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log('ProductoModel::createProducto() -> ' . $e);
            return response::estado500($e);
        }

    }

    public function updateProducto(array $producto)
    {
        $requiredFields = ['id_producto', 'codigo', 'nombre', 'descripcion', 'precio', 'categoria_id'];
        foreach ($requiredFields as $field) {
            if (!isset($producto[$field])) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }

        }

        $sql = 'SELECT * FROM productos WHERE nombre = :nombre AND precio = :precio AND descripcion = :descripcion AND foto = :foto AND estado = 1';
        $params = [
            ':nombre' => $producto['nombre'],
            ':precio' => $producto['precio'],
            ':descripcion' => $producto['descripcion'],
            ':foto' => $producto['foto']

        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        } else {
            $sql = "UPDATE productos SET codigo = :codigo, nombre = :nombre, categoria_id = :categoria_id, precio = :precio, descripcion = :descripcion, foto = :foto, fecha_mod = now() WHERE id_producto = :id_producto";
            $params = [
                ':id_producto' => $producto['id_producto'],
                ':codigo' => $producto['codigo'],
                ':nombre' => $producto['nombre'],
                ':descripcion' => $producto['descripcion'],
                ':precio' => $producto['precio'],
                ':categoria_id' => $producto['categoria_id'],
                ':foto' => $producto['foto']
            ];
            try {
                $resp = $this->save($sql, $params);
                return $resp == 1 ? "ok" : "error";
            } catch (Exception $e) {
                error_log('ProductoModel::updateProducto() -> ' . $e);
                return response::estado500($e);
            }
        }
    }

    public function deleteProducto(int $id_producto)
    {
        $sql = "UPDATE productos SET estado = 0 , fecha_baja = now() WHERE id_producto = :id_producto";
        $params = [
            'id_producto' => $id_producto
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            error_log('ProductoModel::deleteProducto() -> ' . $e);
            return response::estado500($e);
        }
    }

    public function getProductosPrecio()
    {
        $sql = 'SELECT precio FROM `productos` WHERE estado = 1 GROUP BY precio ORDER BY precio DESC';
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            error_log('ProductoModel::getProductoPrecio() -> ' . $e);
            return response::estado500($e);
        }
    }
    public function getBebidasPrecio($precio)
    {
        $sql = "SELECT P.id_producto,P.nombre,P.precio, C.nombre AS categoria FROM productos AS P JOIN categorias AS C ON P.categoria_id = C.id_categoria WHERE P.precio=:precio AND P.estado = 1";
        $params = [
            ':precio' => $precio
        ];
        try {
            return $this->selectAll($sql, $params);
        } catch (Exception $e) {
            error_log('ProductoModel::getBebidasPrecio() -> ' . $e);
            return response::estado500($e);
        }
    }
}



