<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class contratoModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getContratos()
    {
        $sql = "SELECT C.*,U.nombre,U.apellido FROM contratos AS C 
                INNER JOIN usuarios AS U ON C.usuario_id = U.id_usuario 
                WHERE C.estado = 1";
        try {
            return $this->selectAll($sql, );
        } catch (Exception $e) {
            error_log("ContratoModel::getContrato() -> " . $e);
            return response::estado500($e);
        }
    }
    public function getContrato(int $id_contrato)
    {
        $sql = "SELECT C.*,U.run,U.nombre,U.apellido,U.foto
                FROM contratos AS C
                INNER JOIN usuarios AS U
                ON C.usuario_id = U.id_usuario WHERE C.usuario_id=:id_contrato";

        try {
            $params = [
                ":id_contrato" => $id_contrato
            ];
            return $this->select($sql, $params);
        } catch (Exception $e) {
            error_log("ContratoModel::getContrato() -> " . $e);
            return response::estado500($e);
        }

    }

    public function createContrato(array $contrato)
    {
        $requiredFields = ['usuario_id', 'sueldo', 'fonasa'];
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $contrato)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }
        $sql = 'SELECT * FROM contratos WHERE usuario_id = :usuario_id AND estado = 1';
        $params = [
            ':usuario_id' => $contrato['usuario_id']
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        }
        $sql = "INSERT INTO contratos (usuario_id, sueldo, fonasa) VALUES (:usuario_id, :sueldo, :fonasa)";
        $params = [
            ':usuario_id' => $contrato['usuario_id'],
            ':sueldo' => $contrato['sueldo'],
            ':fonasa' => $contrato['fonasa']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log("ContratoModel::createContrato() -> " . $e);
            return response::estado500($e);
        }

    }

    public function updateContrato(array $contrato)
    {
        $requiredFields = ["usuario_id", "sueldo", "fonasa", "id_contrato"];
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $contrato)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }
        $sql = "SELECT * FROM contratos WHERE  sueldo = :sueldo AND fonasa = :fonasa AND estado = 1";
        $params = [
            ':sueldo' => $contrato['sueldo'],
            ':fonasa' => $contrato['fonasa']
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return 'existe';
        }

        $sql = 'UPDATE contratos SET  sueldo = :sueldo, fonasa = :fonasa, fecha_mod = NOW() WHERE id_contrato = :id_contrato';
        $params = [
            ':sueldo' => $contrato['sueldo'],
            ':fonasa' => $contrato['fonasa'],
            ':id_contrato' => $contrato['id_contrato']
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            error_log('ContratoModel::updateContrato() -> ' . $e);
            return response::estado500($e);
        }

    }

    public function deleteContrato(int $id_contrato)
    {
        $sql = 'UPDATE contratos SET estado = 0 , fecha_baja = NOW() WHERE id_contrato = :id_contrato';
        $params = [
            ':id_contrato' => $id_contrato
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp == 1 ? 'ok' : 'error';
        } catch (Exception $e) {
            error_log('ContratoModel::deleteContrato() -> ' . $e);
            return response::estado500($e);
        }
    }
}