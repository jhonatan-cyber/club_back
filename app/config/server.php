<?php

require dirname(__DIR__) . '/../vendor/autoload.php';
header("Access-Control-Allow-Origin: *");

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use app\config\websocket;

try {
    $port = 8888;
    $websocket = new websocket();
    
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                $websocket
            )
        ),
        $port,
        '0.0.0.0'  // Esto permite conexiones desde cualquier IP
    );

    echo "Servidor WebSocket iniciado en el puerto {$port}\n";
    $server->run();
} catch (\Exception $e) {
    echo "Error al iniciar el servidor: " . $e->getMessage() . "\n";
    exit(1);
}
