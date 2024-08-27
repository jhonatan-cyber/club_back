<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class usuarioModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getUsuarios()
    {
        $sql = "CALL getUsuarios()";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            error_log('UsuarioModel::getUsuarios() -> ' . $e);
            return response::estado500($e);
        }
    }
    public function getUsuario(int $id)
    {
        $sql = "SELECT * FROM usuarios WHERE id_usuario =$id AND estado = 1";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            error_log('UsuarioModel::getUsuario() -> ' . $e);
            return response::estado500($e);
        }
    }

    public function createUsuario(array $usuario)
    {
        $required = ['run', 'nombre', 'apellido', 'direccion', 'telefono', 'correo', 'password', 'rol_id'];
        foreach ($required as $field) {
            if (!array_key_exists($field, $usuario)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }

        $sql = "SELECT * FROM usuarios WHERE run = :run AND correo = :correo AND estado = 1";
        $params = [':run' => $usuario['run'], ':correo' => $usuario['correo']];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        }

        $sql = "INSERT INTO usuarios (run,nombre,apellido,direccion,telefono,correo,password,rol_id,foto) VALUES (:run,:nombre,:apellido,:direccion,:telefono,:correo,:password,rol_id,foto)";
        $params = [
            ':run' => $usuario['run'],
            ':nombre' => $usuario['nombre'],
            ':apellido' => $usuario['apellido'],
            ':direccion' => $usuario['direccion'],
            ':telefono' => $usuario['telefono'],
            ':correo' => $usuario['correo'],
            ':password' => $usuario['password'],
            ':rol_id' => $usuario['rol_id'],
            ':foto' => $usuario['foto']
        ];
        try {
            $data = $this->save($sql, $params);
            return $data == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log('UsuarioModel::createUsuario() -> ' . $e);
            return response::estado500($e);
        }
    }
    public function updateUsuario(array $usuario)
    {
        $required = ['id_usuario', 'run', 'nombre', 'apellido', 'direccion', 'telefono', 'correo', 'password', 'rol_id'];
        foreach ($required as $field) {
            if (!array_key_exists($field, $usuario)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }

        $sql = "SELECT * FROM usuarios WHERE run = :run AND nombre = :nombre AND apellido = :apellido AND direccion = :direccion AND telefono = :telefono AND rol_id = :rol_id AND foto = :foto";
        $params = [
            ':run' => $usuario['run'],
            ':nombre' => $usuario['nombre'],
            ':apellido' => $usuario['apellido'],
            ':direccion' => $usuario['direccion'],
            ':telefono' => $usuario['telefono'],
            ':rol_id' => $usuario['rol_id'],
            ':foto' => $usuario['foto']
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        }

        $sql = "UPDATE usuarios SET run = :run, nombre = :nombre, apellido = :apellido, direccion = :direccion, telefono = :telefono, correo = :correo, password = :password, rol_id = :rol_id, foto = :foto WHERE id_usuario = :id_usuario";
        $params = [
            ':id_usuario' => $usuario['id_usuario'],
            ':run' => $usuario['run'],
            ':nombre' => $usuario['nombre'],
            ':apellido' => $usuario['apellido'],
            ':direccion' => $usuario['direccion'],
            ':telefono' => $usuario['telefono'],
            ':correo' => $usuario['correo'],
            ':password' => $usuario['password'],
            ':rol_id' => $usuario['rol_id'],
            ':foto' => $usuario['foto']
        ];
        try {
            $data = $this->save($sql, $params);
            return $data == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log('UsuarioModel::updateUsuario() -> ' . $e);
            return response::estado500($e);
        }
    }

    public function deleteUsuario(int $id)
    {
        $sql = "UPDATE usuarios SET estado = 0, fecha_baja = now() WHERE id_usuario = :id";
        $params = [':id' => $id];
        try {
            $data = $this->save($sql, $params);
            return $data == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log('UsuarioModel::deleteUsuario() -> ' . $e);
            return response::estado500($e);
        }
    }
}
