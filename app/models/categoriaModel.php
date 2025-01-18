<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class categoriaModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCategorias()
    {
        $sql = 'SELECT id_categoria, nombre, descripcion, estado FROM categorias';
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getCategoria($id)
    {
        $sql = "SELECT * FROM categorias WHERE id_categoria = $id AND estado = 1";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createCategoria(array $categoria)
    {
        $sql = 'SELECT nombre FROM categorias WHERE nombre = :nombre';
        $params = [
            ':nombre' => $categoria['nombre'],
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return 'existe';
        } else {
            try {
                $sql = 'INSERT INTO categorias (nombre, descripcion) VALUES (:nombre, :descripcion)';
                $params = [
                    ':nombre' => $categoria['nombre'],
                    ':descripcion' => $categoria['descripcion'],
                ];
                $data = $this->save($sql, $params);
                return $data === true ? 'ok' : 'error';
            } catch (Exception $e) {
                return response::estado500($e);
            }
        }
    }

    public function updateCategoria(array $categoria)
    {
        $sql = 'SELECT nombre FROM categorias WHERE nombre = :nombre AND descripcion = :descripcion';
        $params = [
            ':nombre' => $categoria['nombre'],
            ':descripcion' => $categoria['descripcion'],
        ];
        $existe = $this->select($sql, $params);

        if ($existe) {
            return 'existe';
        } else {
            $sql = 'UPDATE categorias SET nombre = :nombre, descripcion = :descripcion, fecha_mod = NOW() WHERE id_categoria = :id_categoria';
            try {
                $params = [
                    ':id_categoria' => $categoria['id_categoria'],
                    ':nombre' => $categoria['nombre'],
                    ':descripcion' => $categoria['descripcion'],
                ];
                $data = $this->save($sql, $params);
                return $data === true ? 'ok' : 'error';
            } catch (Exception $e) {
                return Response::estado500($e);
            }
        }
    }

    public function deleteCategoria(int $id)
    {
        $sqlCategoria = 'UPDATE categorias SET estado = 0, fecha_baja = NOW() WHERE id_categoria = :id_categoria';
        $sqlProductos = 'UPDATE productos SET estado = 0 , fecha_mod = now() WHERE categoria_id = :id_categoria';

        $params = [':id_categoria' => $id];

        try {
            $this->save($sqlProductos, $params);
            $data = $this->save($sqlCategoria, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return Response::estado500($e);
        }
    }

    public function highCategoria(int $id)
    {
        $sqlCategoria = 'UPDATE categorias SET estado = 1, fecha_mod = NOW() WHERE id_categoria = :id_categoria';
        $sqlProductos = 'UPDATE productos SET estado = 1 , fecha_mod = now() WHERE categoria_id = :id_categoria';

        $params = [':id_categoria' => $id];

        try {
            $this->save($sqlProductos, $params);
            $data = $this->save($sqlCategoria, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return Response::estado500($e);
        }
    }
}
