let conn;
function initWebSocket() {
<<<<<<< HEAD
  conn = new WebSocket("ws://192.168.1.100:8888");
=======
  conn = new WebSocket("ws://192.168.0.8:8888");
>>>>>>> 21c0d6e (arreglos)

  conn.onopen = function (e) {
    console.log("Conexión establecida!");
  };

  conn.onmessage = function (e) {
    const mensaje = JSON.parse(e.data);
    console.log("Mensaje recibido:", mensaje);

    switch (mensaje.tipo) {
      case "rol":
        getRoles();
        break;
      case "pedido":
        getPedidosTotal();
        break;
    }
  };

  conn.onclose = function (e) {
    console.log("Conexión cerrada. Reconectando...");
    setTimeout(function () {
      initWebSocket();
    }, 5000);
  };

  conn.onerror = function (e) {
    console.log("Error en la conexión:", e);
  };
}

<<<<<<< HEAD
// Función para enviar mensajes WebSocket
=======

>>>>>>> 21c0d6e (arreglos)
function sendWebSocketMessage(tipo, accion, data) {
  if (conn && conn.readyState === WebSocket.OPEN) {
    const mensaje = {
      tipo: tipo,
      accion: accion,
      data: data,
    };
    conn.send(JSON.stringify(mensaje));
  } else {
    console.error("WebSocket no está conectado");
  }
}

// Iniciar WebSocket cuando se carga la página
document.addEventListener("DOMContentLoaded",  ()=> {
  initWebSocket();
});
