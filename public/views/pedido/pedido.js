let tbPedido;
const user = JSON.parse(localStorage.getItem("usuario"));
document.addEventListener("DOMContentLoaded", () => {
  getPedidos();

  const propinaInput = document.getElementById("propina");
  const ivaInput = document.getElementById("iva_pedido");
  const totalElement = document.getElementById("total_");
  const metodoPagoSelect = document.getElementById("metodo_pago");
  const selectIva = document.getElementById("select_iva");

  function actualizarTotal() {
    const carrito = JSON.parse(localStorage.getItem("datos_venta")) || {
      total: 0,
    };
    const propina = Number(propinaInput?.value) || 0;
    const iva = Number(ivaInput?.value) || 0;
    const total_a_pagar = carrito.total + propina + iva;

    if (totalElement) {
      totalElement.innerHTML = `TOTAL: $${total_a_pagar
        .toFixed(0)
        .replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`;
    }
  }

  if (propinaInput) {
    propinaInput.addEventListener("input", actualizarTotal);
  }

  if (ivaInput) {
    ivaInput.addEventListener("input", actualizarTotal);
  }

  if (metodoPagoSelect) {
    metodoPagoSelect.addEventListener("change", (e) => {
      if (selectIva) {
        selectIva.hidden = e.target.value !== "Tarjeta";
      }
    });
  }
});

async function getPedidos() {
  let url;
  if (user.rol === "Mesero") {
    url = `${BASE_URL}getPedidosGarzon`;
  } else {
    url = `${BASE_URL}getPedidos`;
  }
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.codigo === 200 && data.estado === "ok") {
      tbPedido = $("#tbPedido").DataTable({
        data: data.data,
        language: LENGUAJE,
        destroy: true,
        responsive: true,
        info: true,
        lengthMenu: [DISPLAY_LENGTH, 10, 25, 50],
        autoWidth: true,
        paging: true,
        searching: true,
        columns: [
          {
            data: null,
            render: (data, type, row, meta) =>
              `<span class="badge badge-sm badge-primary">${formatNumber(
                meta.row + 1
              )}</span>`,
          },
          { data: "codigo" },
          {
            data: "garzon",
          },
          {
            data: "nicks",
          },
          {
            data: "cliente",
          },

          {
            data: "total",
            render: (data, type, row) =>
              data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."),
          },
          {
            data: null,
            render: (data, type, row) => {
              if (row.estado === 1) {
                return `<span class="badge badge-sm badge-info">Pendiente</span>`;
              }
              return `<span class="badge badge-sm badge-success">Vendido</span>`;
            },
          },
          {
            data: null,
            render: (data, type, row) => {
              if (user.rol === "Administrador") {
                return `<button title="Ver detalles" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_pedido}" onclick="verPedido('${row.id_pedido}')">
                <i class="fa-solid fa-eye"></i>
              </button> `;
              }
              return `<button title="Ver detalles" class="btn btn-outline-dark btn-sm hover-scale" disabled>
              <i class="fa-solid fa-eye"></i>
            </button> `;
            },
          },
        ],
      });
    } else {
      return toast("No se encontraron pedidos", "info");
    }
  } catch (error) {
    console.error(error);
  }
}

async function nuevoPedido(e) {
  e.preventDefault();

  const nuevoPedidoElement = document.getElementById("nuevo_pedido");
  const listaPedidoElement = document.getElementById("lista_pedido");

  if (nuevoPedidoElement && listaPedidoElement) {
    nuevoPedidoElement.hidden = false;
    listaPedidoElement.hidden = true;
  }

  await getClientes();
  await getChicas();
  await getProductosPrecio();

  const carritoData = JSON.parse(localStorage.getItem("carritoData")) || {
    carrito: [],
    total: 0,
    subtotal: 0,
    total_comision: 0,
  };

  actualizarTablaCarrito(carritoData.carrito);
  document.getElementById("total").innerText = carritoData.total.toLocaleString(
    "es-ES",
    { maximumFractionDigits: 0 }
  );
}

