let tbPedido;
document.addEventListener("DOMContentLoaded", () => {
  getPedidos();
  if (document.getElementById("propina")) {
    document.getElementById("propina").addEventListener("input", () => {
      const propina =
        Number.parseInt(document.getElementById("propina").value) || 0;
      const carrito = JSON.parse(localStorage.getItem("datos_venta"));
      const total_a_pagar = carrito.total + propina;
      document.getElementById("total_").innerHTML = ` TOTAL: $${total_a_pagar}`;
    });
  }
});

async function getPedidos() {
  const url = `${BASE_URL}getPedidos`;
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
            data: null,
            render: (data, type, row) => `${row.nombre_m} ${row.apellido_m}`,
          },
          {
            data: null,
            render: (data, type, row) => `${row.nombre_ch} ${row.apellido_ch}`,
          },
          {
            data: null,
            render: (data, type, row) => `${row.nombre_c} ${row.apellido_c}`,
          },

          { data: "total" },
          {
            data: null,
            render: (data, type, row) =>
              `<button title="Ver detalles" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_pedido}" onclick="verPedido('${row.id_pedido}')">
                  <i class="fa-solid fa-eye"></i>
                </button> `,
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

function nuevoPedido(e) {
  e.preventDefault();

  const nuevoPedidoElement = document.getElementById("nuevo_pedido");
  const listaPedidoElement = document.getElementById("lista_pedido");

  if (nuevoPedidoElement && listaPedidoElement) {
    nuevoPedidoElement.hidden = false;
    listaPedidoElement.hidden = true;
  }

  getClientes();
  getChicas();
  getProductosPrecio();

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
          <div class="col-xl-3 col-md-3 col-sm-4 mb-2">
            <a onclick="getBebidasPrecio(${precio.precio})">
              <div class="card-wrapper">
                <div class="card overflow-hidden mb-5 mb-xl-2 shadow-sm parent-hover card overflow-hidden mb-5 mb-xl-2 shadow-sm parent-hover hover-scale btn btn-outline btn-outline-dashed btn-outline-default">
                  <div class="card-body d-flex justify-content-between flex-column px-0 pb-0">
                    <div class="mb-4 px-9">
                      <div class="d-flex align-items-center mb-2">
                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                          <i class="fa-solid fa-martini-glass-citrus"></i>
                          <small>Bebidas de ${precio.precio}</small>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>
        `
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
      carElement.innerHTML = "";

      const itemsHTML = data.data
        .map(
          (item) => `
        <input type="hidden" class="form-control" value="${item.id_producto}">
        <div class="input-group input-group-solid mb-3">
          <small style="font-size: 1rem; width: auto; min-width: 120px;">${item.categoria} ${item.nombre}</small>
          <input id="cantidad-${item.id_producto}" type="number" class="form-control form-control-sm form-control-solid" placeholder="Ingrese una cantidad" style="width: 100px;" min="1" />
          <button onclick="cargarCarrito(${item.id_producto}, '${item.nombre}', ${item.precio}, ${item.comision}, document.getElementById('cantidad-${item.id_producto}').value)" class="btn btn-light-dark btn-block btn-sm hover-elevate-up" type="button">
            <i class="fas fa-plus"></i> Agregar
          </button>
        </div>
      `
        )
        .join("");

      carElement.innerHTML = itemsHTML;

      $("#ModalBebida").modal("show");
    }
  } catch (error) {
    console.error("Error en la petición:", error);
  }
}

function cargarCarrito(id_producto, nombre, precio, comision, cantidad) {
  const parsedCantidad = Number.parseInt(cantidad);
  if (Number.isNaN(parsedCantidad) || parsedCantidad <= 0) {
    document.getElementById(`cantidad-${id_producto}`).focus();
    return toast("La cantidad debe ser mayor a cero", "info");
  }

  const parsedPrecio = Number.parseInt(precio) || 0;
  const subtotal = parsedCantidad * parsedPrecio;

  const producto = {
    id_producto,
    nombre,
    precio: parsedPrecio,
    cantidad: parsedCantidad,
    subtotal,
    comision,
  };

  const carritoData = JSON.parse(localStorage.getItem("carritoData")) || {
    carrito: [],
    total: 0,
    subtotal: 0,
    total_comision: 0,
  };

  const { carrito } = carritoData;

  const index = carrito.findIndex((item) => item.id_producto === id_producto);
  if (index > -1) {
    carrito[index].cantidad += parsedCantidad;
    carrito[index].subtotal = carrito[index].cantidad * carrito[index].precio;
  } else {
    carrito.push(producto);
  }

  const nuevoSubtotal = carrito.reduce(
    (acc, item) => acc + (item.subtotal || 0),
    0
  );
  const nuevoTotalComision = carrito.reduce(
    (acc, item) => acc + (item.comision * item.cantidad || 0),
    0
  );

  carritoData.carrito = carrito;
  carritoData.total = nuevoSubtotal;
  carritoData.subtotal = nuevoSubtotal;
  carritoData.total_comision = nuevoTotalComision;

  localStorage.setItem("carritoData", JSON.stringify(carritoData));

  actualizarTablaCarrito(carrito);
  document.getElementById("total").innerText = nuevoSubtotal.toLocaleString(
    "es-ES",
    { maximumFractionDigits: 0 }
  );
}

function actualizarTablaCarrito(carrito) {
  const tbody = document.querySelector("#tbCarritoPedido tbody");
  tbody.innerHTML = "";

  const rows = carrito.map((item) => {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>${item.nombre}</td>
            <td>${item.cantidad}</td>
            <td>${item.precio || 0}</td>
            <td>${item.subtotal || 0}</td>
            <td><button onclick="eliminarProducto(${
              item.id_producto
            })" class="btn btn-danger btn-icon btn-sm"><i class="fa-solid fa-trash"></i></button></td>
          `;
    return row;
  });

  for (const row of rows) {
    tbody.appendChild(row);
  }

  const total = carrito.reduce((acc, item) => acc + (item.subtotal || 0), 0);

  document.getElementById("total").innerText = total.toFixed(2);
}

