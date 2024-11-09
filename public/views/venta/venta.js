let tbVenta;
document.addEventListener("DOMContentLoaded", () => {
  getVentas();
  getClientes();
  getChicas();
  getProductosPrecio();
  const carrito = JSON.parse(localStorage.getItem("carrito_venta")) || [];
  actualizarTablaCarrito(carrito);
});

async function getVentas() {
  const url = `${BASE_URL}getVentas`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;

    if (data.codigo === 200 && data.estado === "ok") {
      tbVenta = $("#tbVenta").DataTable({
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
            render: (data, type, row) => `${row.nombre_c} ${row.apellido_c}`,
          },
          { data: "metodo_pago" },
          { data: "total" },
          {
            data: "fecha_crea",
            render: (data, type, row) =>
              moment(data).format("DD/MM/YYYY HH:mm"),
          },

          {
            data: null,
            render: (data, type, row) =>
              `<button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_venta}" onclick="verVenta('${row.id_venta}')">
                        <i class="fa-solid fa-eye"></i>
                      </button> `,
          },
        ],
      });
    } else {
      return toast("No se encontraron ventas registradas", "info");
    }
  } catch (error) {
    console.error(error);
  }
}

async function verVenta(id_venta) {
  const url = `${BASE_URL}getVenta/${id_venta}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      const detalleVenta = data.data;

      if (!detalleVenta.length) {
        toast("No se encontraron detalles de la venta", "info");
        return;
      }

      const hora = moment(detalleVenta[0].fecha_crea).format("HH:mm:ss");
      const fecha = moment(detalleVenta[0].fecha_crea).format("DD/MM/YYYY");

      document.getElementById(
        "hora"
      ).innerHTML = `<i class="fa-solid fa-clock m-2"></i><b>Hora: ${hora}</b>`;
      document.getElementById(
        "fecha"
      ).innerHTML = `<i class="fa-solid fa-calendar-days m-2"></i><b>Fecha: ${fecha}</b>`;
      document.getElementById(
        "codigo"
      ).innerHTML = `<i class="fa-solid fa-tag m-2"></i><b>Codigo: ${detalleVenta[0].codigo}</b>`;

      const usuariosUnicos = [
        ...new Set(
          detalleVenta.map((item) => {
            if (item.nombre_u === null || item.apellido_u === null) {
              return "Venta en barra";
            }
            return `${item.nombre_u} ${item.apellido_u}`;
          })
        ),
      ];

      const esVentaEnBarra = usuariosUnicos.every(
        (usuario) => usuario === "Venta en barra"
      );

      if (esVentaEnBarra) {
        document.getElementById(
          "usuario"
        ).innerHTML = `<i class="fa-solid fa-cash-register m-2"></i><b>${usuariosUnicos.join("<br/>")}</b>`;
      } else {
        document.getElementById(
          "usuario"
        ).innerHTML = `<b>Dama acompañante: <br/> ${usuariosUnicos.join(
          "<br/>"
        )}</b>`;
      }

      document.getElementById(
        "cliente"
      ).innerHTML = `<i class="fa-solid fa-users m-2"></i><b>Cliente: ${detalleVenta[0].nombre_c} ${detalleVenta[0].apellido_a}</b>`;
      document.getElementById(
        "total"
      ).innerHTML = `<b>Total: $${detalleVenta[0].total}</b>`;
      document.getElementById(
        "total_comision"
      ).innerHTML = `<i class="fa-solid fa-hand-holding-dollar m-2"></i><b>Comision: ${detalleVenta[0].total_comision}</b>`;

      document.getElementById(
        "metodo"
      ).innerHTML = `<i class="fa-solid fa-money-bill-transfer m-2"></i><b>Metodo de Pago: ${detalleVenta[0].metodo_pago}</b>`;

      const productosMap = new Map();

      for (const item of detalleVenta) {
        const key = `${item.categoria}-${item.producto}`;
        if (!productosMap.has(key)) {
          productosMap.set(key, {
            producto: `${item.categoria} ${item.producto}`,
            cantidad: Number.parseFloat(item.cantidad),
            precio: Number.parseFloat(item.precio),
            comision: Number.parseFloat(item.comision),
            sub_total: Number.parseFloat(item.sub_total),
          });
        }
      }

      const detalleProductos = document.getElementById("detalle_productos");
      detalleProductos.innerHTML = "";

      for (const item of productosMap) {
        detalleProductos.innerHTML += `
            <tr>
              <td>${item[1].producto}</td>
              <td>${item[1].cantidad}</td>
              <td>${item[1].precio}</td>
              <td>${item[1].sub_total}</td>
            </tr>
          `;
        let total = 0;
        total += item[1].sub_total;
        document.getElementById("total_").innerHTML = `<b>Total: $${total}</b>`;
      }

      $("#ModalDetalleVenta").modal("show");
    }
  } catch (error) {
    console.error(error);
  }
}
function cerrarModal(e) {
  e.preventDefault();
  $("#ModalDetalleVenta").modal("hide");
}
function nuevoVenta(e) {
  e.preventDefault();
  document.getElementById("nuevo_venta").hidden = false;
  document.getElementById("lista_venta").hidden = true;
}
function atras(e) {
  e.preventDefault();
  document.getElementById("nuevo_venta").hidden = true;
  document.getElementById("lista_venta").hidden = false;
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
            <small class="m-5" >${item.categoria} ${item.nombre}</small>
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

  const carrito = JSON.parse(localStorage.getItem("carrito_venta")) || [];

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

  const totales = {
    total: total,
    subtotal: subtotal_,
    total_comision: total_comision,
  };
  localStorage.setItem("carrito_venta", JSON.stringify(carrito));
  localStorage.setItem("totales", JSON.stringify(totales));
  actualizarTablaCarrito(carrito);
  document.getElementById("total").innerText = total;
}
function actualizarTablaCarrito(carrito) {
  const tbody = document.querySelector("#tbCarritoVenta tbody");
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
  let carrito = JSON.parse(localStorage.getItem("carrito_venta")) || [];

  carrito = carrito.filter((item) => item.id_producto !== id_producto);
  localStorage.setItem("carrito_venta", JSON.stringify(carrito));

  actualizarTablaCarrito(carrito);
}
async function getClientes() {
  const url = `${BASE_URL}getClientes`;
  try {
    const response = await axios.get(url, config);
    const datos = response.data;
    if (datos.estado === "ok" && datos.codigo === 200) {
      const select = document.getElementById("cliente_id");
      const defaultOption = document.createElement("option");
      defaultOption.value = "0";
      defaultOption.text = "Seleccione un cliente";
      defaultOption.selected = true;
      select.appendChild(defaultOption);
      for (let i = 0; i < datos.data.length; i++) {
        const cliente = datos.data[i];
        const option = document.createElement("option");
        option.value = cliente.id_cliente;
        option.text = `${cliente.nombre} ${cliente.apellido};`;
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

    if (datos.estado === "ok" && datos.codigo === 200) {
      const select = document.getElementById("usuario_id");
      select.innerHTML = "";
      datos.data.map((chica) => {
        const option = document.createElement("option");
        option.value = chica.usuario_id;
        option.text = `${chica.nombre} ${chica.apellido}`;
        select.appendChild(option);
      });
    }
  } catch (error) {
    console.log(error);
  }
}
async function createVenta(e) {
  e.preventDefault();

  const selectElement = document.getElementById("usuario_id");
  const productos = JSON.parse(localStorage.getItem("carrito_venta")) || [];
  const totales = JSON.parse(localStorage.getItem("totales")) || {};
  let cliente_id = document.getElementById("cliente_id").value;

  if (cliente_id === "0") {
    cliente_id = 1;
  }

  const selectedOptions = [...selectElement.selectedOptions];
  let usuario_id = selectedOptions.map((option) => option.value);

  const minimoAcomprañante = 120000;

  const maxUsuarios =
    Math.floor((totales.total - minimoAcomprañante) / 40000) + 2;

  if (totales.total < minimoAcomprañante) {
    if (selectedOptions.length > 1) {
      toast("solo puede seleccionar a una acompañante", "info");
      return;
    }
  } else {
    if (selectedOptions.length > maxUsuarios) {
      toast(`Puedes seleccionar hasta ${maxUsuarios} acompañantes`, "info");
      return;
    }
  }

  if (selectedOptions.length === 0) {
    totales.total_comision = 0;
    usuario_id = 0;
  }
  if (productos.length === 0) {
    toast("Seleccione al menos un producto", "info");
    return;
  }

  const metodo_pago = document.getElementById("metodo_pago").value;
  if (metodo_pago === "0") {
    toast("Seleccione un metodo de pago", "info");
    return;
  }

  const codigo = generarCodigoAleatorio(8);

  const datos = {
    cliente_id: cliente_id,
    usuario_id: usuario_id,
    productos: productos,
    total: totales.total || 0,
    total_comision: totales.total_comision ,
    subtotal: totales.subtotal || 0,
    metodo_pago: metodo_pago,
    codigo: codigo,
  };

  const url = `${BASE_URL}createVenta`;

  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 201) {
      localStorage.removeItem("carrito_venta");
      localStorage.removeItem("totales");
      toast("Venta creada correctamente", "success");
      atras(e);
      getVentas();
      window.location.reload();
    } 
  } catch (e) {
    console.error(e);
    toast("Error al crear la venta", "error");
  }
}

function mostrarLocalStorage() {
  // Verifica si hay algo en localStorage
  if (localStorage.length === 0) {
    console.log("No hay datos en el localStorage");
    return;
  }

  // Recorre todas las claves almacenadas
  for (let i = 0; i < localStorage.length; i++) {
    const clave = localStorage.key(i);
    const valor = localStorage.getItem(clave);

    // Muestra la clave y su valor en la consola
    console.log(`Clave: ${clave}, Valor: ${valor}`);
  }
}
/* mostrarLocalStorage(); */
function eliminarLocalStorage() {
  localStorage.clear();
  console.log("Todos los datos del localStorage han sido eliminados");
}
function generarCodigoAleatorio(length) {
  const chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  return Array.from({ length }, () =>
    chars.charAt(Math.floor(Math.random() * chars.length))
  ).join("");
}
