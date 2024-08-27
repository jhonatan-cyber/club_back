<?php

namespace app\config;

use PDO;
use PDOException;
use Exception;
use Dotenv\Dotenv;


class conexion
{
    private $conexion;
    private $data;

    public function __construct()
    {
        $this->cargarEntorno();
        $this->validarVariablesEntorno();
        $this->establecerConexion();
    }

    private function cargarEntorno()
    {
        try {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();
        } catch (Exception $e) {
            $this->manejarError('Error al cargar el entorno: ' . $e->getMessage());
        }
    }

    private function validarVariablesEntorno()
    {
        $requiredEnvVars = ['HOST', 'DB', 'DB_USER', 'CHARSET'];
        foreach ($requiredEnvVars as $envVar) {
            if (empty($_ENV[$envVar])) {
                $this->manejarError("La variable de entorno $envVar no está definida o está vacía.");
            }
        }

        $this->data = [
            'HOST' => $_ENV['HOST'],
            'DB' => $_ENV['DB'],
            'USER' => $_ENV['DB_USER'],
            'PASSWORD' => $_ENV['PASSWORD'] ?? '', // La contraseña puede estar vacía
            'CHARSET' => $_ENV['CHARSET'],
        ];
    }

    private function establecerConexion()
    {
        $dsn = "mysql:host={$this->data['HOST']};dbname={$this->data['DB']};charset={$this->data['CHARSET']}";
        $opt = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

        try {
            if (!$this->conexion instanceof PDO) {
                $this->conexion = new PDO($dsn, $this->data['USER'], $this->data['PASSWORD'], $opt);
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {
            error_log('Error en la conexión: ' . $e->getMessage());
            $this->manejarError('Error al intentar conectarse a la base de datos.');
        }
    }

    public function conectar()
    {
        if ($this->conexion instanceof PDO) {
            return $this->conexion;
        } else {
            $this->manejarError("La conexión no está configurada correctamente.");
        }
    }

    private function manejarError($mensaje)
    {
        error_log($mensaje);
        die(json_encode(['error' => $mensaje, 'status' => 500]));
    }
}