function eliminarProducto(id_producto) {
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
    (acc, item) => acc + (item.comision * item.cantidad || 0),
    0
  );
  localStorage.setItem("carritoData", JSON.stringify(carritoData));
  actualizarTablaCarrito(carritoData.carrito);
  document.getElementById("total").innerText = carritoData.total;
}
async function getClientes() {
  const url = `${BASE_URL}getClientes`;
  try {
    const response = await axios.get(url, config);
    const datos = response.data;
    if (datos.estado === "ok" && datos.codigo === 200) {
      const select = document.getElementById("cliente_id");
      select.innerHTML = "";
      const defaultOption = document.createElement("option");
      defaultOption.value = 0;
      defaultOption.text = "Seleccione un cliente";
      defaultOption.selected = true;
      select.appendChild(defaultOption);
      for (let i = 0; i < datos.data.length; i++) {
        const cliente = datos.data[i];
        const option = document.createElement("option");
        option.value = cliente.id_cliente;
        option.text = `${cliente.nombre} ${cliente.apellido}`;
        select.appendChild(option);
      }
    }
  } catch (error) {
    console.log(error);
  }
}

async function getChicas() {
  const url = `${BASE_URL}getChicas`;
  try {
    const response = await axios.get(url, config);
    const datos = response.data;
    console.log(datos);
    if (datos.estado === "ok" && datos.codigo === 200) {
      const select = document.getElementById("chica_id");
      select.innerHTML = "";
      datos.data.map((chica) => {
        const option = document.createElement("option");
        option.value = chica.usuario_id;
        option.text = `${chica.nombre} ${chica.apellido}`;
        select.appendChild(option);
      });
    }
  } catch (error) {
    const resp = error.response.data;
    if (resp.codigo === 500 && resp.estado === "error") {
      return toast("Error al abrir la caja", "error");
    }
  }
}

