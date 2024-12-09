<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
use PHPSocketIO\SocketIO;


$io = new SocketIO(3000);  


$io->origins('*:*');


$io->on('connection', function($socket) use($io) {
    echo "Nuevo cliente conectado\n";
    

    $socket->on('iniciar_contador', function($data) use($socket, $io) {
        $servicio_id = $data['servicio_id'];
        $duracion = $data['duracion'];
        $tiempo_restante = $data['tiempo_restante'];
        
        $io->emit('actualizar_contador', [
            'servicio_id' => $servicio_id,
            'tiempo_restante' => $tiempo_restante
        ]);
        
        if ($tiempo_restante <= 0) {
            $io->emit('finalizar_contador', [
                'servicio_id' => $servicio_id
            ]);
        }
    });
    
    \Workerman\Timer::add(1, function() use($socket) {
        try {
            $pdo = new PDO(
                "mysql:host=localhost;dbname=db_night_club",
                "root",
                "",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $sql = "SELECT P.id_pedido, P.codigo, P.subtotal, P.total, 
                          C.nombre AS nombre_c, C.apellido AS apellido_c, 
                          CH.nombre AS nombre_ch, CH.apellido AS apellido_ch,
                          M.nombre AS nombre_m, M.apellido AS apellido_m 
                   FROM pedidos AS P 
                   JOIN clientes AS C ON P.cliente_id = C.id_cliente 
                   JOIN usuarios AS CH ON P.chica_id = CH.id_usuario 
                   JOIN usuarios AS M ON P.mesero_id = M.id_usuario 
                   WHERE P.estado = 1";
            
            $stmt = $pdo->query($sql);
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $socket->emit('pedidos_update', [
                'estado' => 'ok',
                'codigo' => 200,
                'data' => $pedidos
            ]);
            
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            $socket->emit('pedidos_error', ['error' => $e->getMessage()]);
        }
    });
});

echo "Servidor WebSocket iniciado en el puerto 3000...\n";
Worker::runAll();
