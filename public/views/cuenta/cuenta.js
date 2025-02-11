let tbCuenta;

document.addEventListener("DOMContentLoaded", () => {
  getCuentas();
  const carrito = JSON.parse(localStorage.getItem("carrito_cuenta")) || [];
  actualizarTablaCarrito(carrito);

  const propinaInput = document.getElementById("propina_cuenta");
  const ivaInput = document.getElementById("iva_cuenta");
  const totalElement = document.getElementById("total_cuenta");
  const metodoPagoSelect = document.getElementById("metodo_pago");
  const selectIva = document.getElementById("select_iva");

  function actualizarTotal() {
    const carrito_datos = JSON.parse(
      localStorage.getItem("datos_venta_cuenta")
    ) || {
      total: 0,
    };
    const propina = Number(propinaInput?.value) || 0;
    const iva = Number(ivaInput?.value) || 0;
    const total_a_pagar = carrito_datos.total + propina + iva;

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

async function getCuentas() {
  const url = `${BASE_URL}getCuentas`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" && data.codigo !== 200) {
      return toast("No se encontraron cuentas", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      if (data.data.length <= 0) {
        return toast("No se encontraron cuentas", "info");
      }

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
              row.estado === 1
                ? `<span class="badge badge-sm badge-danger">Por cobrar</span>`
                : `<span class="badge badge-sm badge-success">Pagado</span>`,
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
    }
  } catch (error) {
    console.log(error);
  }
}

async function getDetalleCuenta(id) {
  const url = `${BASE_URL}getDetalleCuentas/${id}`;
  try {
    const resp = await axios.get(url, config);
    const { data } = resp;
    const metodo_pago = document.getElementById("metodo_pago");
    const { data: cuenta } = data;
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      const { cuenta: datos_cuenta, detalle_cuenta: datos_detalle } = cuenta;

      const productoArray = datos_detalle.map(
        ({ id_producto, precio, cantidad, comision, subtotal }) => ({
          id_producto,
          precio,
          cantidad,
          comision,
          subtotal,
        })
      );

      const usuarios = datos_detalle.map(({ id_usuario }) => id_usuario);

      const datos = {
        id_cuenta: datos_cuenta.id_cuenta,
        codigo: datos_cuenta.codigo,
        usuario_id: usuarios,
        cliente_id: datos_cuenta.id_cliente,
        metodo_pago: metodo_pago.value,
        iva: 0,
        propina: 0,
        pieza_id: 0,
        total: datos_cuenta.total,
        total_comision: datos_cuenta.total_comision,
        productos: productoArray,
      };

      actualizarDOM(datos_cuenta, datos_detalle);

      localStorage.setItem("datos_venta_cuenta", JSON.stringify(datos));
      document.getElementById("btn_cobrar").onclick = () => {
        if (metodo_pago.value === "0") {
          return toast("Seleccione un metodo de pago", "info");
        }
        createVenta();
      };

      $("#ModalDetalleCuenta").modal("show");
    }
  } catch (error) {
    console.error("Error al obtener los detalles de la cuenta:", error);
  }
}

function actualizarDOM(datos_cuenta, datos_detalle) {
  const fechaMoment = moment(datos_cuenta.fecha);
  document.getElementById("fecha").innerHTML = `<b>Fecha : ${fechaMoment.format(
    "DD-MM-YYYY"
  )}</b>`;
  document.getElementById(
    "codigo_cuenta"
  ).innerHTML = `<b>Codigo : ${datos_cuenta.codigo}</b>`;

  const anfitrionas = [
    ...new Set(
      datos_detalle.flatMap(({ anfitrionas }) =>
        anfitrionas.flatMap((nombre) =>
          nombre.split(",").map((n) => n.trim().toLowerCase())
        )
      )
    ),
  ]
    .map((nombre) =>
      nombre
        .split(" ")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ")
    )
    .join(", ");

  if (anfitrionas.length > 0) {
    document.getElementById(
      "usuario"
    ).innerHTML = `<b>Anfitriona(s): ${anfitrionas}</b>`;
  }

  if (datos_cuenta.id_cliente !== 0) {
    document.getElementById(
      "cliente"
    ).innerHTML = `<b>Cliente : ${datos_cuenta.cliente}</b>`;
  } else {
    document.getElementById("cliente").innerHTML =
      "<b>Cuenta creada en barra</b>";
  }

  document.getElementById("comision").innerHTML = `<b>Comision : $ ${Number(
    datos_cuenta.total_comision
  ).toLocaleString("es-ES")}</b>`;
  document.getElementById("total_cuenta").innerHTML = `<b>Total : $ ${Number(
    datos_cuenta.total
  ).toLocaleString("es-ES")}</b>`;

  const productos = datos_detalle
    .map(
      ({
        fecha_crea,
        anfitrionas,
        producto,
        cantidad,
        precio,
        comision,
        subtotal,
      }) => {
        return `
    <tr>
      <td>${moment(fecha_crea).format("hh:mm:ss")}</td>
      <td>${anfitrionas}</td>
      <td>${producto}</td>
      <td>${cantidad}</td>
      <td>${Number(precio).toLocaleString("es-ES")}</td>
      <td>${Number(comision).toLocaleString("es-ES")}</td>
      <td>${Number(subtotal).toLocaleString("es-ES")}</td>
    </tr>
  `;
      }
    )
    .join("");

  document.getElementById("detalle_productos").innerHTML = productos;
}

async function createVenta() {
  const datos = JSON.parse(localStorage.getItem("datos_venta_cuenta") || []);
  const nuevoMetodoPago = document.getElementById("metodo_pago").value;
  const propina = Number.parseInt(
    document.getElementById("propina_cuenta").value || 0
  );
  let iva = Number.parseInt(document.getElementById("iva_cuenta").value);
  if (nuevoMetodoPago === "0") {
    return toast("Seleccione un metodo de pago", "info");
  }
  if (nuevoMetodoPago === "Tarjeta" && iva.value <= 0) {
    document.getElementById("iva_cuenta").focus();
    return toast("Seleccione un monto mayor a cero par ael iva", "info");
  }
  let total = datos.total;
  if (iva === null || iva === "") {
    iva = 0;
  }
  if (iva > 0) {
    total = total + iva;
  }
  if (propina > 0) {
    total = total + propina;
  }

  datos.metodo_pago = nuevoMetodoPago;
  datos.propina = propina;
  datos.iva = iva;
  datos.total = total;

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
  tbody.innerHTML = "";

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

  document.getElementById("total_cuenta").innerText = total;
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