async function createPedido(e) {
  e.preventDefault();

  let cliente_id = document.getElementById("cliente_id").value;
  if (cliente_id === 0) {
    cliente_id = 1;
  }

  const selectElement = document.getElementById("chica_id");
  const selectedOptions = [...selectElement.selectedOptions];
  const chica_id = selectedOptions.map((option) => option.value);

  const carritoData = JSON.parse(localStorage.getItem("carritoData")) || {
    carrito: [],
    total: 0,
    subtotal: 0,
    total_comision: 0,
  };

  const datos = {
    cliente_id: Number(cliente_id),
    chica_id: chica_id.map((id) => Number(id)),
    productos: carritoData.carrito,
    total: carritoData.total,
    total_comision: carritoData.total_comision,
    subtotal: carritoData.subtotal,
  };
  const url = `${BASE_URL}createPedido`;
  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;
    console.log(data);
    if (data.codigo === 201 && data.estado === "ok") {
      toast("Pedido creado exitosamente", "success");
      localStorage.removeItem("carritoData");
      actualizarTablaCarrito([]);
      document.getElementById("total").innerText = 0;
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
      toast("Error al crear el pedido. Intenta nuevamente.", "error");
    }
  }
}
async function getPiezas() {
  const url = `${BASE_URL}getPiezasLibres`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      const select = document.getElementById("habitacion_pedido");
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
      ).innerHTML = `<i class="fa-solid fa-user m-2"></i><b>Dama acompañante : ${primerProducto.nombre_ch} ${primerProducto.apellido_ch}</b>`;
      document.getElementById(
        "cliente"
      ).innerHTML = `<i class="fa-solid fa-users m-2"></i><b>Cliente : ${primerProducto.nombre_cl} ${primerProducto.apellido_cl}</b>`;
      document.getElementById(
        "mesero"
      ).innerHTML = `<i class="fa-solid fa-users m-2"></i><b>Mesero : ${primerProducto.nombre_m} ${primerProducto.apellido_m}</b>`;
      document.getElementById(
        "total_"
      ).innerHTML = `<b>Total: $${primerProducto.total}</b>`;
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
          <td>${item.categoria} ${item.nombre}</td>
          <td>${item.cantidad}</td>
          <td>${Number.parseInt(item.precio || 0)}</td>
          <td>${Number.parseInt(item.comision || 0)}</td>
          <td>${Number.parseInt(item.subtotal || 0)}</td>
        `;
        detalleProductos.appendChild(row);

        return producto;
      });

      const datos = {
        id_pedido: id,
        codigo: primerProducto.codigo,
        categoria: primerProducto.categoria,
        chica_id: primerProducto.chica_id,
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
  const propina = Number.parseInt(document.getElementById("propina").value);
  const datos = JSON.parse(localStorage.getItem("datos_venta") || []);
  datos.metodo_pago = nuevoMetodoPago;
  datos.propina = propina;
  localStorage.setItem("datos_venta", JSON.stringify(datos));
  const pieza_id = document.getElementById("habitacion_pedido").value;
  const tiempo = document.getElementById("tiempo_pedido").value;
  const categorias = JSON.parse(localStorage.getItem("datos_venta")) || [];
  if (categorias.includes("Champaña")) {
    if (pieza_id === "0") {
      return toast("Seleccione una habitación", "info");
    }
    if (tiempo === "") {
      return toast("Ingrese un tiempo para la habitación", "info");
    }
    if (Number.isNaN(tiempo) || tiempo <= 0) {
      return toast("Ingrese un tiempo válido para el uso de la pieza", "info");
    }
    iniciarTemporizadorLocalStorage(tiempo, pieza_id);
    updatePiezaVenta(pieza_id);
    localStorage.removeItem("datos_venta");
  }
  console.log(categorias);

  /*   const url = `${BASE_URL}createVenta`;
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
  } */
}

async function createCuenta() {
  const datos = JSON.parse(localStorage.getItem("datos_venta") || []);
  const url = `${BASE_URL}createCuenta`;

  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;
    if (data.codigo === 201 && data.estado === "ok") {
      toast("Cuenta creada correctamente", "success");
      localStorage.removeItem("datos_venta");
      getPedidos();
      $("#ModalVenta").modal("hide");
    }
  } catch (error) {
    console.error(error);
  }
}
