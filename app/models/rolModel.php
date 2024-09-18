<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class rolModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getRoles()
    {
        $sql = "SELECT * FROM roles WHERE estado = 1";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            error_log('RolModel::getRoles() -> ' . $e);
            return response::estado500($e);
        }
    }
    public function getRol($id)
    {
        $sql = "SELECT * FROM roles WHERE id_rol = $id AND estado = 1";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            error_log('RolModel::getRol() -> ' . $e);
            return response::estado500($e);
        }
    }

    public function createRol(string $nombre)
    {
        $sql = "SELECT nombre FROM roles WHERE nombre = :nombre";
        $params = [':nombre' => $nombre];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        } else {
            try {
                $sql = "INSERT INTO roles (nombre) VALUES (:nombre)";
                $data = $this->save($sql, $params);
                return $data == 1 ? "ok" : "error";
            } catch (Exception $e) {
                error_log('RolModel::createRol() -> ' . $e);
                return response::estado500($e);
            }
        }
    }

    public function updateRol(array $rol)
    {
        $sql = "SELECT nombre FROM roles WHERE nombre = :nombre AND estado = 1";
        $params = [':nombre' => $rol['nombre']];
        $existe = $this->select($sql, $params);
        if ($existe) {
            return "existe";
        } else {
            $sql = "UPDATE roles SET nombre = :nombre, fecha_mod = now() WHERE id_rol = :id_rol";
            $params = [':nombre' => $rol['nombre'], ':id_rol' => $rol['id_rol']];
            try {
                $data = $this->save($sql, $params);
                return $data == 1 ? "ok" : "error";
            } catch (Exception $e) {
                error_log("RolModel::updateRol() -> " . $e);
                return Response::estado500($e);
            }
        }
    }
    public function deleteRol(int $id)
    {
        $sql = "UPDATE roles SET estado = 0, fecha_baja = now() WHERE id_rol = :id_rol";
        $params = [':id_rol' => $id];
        try {
            $data = $this->save($sql, $params);
            return $data == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log("RolModel::deleteRol() -> " . $e);
            return Response::estado500($e);
        }
    }
}