function atras(e) {
  e.preventDefault();
  document.getElementById("nuevo_pedido").hidden = true;
  document.getElementById("lista_pedido").hidden = false;
}

async function getProductosPrecio() {
  const url = `${BASE_URL}getProductosPrecio`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      const precios = data.data;
      const preciosHTML = precios
        .map(
          (precio) => `
          <div class="col-xl-3 col-md-3 col-sm-4 mb-2"><a onclick="getBebidasPrecio(${precio.precio})" class="text-decoration-none">
          <div class="card shadow-sm btn btn-outline btn-outline-dashed btn-outline-default rounded overflow-hidden cardi">
          <div class="card-body d-flex flex-column align-items-center text-center p-4"><i class="fa-solid fa-martini-glass-citrus fs-2hx text-gray-900 mb-3"></i>
          <h5 class="fw-bold text-gray-900 mb-1">Bebidas de ${precio.precio}</h5></div></div></a></div>`
        )
        .join("");

      document.getElementById("precio_bebidas").innerHTML = preciosHTML;
    }
  } catch (error) {
    console.error(error);
  }
}

async function getBebidasPrecio(precio) {
  const url = `${BASE_URL}getBebidasPrecio/${precio}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;

    if (data.estado === "ok" && data.codigo === 200) {
      const carElement = document.getElementById("bebida_card");
      if (!carElement) {
        return toast("No se encontró el elemento", "info");
      }
      carElement.innerHTML = "";

      const itemsHTML = data.data
        .map(
          (item) => `
          <input type="hidden" class="form-control form-control-sm form-control-solid" value="${item.id_producto}"><div class="input-group input-group-solid mb-3">
          <small class="text-muted m-2"><b>${item.categoria} ${item.nombre}</b></small><input min="0" step="1" id="cantidad-${item.id_producto}" type="number" class="form-control form-control-sm form-control-solid" placeholder="Cantidad"/>
          <button title="Agregar al carrito" onclick="cargarCarrito(${item.id_producto},'${item.categoria}','${item.nombre}', ${item.precio}, ${item.comision}, document.getElementById('cantidad-${item.id_producto}').value)" class="btn btn-light-dark btn-sm hover-elevate-up" type="button">
          <i class="fas fa-plus"></i> Agregar</button></div>`
        )
        .join("");

      carElement.innerHTML = itemsHTML;

      $("#ModalBebida").modal("show");
      $("#ModalBebida").on("shown.bs.modal", () => {
        const firstInput = document.querySelector('[id^="cantidad-"]');
        if (firstInput) firstInput.focus();
      });
    }
  } catch (error) {
    console.error("Error en la petición:", error);
  }
}

function cargarCarrito(
  id_producto,
  categoria,
  nombre,
  precio,
  comision,
  cantidad
) {
  const parsedCantidad = Number.parseInt(cantidad);
  const cantidadInput = document.getElementById(`cantidad-${id_producto}`);
  if (!cantidadInput || parsedCantidad <= 0) {
    cantidadInput?.focus();
    return toast("La cantidad debe ser mayor a cero", "info");
  }

  const parsedPrecio = Number.parseInt(precio) || 0;
  const parsedComision = Number.parseInt(comision) || 0;
  const subtotal = parsedCantidad * parsedPrecio;

  const producto = {
    id_producto,
    categoria,
    nombre,
    precio: parsedPrecio,
    cantidad: parsedCantidad,
    subtotal,
    comision: parsedComision,
  };

  let carritoData = {
    carrito: [],
    total: 0,
    subtotal: 0,
    total_comision: 0,
  };

  try {
    const storedData = localStorage.getItem("carritoData");
    carritoData = storedData ? JSON.parse(storedData) : carritoData;
  } catch (error) {
    return toast("Error al cargar el carrito", "error");
  }

  const { carrito } = carritoData;
  if (!Array.isArray(carrito)) {
    console.error(
      "Error: carrito no es un array, inicializando carrito vacío."
    );
    carritoData.carrito = [];
  }

  const index = carrito.findIndex((item) => item.id_producto === id_producto);
  if (index > -1) {
    carrito[index].cantidad += parsedCantidad;
    carrito[index].subtotal = carrito[index].cantidad * carrito[index].precio;
  } else {
    carrito.push(producto);
  }

  carritoData.subtotal = carrito.reduce((acc, item) => acc + item.subtotal, 0);
  carritoData.total_comision = carrito.reduce(
    (acc, item) => acc + item.comision * item.cantidad,
    0
  );
  carritoData.total = carritoData.subtotal;

  try {
    localStorage.setItem("carritoData", JSON.stringify(carritoData));
  } catch (error) {
    return toast("Error al guardar el carrito", "error");
  }

  actualizarTablaCarrito(carrito);
  actualizarTotalCarrito(carrito);

  return toast("Producto agregado al carrito", "success");
}

function actualizarTotalCarrito(carrito) {
  let carritoArray = carrito;
  if (!Array.isArray(carrito)) {
    console.error("Error: carrito no es un array", carrito);
    carritoArray = [];
  }

  const total = carritoArray.reduce(
    (acc, item) => acc + (item.subtotal || 0),
    0
  );

  document.getElementById("total").innerText = total.toLocaleString("es-CL", {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  });
}

function actualizarTablaCarrito(carrito) {
  const tbody = document.querySelector("#tbCarritoPedido tbody");
  const fragment = document.createDocumentFragment();

  for (const item of carrito) {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${item.categoria} ${item.nombre}</td>
      <td>${item.cantidad}</td>
 <td>${item.precio.toLocaleString("es-CL", {
   minimumFractionDigits: 0,
   maximumFractionDigits: 0,
 })}</td>
  <td>${item.subtotal.toLocaleString("es-CL", {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  })}</td>
      <td>
        <button onclick="eliminarProducto(${item.id_producto})" 
                class="btn btn-danger btn-icon btn-sm">
          <i class="fa-solid fa-trash"></i>
        </button>
      </td>
    `;
    fragment.appendChild(row);
  }

  tbody.innerHTML = "";
  tbody.appendChild(fragment);

  actualizarTotalCarrito(carrito);
}

