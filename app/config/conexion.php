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
        $requiredEnvVars = ['HOST', 'DB', 'DB_USER', 'CHARSET', 'APP_ENV']; 
        foreach ($requiredEnvVars as $envVar) {
            if (empty($_ENV[$envVar])) {
                $this->manejarError("La variable de entorno $envVar no está definida o está vacía.");
            }
        }

        $this->data = [
            'HOST' => $_ENV['HOST'],
            'DB' => $_ENV['DB'],
            'USER' => $_ENV['DB_USER'],
            'PASSWORD' => $_ENV['PASSWORD'] ?? '', 
            'CHARSET' => $_ENV['CHARSET'],
            'APP_ENV' => $_ENV['APP_ENV'], 
        ];


        if ($this->data['APP_ENV'] === 'production' && empty($this->data['PASSWORD'])) {
            $this->manejarError('La contraseña de la base de datos es requerida en el entorno de producción.');
        }
    }

    private function establecerConexion()
    {
        $dsn = "mysql:host={$this->data['HOST']};dbname={$this->data['DB']};charset={$this->data['CHARSET']}";
        $opt = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

        try {
            if (!$this->conexion instanceof PDO) {
   
                if ($this->data['APP_ENV'] === 'development') {
                    $this->conexion = new PDO($dsn, $this->data['USER'], '', $opt);
                } else {
                    $this->conexion = new PDO($dsn, $this->data['USER'], $this->data['PASSWORD'], $opt);
                }
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {
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
