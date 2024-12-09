<?php

namespace app\config;

use app\config\conexion;
use app\config\queryBuilder;
use PDO;
use PDOException;

class query extends conexion
{
    private $pdo, $con, $sql, $datos;
    private $queryBuilder;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = new conexion();
        $this->con = $this->pdo->conectar();
    }

    public function table($table)
    {
        $this->queryBuilder = new queryBuilder($table);
        return $this->queryBuilder;
    }

    public function select(string $sql, array $params = [])
    {
        try {
            $stmt = $this->con->prepare($sql);
            $this->bindParams($stmt, $params);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError('Error en select: ' . $e->getMessage());
            return false;
        }
    }

    public function selectAll(string $sql, array $params = [])
    {
        try {
            $stmt = $this->con->prepare($sql);
            $this->bindParams($stmt, $params);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError('Error en selectAll: ' . $e->getMessage());
            return false;
        }
    }

    public function save(string $sql, array $datos)
    {
        try {
            $this->con->beginTransaction();

            $stmt = $this->con->prepare($sql);
            $this->bindParams($stmt, $datos);
            $result = $stmt->execute();

            $this->con->commit();
            return $result;
        } catch (PDOException $e) {
            $this->con->rollBack();
            $this->logError('Error en save: ' . $e->getMessage());
            return false;
        }
    }

    private function bindParams($stmt, $params)
    {
        foreach ($params as $key => $value) {
            $type = $this->getParamType($value);
            $stmt->bindValue($key, $value, $type);
        }
    }

    private function getParamType($value)
    {
        switch (true) {
            case is_int($value):
                return PDO::PARAM_INT;
            case is_bool($value):
                return PDO::PARAM_BOOL;
            case is_null($value):
                return PDO::PARAM_NULL;
            default:
                return PDO::PARAM_STR;
        }
    }

    private function logError($message)
    {
        error_log($message);
    }

    public function executeQuery($query, $params = [])
    {
        try {
            $stmt = $this->con->prepare($query);
            $this->bindParams($stmt, $params);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError('Error en executeQuery: ' . $e->getMessage());
            return false;
        }
    }

    public function lastInsertId()
    {
        return $this->con->lastInsertId();
    }
}
