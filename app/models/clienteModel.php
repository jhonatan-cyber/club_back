<?php

namespace app\models;

use app\config\query;
use app\config\response;
use app\config\guard;
use Exception;

class clienteModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getClientes()
    {
        $sql = "SELECT * FROM clientes WHERE estado = 1";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            error_log('ClienteModel::getClientes() -> ' . $e);
            return response::estado500($e);
        }
    }
    public function getCliente(int $id)
    {
        $sql = "SELECT * FROM clientes WHERE id_cliente = $id AND estado = 1";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            error_log('ClienteModel::getClientes() -> ' . $e);
            return response::estado500($e);
        }
    }
    public function createCliente(array $clientes)
    {
        $requiredFields = ['run', 'nombre', 'apellido', 'telefono'];
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $clientes)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }
        $sql = "SELECT * FROM clientes WHERE run = :run AND estado = 1";
        $params = [
            ':run' => $clientes['run'],
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        }
        $sql = "INSERT INTO clientes (run, nombre, apellido, telefono) VALUES (:run, :nombre, :apellido, :telefono)";
        $params = [
            ':run' => $clientes['run'],
            ':nombre' => $clientes['nombre'],
            ':apellido' => $clientes['apellido'],
            ':telefono' => $clientes['telefono'],
        ];
        try {
            $result = $this->save($sql, $params);
            return $result == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log("clienteModel::createCliente() -> " . $e);
            return response::estado500();
        }
    }

    public function updateCliente(array $clientes)
    {
        $requiredFields = ['run', 'nombre', 'apellido', 'telefono', 'id_cliente'];
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $clientes)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }

        $sql = "SELECT * FROM clientes WHERE run = :run ";
        $params = [
            'run' => $clientes['run'],
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        }
        $sql = "UPDATE clientes SET run = :run, nombre = :nombre, apellido = :apellido, telefono = :telefono, fecha_mod = now() WHERE id_cliente = :id_cliente";
        $params = [
            ':run' => $clientes['run'],
            ':nombre' => $clientes['nombre'],
            ':apellido' => $clientes['apellido'],
            ':telefono' => $clientes['telefono'],
            ':id_cliente' => $clientes['id_cliente']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log("clienteModel::updateCliente() -> " . $e);
            return response::estado500();
        }
    }
    public function deleteCliente(int $id)
    {
        $sql = "UPDATE clientes SET estado = 0, fecha_elim = now() WHERE id_cliente = :id_cliente";
        $params = [':id_cliente' => $id];
        try {
            $data = $this->save($sql, $params);
            return $data == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log('clienteModel::deleteCliente() -> ' . $e);
            return response::estado500($e);
        }
    }
}