function eliminarProducto(id_producto) {
  try {
    const carritoData = JSON.parse(localStorage.getItem("carritoData")) || {
      carrito: [],
      total: 0,
      subtotal: 0,
      total_comision: 0,
    };

    carritoData.carrito = carritoData.carrito.filter(
      (item) => item.id_producto !== id_producto
    );

    carritoData.total = carritoData.carrito.reduce(
      (acc, item) => acc + (item.subtotal || 0),
      0
    );

    carritoData.subtotal = carritoData.total;
    carritoData.total_comision = carritoData.carrito.reduce(
      (acc, item) => acc + (item.comision || 0) * (item.cantidad || 0),
      0
    );

    localStorage.setItem("carritoData", JSON.stringify(carritoData));

    actualizarTablaCarrito(carritoData.carrito);
    document.getElementById("total").innerText =`$ ${carritoData.total.toLocaleString("es-CL")}`;
    return toast("Producto eliminado del carrito", "info");
  } catch (error) {
    return toast("Error al eliminar el producto", "warning");
  }
}

async function getClientes() {
  const url = `${BASE_URL}getClientes`;

  try {
    const response = await axios.get(url, config);
    const datos = response.data;

    if (datos.estado === "ok" && datos.codigo === 200) {
      const select = document.getElementById("cliente_id");
      const fragment = document.createDocumentFragment();

      const defaultOption = document.createElement("option");
      defaultOption.value = 0;
      defaultOption.text = "Seleccione un cliente";
      defaultOption.selected = true;
      fragment.appendChild(defaultOption);

      for (const cliente of datos.data) {
        const option = document.createElement("option");
        option.value = cliente.id_cliente;
        option.textContent = `${cliente.nombre} ${cliente.apellido}`;
        fragment.appendChild(option);
      }

      select.innerHTML = "";
      select.appendChild(fragment);
    } else {
      return toast("No se encontraron clientes", "info");
    }
  } catch (error) {
    toast("Error al obtener la lista de clientes", "warning");
  }
}

