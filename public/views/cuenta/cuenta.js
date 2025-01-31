let tbCuenta;

document.addEventListener("DOMContentLoaded", () => {
  getCuentas();
  const carrito = JSON.parse(localStorage.getItem("carrito_cuenta")) || [];
  actualizarTablaCarrito(carrito);
});

async function getCuentas() {
  const url = `${BASE_URL}getCuentas`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      if (data.data.length > 0) {
        tbCuenta = $("#tbCuenta").DataTable({
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
              data: "cliente",
            },
            { data: "total" },
            {
              data: null,
              render: (data, type, row) => {
                const fecha = moment(row.fecha_crea, "YYYY-MM-DD HH:mm:ss");

                if (!fecha.isValid()) {
                  return `<span class="badge badge-sm badge-danger">Fecha inválida</span>`;
                }
                const fechaFormateada = fecha.format("DD-MM-YYYY");
                const horaFormateada = fecha.format("HH:mm:ss");
                return `
                      <span class="badge badge-sm badge-secondary">${fechaFormateada}</span><br/>
                      <span class="badge badge-sm badge-light">${horaFormateada}</span>
                  `;
              },
            },
            {
              data: null,
              render: (data, type, row) =>
                `<div class="dropdown">
                    <button class="btn btn-outline-dark btn-sm hover-scale dropdown-toggle" 
                            type="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        Acciones
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#" onclick="agregarProductos('${row.id_cuenta}')">
                                <i class="fa-solid fa-cart-plus me-2"></i>
                                Agregar Productos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="getDetalleCuenta('${row.id_cuenta}')">
                                <i class="fa-solid fa-cash-register me-2"></i>
                                Pagar Cuenta
                            </a>
                        </li>
                    </ul>
                </div>`,
            },
          ],
        });
      } else {
        return toast("No se encontraron cuentas", "info");
      }
    } else {
      return toast("No se encontraron cuentas", "info");
    }
  } catch (error) {
    console.log(error);
  }
}

async function getDetalleCuenta(id) {
  const url = `${BASE_URL}getDetalleCuentas/${id}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    const metodo_pago = document.getElementById("metodo_pago");
    const cuenta = data.data;
    console.log(cuenta);
    if (data.estado === "ok" && data.codigo === 200) {
      const primerProducto = cuenta[0];

      const productoArray = cuenta.map((item) => ({
        id_producto: item.id_producto,
        precio: item.precio,
        cantidad: item.cantidad,
        comision: item.comision,
        subtotal: item.subtotal,
      }));
      const datos = {
        id_pedido: id,
        codigo: primerProducto.codigo,
        usuario_id: primerProducto.id_usuario,
        cliente_id: primerProducto.cliente_id,
        metodo_pago: document.getElementById("metodo_pago").value,
        total: primerProducto.total,
        total_comision: primerProducto.total_comision,
        productos: productoArray,
      };

      localStorage.setItem("datos_venta_cuenta", JSON.stringify(datos));
      const fechaMoment = moment(cuenta.fecha_crea);
      document.getElementById(
        "fecha"
      ).innerHTML = `<b>Fecha : ${fechaMoment.format("DD-MM-YYYY")}</b>`;
      document.getElementById(
        "hora"
      ).innerHTML = `<b>Hora : ${fechaMoment.format("HH:mm:ss")}</b>`;
      document.getElementById(
        "codigo"
      ).innerHTML = `<b>Codigo : ${cuenta[0].codigo}</b>`;
      if (cuenta.usuario_id !== 0) {
        document.getElementById(
          "usuario"
        ).innerHTML = `<b>Acompañante : ${cuenta[0].nombre_usuario} ${cuenta[0].apellido_usuario}</b>`;
      }
      if (cuenta.cliente_id !== 0) {
        document.getElementById(
          "cliente"
        ).innerHTML = `<b>Cliente : ${cuenta[0].nombre_cliente} ${cuenta[0].apellido_cliente}</b>`;
      } else {
        document.getElementById("cliente").innerHTML =
          "<b>Cuenta creada en barra</b>";
      }

      document.getElementById(
        "comision"
      ).innerHTML = `<b>Comision : $ ${cuenta[0].total_comision}</b>`;
      document.getElementById(
        "total"
      ).innerHTML = `<b>Total : $ ${cuenta[0].total}</b>`;

      const productos = cuenta
        .map((item) => {
          return `
    <tr>
      <td>${item.nombre_categoria} ${item.nombre_producto}</td>
      <td>${item.cantidad}</td>
      <td>${item.precio}</td>
      <td>${item.comision}</td>
      <td>${item.subtotal}</td>
    </tr>
  `;
        })
        .join("");

      document.getElementById("detalle_productos").innerHTML = productos;
    }
    document.getElementById("btn_cobrar").onclick = () => {
      if (metodo_pago.value === "0") {
        return toast("Seleccione un metodo de pago", "info");
      }
      cobrarCuenta(cuenta[0].cuenta_id, metodo_pago.value);
    };
  } catch (error) {
    console.log(error);
  }
  $("#ModalDetalleCuenta").modal("show");
}

async function cobrarCuenta(id, metodo_pago) {
  const url = `${BASE_URL}cobrarCuenta`;
  const datos = {
    id_cuenta: id,
    metodo_pago: metodo_pago,
  };
  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;

    if (data.estado === "ok" && data.codigo === 201) {
      createVenta();
    }
  } catch (error) {
    console.log(error);
  }
}

async function createVenta() {
  const nuevoMetodoPago = document.getElementById("metodo_pago").value;
  if (nuevoMetodoPago === "0") {
    return toast("Seleccione un metodo de pago", "info");
  }
  const datos = JSON.parse(localStorage.getItem("datos_venta_cuenta") || []);
  datos.metodo_pago = nuevoMetodoPago;
  localStorage.setItem("datos_venta_cuenta", JSON.stringify(datos));

  const url = `${BASE_URL}createVenta`;

  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;
    console.log(data);
    if (data.codigo === 201 && data.estado === "ok") {
      localStorage.removeItem("datos_venta_cuenta");
      toast("Cuenta cerrada con exito", "success");
      getCuentas();
      $("#ModalDetalleCuenta").modal("hide");
    }
  } catch (error) {
    console.error(error);
  }
}

async function agregarProductos(id) {
  getProductosPrecio();
  $("#ModalAgregarCuenta").modal("show");
  document.getElementById("btn_agregar_cuenta").onclick = () => {
    agregarCuenta(id);
  };
}

async function agregarCuenta(id) {
  const carrito = JSON.parse(localStorage.getItem("carrito_cuenta")) || [];
  if (carrito.length === 0) {
    return toast("No hay productos en el carrito", "info");
  }
  const url = `${BASE_URL}createDetalleCuenta`;
  const datos = {
    cuenta_id: id,
    productos: carrito,
  };
  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 201) {
      toast("Productos agregados con exito", "success");
      localStorage.removeItem("carrito_cuenta");
      const carrito = JSON.parse(localStorage.getItem("carrito_cuenta")) || [];
      actualizarTablaCarrito(carrito);
      $("#ModalAgregarCuenta").modal("hide");
      getCuentas();
    }
  } catch (error) {
    console.log(error);
  }
}

async function getProductosPrecio() {
  const url = `${BASE_URL}getProductosPrecio`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      const precios = data.data;
      const preciosHTML = precios
        .filter((precio) => precio.nombre)
        .map(
          (precio) => `
            <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
              <a onclick="getBebidasPrecio(${precio.precio})">
                <div class="card-wrapper">
                  <div class="card overflow-hidden mb-5 mb-xl-2 shadow-sm parent-hover card overflow-hidden mb-5 mb-xl-2 shadow-sm parent-hover hover-scale btn btn-outline btn-outline-dashed btn-outline-default">
                    <div class="card-body d-flex justify-content-between flex-column px-0 pb-0">
                      <div class="mb-4 px-9">
                        <div class="d-flex align-items-center mb-2">
                          <span class="text-gray-900">
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
    } else {
      return toast("No se encontraron productos registrados", "info");
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
      carElement.innerHTML = '';

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
      $("#ModalAgregarCuenta").modal("hide");
      $("#ModalBebida").modal("show");
    }
  } catch (error) {
    console.error("Error en la petición:", error);
  }
}

