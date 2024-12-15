<?php

namespace app\config;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class websocket implements MessageComponentInterface
{
    protected $clients;
    private $messageFile;
    private $lastCheck = 0;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->messageFile = __DIR__ . '/../../tmp/websocket_message.txt';
        echo "Servidor WebSocket iniciado\n";

        // Crear directorio tmp si no existe
        if (!file_exists(__DIR__ . '/../../tmp')) {
            mkdir(__DIR__ . '/../../tmp', 0777, true);
        }
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "Nueva conexión ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            $data = json_decode($msg, true);
            if (!isset($data['tipo'])) {
                throw new \Exception("Mensaje no válido");
            }

            // Broadcast del mensaje a todos los clientes
            foreach ($this->clients as $client) {
                $client->send($msg);
            }
            
            echo "Mensaje tipo '{$data['tipo']}' recibido\n";
        } catch (\Exception $e) {
            echo "Error al procesar mensaje: " . $e->getMessage() . "\n";
            $from->send(json_encode([
                'error' => $e->getMessage()
            ]));
        }

        // Verificar si hay mensajes nuevos
        $this->checkNewMessages();
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Cliente desconectado ({$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    private function checkNewMessages()
    {
        if (!file_exists($this->messageFile)) {
            return;
        }

        $currentTime = filemtime($this->messageFile);
        if ($currentTime > $this->lastCheck) {
            $message = file_get_contents($this->messageFile);
            if ($message) {
                // Broadcast el mensaje a todos los clientes
                foreach ($this->clients as $client) {
                    $client->send($message);
                }
                // Limpiar el archivo
                unlink($this->messageFile);
                $this->lastCheck = time();
            }
        }
    }
}
