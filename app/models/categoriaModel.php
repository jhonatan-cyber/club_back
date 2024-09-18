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
        $sql = "SELECT id_categoria,nombre,descripcion FROM categorias WHERE estado = 1";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            error_log('CategoriaModel::getCategorias() -> ' . $e);
            return response::estado500($e);
        }
    }
    public function getCategoria($id)
    {
        $sql = "SELECT * FROM categorias WHERE id_categoria = $id AND estado = 1";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            error_log('CategoriaModel::getCategoria() -> ' . $e);
            return response::estado500($e);
        }
    }
    public function createCategoria(array $categoria)
    {
        
        $sql = "SELECT nombre FROM categorias WHERE nombre = :nombre";
        $params = [
            ':nombre' => $categoria['nombre'],
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        } else {
            try {
                $sql = "INSERT INTO categorias (nombre, descripcion) VALUES (:nombre, :descripcion)";
                $params = [
                    ':nombre' => $categoria['nombre'],
                    ':descripcion' => $categoria['descripcion'],
                ];
                $data = $this->save($sql, $params);
                return $data == 1 ? "ok" : "error";
            } catch (Exception $e) {
                error_log('CategoriaModel::createCategoria() -> ' . $e);
                return response::estado500($e);
            }
        }
    }
    public function updateCategoria(array $categoria)
    {
        $sql = "SELECT nombre FROM categorias WHERE nombre = :nombre";
        $params = [
            ':id_categoria' => $categoria['id_categoria'],
            ':nombre' => $categoria['nombre'],
            ':descripcion' => $categoria['descripcion'],
        ];
        $existe = $this->select($sql, $params);
        if ($existe) {
            return "existe";
        } else {
            $sql = "UPDATE categorias SET nombre = :nombre, descripcion,  fecha_mod = now() WHERE id_categoria = :id_categoria";
            try {
                $data = $this->save($sql, $params);
                return $data == 1 ? "ok" : "error";
            } catch (Exception $e) {
                error_log("CategoriaModel::updateCategoria() -> " . $e);
                return Response::estado500($e);
            }
        }
    }
    public function deleteCategoria(int $id)
    {
        $sql = "UPDATE categorias SET estado = 0, fecha_baja = now() WHERE id_categoria = :id_categoria";
        $params = [':id_categoria' => $id];
        try {
            $data = $this->save($sql, $params);
            return $data == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log("CategoriaModel::deleteCategoria() -> " . $e);
            return Response::estado500($e);
        }
    }
}