function cargarCarrito(id_producto, nombre, precio, comision, cantidad) {
  const parsedCantidad = Number.parseInt(cantidad);
  if (Number.isNaN(cantidad) || cantidad <= 0) {
    document.getElementById(`cantidad-${id_producto}`).focus();
    return toast("La cantidad debe ser mayor a cero", "info");
  }
  const parsedPrecio = Number.parseInt(precio) || 0;
  const subtotal = cantidad * precio;
  const producto = {
    id_producto,
    nombre,
    precio: parsedPrecio,
    cantidad: parsedCantidad,
    subtotal,
    comision,
  };

  const carrito = JSON.parse(localStorage.getItem("carrito_cuenta")) || [];

  const index = carrito.findIndex((item) => item.id_producto === id_producto);
  if (index > -1) {
    carrito[index].cantidad += cantidad;
    carrito[index].subtotal = carrito[index].cantidad * carrito[index].precio;
  } else {
    carrito.push(producto);
  }

  const total = carrito.reduce((acc, item) => {
    return acc + (item.subtotal || 0);
  }, 0);
  const subtotal_ = carrito.reduce((acc, item) => {
    return acc + (item.subtotal || 0);
  }, 0);
  const total_comision = carrito.reduce((acc, item) => {
    return acc + (item.cantidad * item.comision || 0);
  }, 0);

  const totales_cuenta = {
    total: total,
    subtotal: subtotal_,
    total_comision: total_comision,
  };
  localStorage.setItem("carrito_cuenta", JSON.stringify(carrito));
  localStorage.setItem("totales_cuenta", JSON.stringify(totales_cuenta));
  actualizarTablaCarrito(carrito);
  document.getElementById("total_cuenta").innerHTML = `<b>Total : ${total}</b>`;
  $("#ModalAgregarCuenta").modal("show");
  $("#ModalBebida").modal("hide");
}

function actualizarTablaCarrito(carrito) {
  const tbody = document.querySelector("#tbCarritoCuenta tbody");
  tbody.innerHTML = '';

  const rows = carrito.map((item) => {
    const row = document.createElement("tr");
    row.innerHTML = `
              <td>${item.nombre}</td>
              <td>${item.cantidad}</td>
              <td>${item.precio || 0}</td>
              <td>${item.subtotal || 0}</td>
              <td>${item.comision || 0}</td>
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
  let carrito = JSON.parse(localStorage.getItem("carrito_cuenta")) || [];

  carrito = carrito.filter((item) => item.id_producto !== id_producto);
  localStorage.setItem("carrito_cuenta", JSON.stringify(carrito));

  actualizarTablaCarrito(carrito);
}

async function cerrarModal(e) {
  e.preventDefault();
  $("#ModalAgregarCuenta").modal("hide");
  localStorage.removeItem("carrito_cuenta");
  localStorage.removeItem("totales_cuenta");
  const carrito = JSON.parse(localStorage.getItem("carrito_cuenta")) || [];
  actualizarTablaCarrito(carrito);
}