async function getChicas() {
  const url = `${BASE_URL}getChicas`;
  try {
    const response = await axios.get(url, config);
    const datos = response.data;
    if (datos.estado === "ok" && datos.codigo === 200) {
      const select = document.getElementById("chica_id");
      const fragment = document.createDocumentFragment();

      select.innerHTML = "";

      datos.data.map((chica) => {
        const option = document.createElement("option");
        option.value = chica.usuario_id;
        option.text = chica.nick;
        fragment.appendChild(option);
      });
      select.appendChild(fragment);
    }
  } catch (error) {
    const resp = error.response.data;
    if (resp.codigo === 500 && resp.estado === "error") {
      return toast("Error al obtener las anfitrionas", "error");
    }
    return toast("Error desconocido al obtener las anfitrionas", "error");
  }
}

async function createPedido(e) {
  e.preventDefault();

  let cliente_id = Number(document.getElementById("cliente_id").value) || 1;
  if (cliente_id === 0) {
    cliente_id = 1;
  }

  const selectElement = document.getElementById("chica_id");
  const chica_id = Array.from(selectElement.selectedOptions).map((option) =>
    Number(option.value)
  );

  const carritoData = JSON.parse(localStorage.getItem("carritoData")) || {
    carrito: [],
    total: 0,
    subtotal: 0,
    total_comision: 0,
  };

  if (carritoData.carrito.length === 0) {
    return toast(
      "El carrito está vacío. Agrega productos antes de crear el pedido.",
      "warning"
    );
  }
  const datos = {
    cliente_id,
    chica_id,
    productos: carritoData.carrito,
    total: carritoData.total,
    total_comision: carritoData.total_comision,
    subtotal: carritoData.subtotal,
  };

  const url = `${BASE_URL}createPedido`;

  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;

    if (data.codigo === 201 && data.estado === "ok") {
      toast("Pedido creado exitosamente", "success");
      localStorage.removeItem("carritoData");
      actualizarTablaCarrito([]);
      document.getElementById("total").innerText = "0";
      sendWebSocketMessage("pedido", "createPedido");
      getPedidosTotal();
      atras(e);
      getPedidos();
    }
  } catch (e) {
    const error = e;
    if (error.response.status === 400) {
      return toast(error.response.data.data, "info");
    }
    if (error.response.status === 500) {
      return toast("Error al crear el pedido. Intenta nuevamente.", "error");
    }
    return toast("Error desconocido al procesar la solicitud", "error");
  }
}

async function getPiezas() {
  const url = `${BASE_URL}getPiezasLibres`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      const select = document.getElementById("habitacion_pedido");
      select.innerHTML = "";
      const defaultOption = document.createElement("option");
      defaultOption.value = "0";
      defaultOption.text = "Seleccione una habitación";
      defaultOption.selected = true;
      select.appendChild(defaultOption);

      const piezas = data.data;
      for (let i = 0; i < piezas.length; i++) {
        const pieza = piezas[i];
        const option = document.createElement("option");
        option.value = pieza.id_pieza;
        option.text = `${pieza.nombre}`;
        option.setAttribute("data-precio", pieza.precio);
        select.appendChild(option);
      }
    }
  } catch (error) {
    console.log(error);
  }
}

