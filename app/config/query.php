<?php

namespace app\config;

use PDO;
use PDOException;
use app\config\conexion;
use app\config\response;

class query extends conexion
{
    private $pdo, $con, $sql, $datos;
    public function __construct()
    {
        parent::__construct();
        $this->pdo = new conexion();
        $this->con = $this->pdo->conectar();
    }
    public function select(string $sql, array $params = [])
    {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute($params);
        $data = $resul->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
    public function selectAll(string $sql, array $params = [])
    {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute($params);
        $data = $resul->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function save(string $sql, array $datos)
    {
        $this->sql = $sql;
        $this->datos = $datos;

        try {
            $insert = $this->con->prepare($this->sql);

            foreach ($this->datos as $key => &$value) {
                $insert->bindParam($key, $value);
            }
            $data = $insert->execute();

            return $data;
        } catch (PDOException $e) {
            response::estado500($e);
        }
    }
}
