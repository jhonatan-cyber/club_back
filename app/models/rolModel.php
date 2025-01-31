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

   

    /**
     * Obtiene todos los roles de la base de datos
     *
     * @return array
     */
    public function getRoles(): array
    {
        $sql = 'SELECT * FROM roles';
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    /**
     * Obtiene un rol específico de la base de datos
     *
     * @param int $id_rol
     * @return array
     */

    public function getRol(int $id_rol): ?array
    {
        $sql = "SELECT * FROM roles WHERE id_rol = $id_rol";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    /**
     * Crea un nuevo rol en la base de datos
     *
     * @param string $nombre
     * @return string
     */
    public function createRol(string $nombre): string
    {
        $sql = 'SELECT nombre FROM roles WHERE nombre = :nombre';
        $params = [':nombre' => $nombre];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return 'existe';
        } else {
            try {
                $sql = 'INSERT INTO roles (nombre) VALUES (:nombre)';
                $data = $this->save($sql, $params);
                return $data === true ? 'ok' : 'error';
            } catch (Exception $e) {
                return response::estado500($e);
            }
        }
    }

    /**
     * Actualiza un rol en la base de datos
     *
     * @param array $rol
     * @return string
     */
    public function updateRol(array $rol): string
    {
        $sql = 'SELECT nombre FROM roles WHERE nombre = :nombre AND estado = 1';
        $params = [':nombre' => $rol['nombre']];
        $existe = $this->select($sql, $params);
        if ($existe) {
            return 'existe';
        } else {
            $sql = 'UPDATE roles SET nombre = :nombre, fecha_mod = now() WHERE id_rol = :id_rol';
            $params = [':nombre' => $rol['nombre'], ':id_rol' => $rol['id_rol']];
            try {
                $data = $this->save($sql, $params);
                return $data === true ? 'ok' : 'error';
            } catch (Exception $e) {
                return Response::estado500($e);
            }
        }
    }

    /**
     * Elimina (desactiva) un rol de la base de datos
     *
     * @param int $id_rol
     * @return string
     */
    public function deleteRol(int $id_rol): string
    {
        try {
            $sqlUsuarios = 'UPDATE usuarios SET estado = 0 WHERE rol_id = :rol_id';
            $paramsUsuarios = [':rol_id' => $id_rol];
            $this->save($sqlUsuarios, $paramsUsuarios);

            $sqlRol = 'UPDATE roles SET estado = 0, fecha_baja = NOW() WHERE id_rol = :id_rol';
            $paramsRol = [':id_rol' => $id_rol];
            $data = $this->save($sqlRol, $paramsRol);

            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return Response::estado500($e);
        }
    }

    /**
     * Restaura (activa) un rol en la base de datos
     *
     * @param int $id_rol
     * @return string
     */
    public function highRol(int $id_rol): string
    {
        try {
            $sqlUsuarios = 'UPDATE usuarios SET estado = 1 WHERE rol_id = :rol_id';
            $paramsUsuarios = [':rol_id' => $id_rol];
            $this->save($sqlUsuarios, $paramsUsuarios);

            $sqlRol = 'UPDATE roles SET estado = 1, fecha_mod = NOW() WHERE id_rol = :id_rol';
            $paramsRol = [':id_rol' => $id_rol];
            $data = $this->save($sqlRol, $paramsRol);

            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return Response::estado500($e);
        }
    }
}
