let conn;
function initWebSocket() {
  conn = new WebSocket("ws://192.168.0.8:8888");

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

document.addEventListener("DOMContentLoaded", () => {
  initWebSocket();
  const usuario = JSON.parse(localStorage.getItem("usuario"));
  if (usuario.rol === "Administrador" || usuario.rol === "Cajero") {
    getCodigo();
    setInterval(getCodigo, 70000)
  }
});

async function getCodigo() {
  const url = `${BASE_URL}getCodigo`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      if(document.getElementById("codigo")){
        document.getElementById("codigo").innerHTML = data.data.codigo;
      }
    }
  } catch (error) {
    console.log(error);
  }
}
