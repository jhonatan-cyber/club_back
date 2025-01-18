<?php

namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class piezaModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getPiezas()
    {

        $sql = "SELECT * FROM piezas";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }

    }

    public function getPieza(int $id_pieza)
    {
        $sql = "SELECT * FROM piezas WHERE id_pieza = :id_pieza";
        $params = [
            ':id_pieza' => $id_pieza
        ];
        try {
            return $this->select($sql, $params);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function createPieza(array $data)
    {
        
        $sql = "SELECT * FROM piezas WHERE nombre = :nombre AND precio = :precio";
        $params = [
            ':nombre' => $data['nombre'],
            ':precio' => $data['precio']
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        } else {
            $sql = "INSERT INTO piezas (nombre, precio) VALUES (:nombre, :precio)";
            $params = [
                ':nombre' => $data['nombre'],
                ':precio' => $data['precio']
            ];
            try {
                $resp = $this->save($sql, $params);
                return $resp === true ? "ok" : "error";
            } catch (Exception $e) {
                return response::estado500($e);
            }
        }
    }

    public function updatePieza(array $data)
    {
      
        $sql = "SELECT * FROM piezas WHERE nombre = :nombre AND precio = :precio";
        $params = [
            ':nombre' => $data['nombre'],
            ':precio' => $data['precio'],
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return 'existe';
        } else {
            $sql = "UPDATE piezas SET nombre = :nombre, precio = :precio, estado = :estado WHERE id_pieza= :id_pieza";
            $params = [
                ':nombre' => $data['nombre'],
                ':precio' => $data['precio'],
                ':estado' => $data['estado'],
                ':id_pieza' => $data['id_pieza']
            ];
            try {
                $resp = $this->save($sql, $params);
                return $resp === true ? "ok" : "error";
            } catch (Exception $e) {
                return response::estado500($e);
            }
        }

    }

    public function deletePieza(int $id_pieza)
    {

        $sql = "UPDATE piezas SET estado = 0 ,fecha_mod = now() WHERE id_pieza = :id_pieza";
        $params = [
            ":id_pieza" => $id_pieza,
        ];
        try {
            $resp = $this->save($sql, $params);
            return $resp === true ? "ok" : "error";
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

    public function getPiezasLibres()
    {

        $sql = "SELECT * FROM piezas WHERE estado = 1";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }

    }
    public function highPieza(int $id_pieza){
        $sql = "UPDATE piezas SET estado = 1 , fecha_mod = now() WHERE id_pieza= :id_pieza";
        $params = [':id_pieza' => $id_pieza];
        try {
            $data = $this->save($sql, $params);
            return $data === true ? 'ok' : 'error';
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

}