async function verPedido(id) {
  const url = `${BASE_URL}getDetallePedido/${id}`;
  const obtenerFechaHora = () => {
    const fechaHora = new Date();
    const hour = String(fechaHora.getHours()).padStart(2, "0");
    const minute = String(fechaHora.getMinutes()).padStart(2, "0");
    const second = String(fechaHora.getSeconds()).padStart(2, "0");
    const year = fechaHora.getFullYear();
    const month = String(fechaHora.getMonth() + 1).padStart(2, "0");
    const day = String(fechaHora.getDate()).padStart(2, "0");
    return { hour, minute, second, year, month, day };
  };
  const { hour, minute, second, year, month, day } = obtenerFechaHora();
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);
    if (data.codigo === 200 && data.estado === "ok") {
      const productos = data.data;
      const primerProducto = productos[0];
      if (primerProducto.categoria === "Champaña") {
        getPiezas();
        document.getElementById("select_habitacion").hidden = false;
        document.getElementById("select_tiempo").hidden = false;
      } else {
        document.getElementById("select_habitacion").hidden = true;
        document.getElementById("select_tiempo").hidden = true;
      }
      document.getElementById(
        "hora"
      ).innerHTML = `<i class="fa-solid fa-clock m-2"></i><b>Hora : ${hour}:${minute}:${second}</b>`;
      document.getElementById(
        "fecha"
      ).innerHTML = `<i class="fa-solid fa-calendar-days m-2"></i><b>Fecha : ${year}-${month}-${day}</b>`;
      document.getElementById(
        "codigo_pedido"
      ).innerHTML = `<i class="fa-solid fa-tag m-2"></i><b>Codigo : ${primerProducto.codigo}</b>`;
      document.getElementById(
        "usuario"
      ).innerHTML = `<i class="fa-solid fa-user m-2"></i><b>Anfitriona(s) : ${primerProducto.Anfitriona}</b>`;
      document.getElementById(
        "cliente"
      ).innerHTML = `<i class="fa-solid fa-users m-2"></i><b>Cliente : ${primerProducto.cliente} </b>`;
      document.getElementById(
        "mesero"
      ).innerHTML = `<i class="fa-solid fa-users m-2"></i><b>Garzón : ${primerProducto.garzon} </b>`;
      document.getElementById(
        "total_"
      ).innerHTML = `<b>Total: $${primerProducto.total
        .toFixed(0)
        .replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</b>`;

      document.getElementById("total_comision").value =
        primerProducto.total_comision;

      const detalleProductos = document.getElementById("detalle_productos");
      detalleProductos.innerHTML = "";

      const productosArray = productos.map((item) => {
        const producto = {
          id_producto: item.id_producto,
          precio: item.precio,
          cantidad: item.cantidad,
          comision: item.comision,
          subtotal: item.subtotal,
        };

        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${item.categoria} ${item.producto}</td>
          <td>${item.cantidad}</td>
               <td>${item.precio.toLocaleString("es-ES", {
                 minimumFractionDigits: 0,
                 maximumFractionDigits: 0,
               })}</td>
           <td>${item.comision.toLocaleString("es-ES", {
             minimumFractionDigits: 0,
             maximumFractionDigits: 0,
           })}</td>
             <td>${item.subtotal.toLocaleString("es-ES", {
               minimumFractionDigits: 0,
               maximumFractionDigits: 0,
             })}</td>
        `;
        detalleProductos.appendChild(row);

        return producto;
      });

      const anfitrionas = productos.flatMap((item) =>
        item.anfitriona_id
          .split(",")
          .map((id) => Number.parseInt(id.trim(), 10))
      );
      const datos = {
        id_pedido: Number.parseInt(id),
        codigo: primerProducto.codigo,
        categoria: primerProducto.categoria,
        usuario_id: anfitrionas,
        cliente_id: primerProducto.cliente_id,
        metodo_pago: document.getElementById("metodo_pago").value,
        total: primerProducto.total,
        total_comision: primerProducto.total_comision,
        productos: productosArray,
      };
      localStorage.setItem("datos_venta", JSON.stringify(datos));

      $("#ModalVenta").modal("show");
    }
  } catch (error) {
    console.error("Error en la petición:", error);
  }
}

async function createVenta(e) {
  e.preventDefault();
  const nuevoMetodoPago = document.getElementById("metodo_pago").value;
  if (nuevoMetodoPago === "0") {
    return toast("Seleccione un metodo de pago", "info");
  }
  if (nuevoMetodoPago === "Tarjeta") {
    if (document.getElementById("iva_pedido").value <= 0) {
      return toast("Ingrese un monto mayor a cero para el Iva ", "info");
    }
  }
  const propina = Number.parseInt(document.getElementById("propina").value);
  const datos = JSON.parse(localStorage.getItem("datos_venta") || []);
  const pieza_id = document.getElementById("habitacion_pedido").value;
  const tiempo = document.getElementById("tiempo_pedido").value;

  if (datos.categoria === "Champaña") {
    if (pieza_id === "0") {
      return toast("Seleccione una habitación", "info");
    }
    if (tiempo === "") {
      document.getElementById("tiempo_pedido").focus();
      return toast("Ingrese un tiempo para la habitación", "info");
    }
    if (Number.isNaN(tiempo) || tiempo <= 0) {
      document.getElementById("tiempo_pedido").value = "";
      document.getElementById("tiempo_pedido").focus();
      return toast(
        "Ingrese un tiempo válido para el uso de la habitación",
        "info"
      );
    }
    iniciarTemporizadorLocalStorage(tiempo, pieza_id);
    updatePiezaVenta(pieza_id);
  }

  datos.metodo_pago = nuevoMetodoPago;
  datos.propina = Number.parseInt(propina || 0);
  datos.pieza_id = Number.parseInt(pieza_id || 0);
  datos.iva = Number.parseInt(document.getElementById("iva_pedido").value || 0);

  if (datos.iva > 0) {
    datos.total = Number.parseInt(datos.total) + Number.parseInt(datos.iva);
  }

  if (datos.propina > 0) {
    datos.total = Number.parseInt(datos.total) + Number.parseInt(datos.propina);
  }

  localStorage.setItem("datos_venta", JSON.stringify(datos));
  const url = `${BASE_URL}createVenta`;

  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;

    if (data.codigo === 201 && data.estado === "ok") {
      toast("Venta realizada correctamente", "success");
      localStorage.removeItem("datos_venta");
      getPedidos();
      $("#ModalVenta").modal("hide");
    }
  } catch (error) {
    console.error(error);
  }
}

async function createCuenta() {
  let datos;
  try {
    datos = JSON.parse(localStorage.getItem("datos_venta")) || {};
  } catch (error) {
    console.error("Error al parsear datos de venta:", error);
    datos = {};
  }

  const pieza_id =
    Number(document.getElementById("habitacion_pedido").value) || 0;
  let tiempo = document.getElementById("tiempo_pedido").value.trim();

  const url = `${BASE_URL}createCuenta`;

  if (datos.categoria === "Champaña") {
    if (pieza_id === 0) {
      return toast("Seleccione una habitación", "info");
    }
    tiempo = Number(tiempo);
    if (!tiempo || tiempo <= 0) {
      document.getElementById("tiempo_pedido").value = "";
      document.getElementById("tiempo_pedido").focus();
      return toast(
        "Ingrese un tiempo válido para el uso de la habitación",
        "info"
      );
    }

    iniciarTemporizadorLocalStorage(tiempo, pieza_id);
    updatePiezaVenta(pieza_id);
  }
  datos.pieza_id = Number.parseInt(pieza_id || 0);
  console.log(datos);
  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;
    console.log(data);
    if (data.codigo === 201 && data.estado === "ok") {
      toast("Cuenta creada correctamente", "success");
      localStorage.removeItem("datos_venta");
      getPedidos();
      $("#ModalVenta").modal("hide");
    }
  } catch (error) {
    console.error(error);
    if (error.response) {
      return toast(
        `Error: ${error.response.data.message || "No se pudo crear la cuenta"}`,
        "error"
      );
    }
    return toast("Error de conexión al servidor", "error");
  }
}

function cerrarModalVenta() {
  localStorage.removeItem("datos_venta");
  $("#ModalVenta").modal("hide");
}
