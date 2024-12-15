let tbVenta;

document.addEventListener("DOMContentLoaded", () => {
  getVentas();

  const carrito = JSON.parse(localStorage.getItem("carrito_venta")) || [];
  actualizarTablaCarrito(carrito);
  
  document.getElementById("propina").addEventListener("input", () => {
    const carrito = JSON.parse(localStorage.getItem("carrito_venta")) || [];
    const subtotal = carrito.reduce((acc, item) => acc + (parseFloat(item.subtotal) || 0), 0);
    const propina = parseFloat(document.getElementById("propina").value) || 0;
    const total_a_pagar = subtotal + propina;
    document.getElementById("total").innerText = total_a_pagar;
  });
});
async function getPiezas() {
  const url = `${BASE_URL}getPiezasLibres`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      const select = document.getElementById("pieza_id");
      const defaultOption = document.createElement("option");
      defaultOption.value = "0";
      defaultOption.text = "Seleccione una pieza";
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
async function getVentas() {
  const url = `${BASE_URL}getVentas`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;

    if (data.codigo === 200 && data.estado === "ok") {
      const piezasPromises = data.data.map(async (venta) => {
        if (venta.pieza_id) {
          const nombrePieza = await getNombrePieza(venta.pieza_id);
          return nombrePieza || "Pieza no encontrada";
        }
        return `<span class="badge badge-sm badge-primary">Sin habitacion</span>`;
      });

      const piezas = await Promise.all(piezasPromises);

      tbVenta = $("#tbVenta").DataTable({
        data: data.data.map((venta, index) => ({
          ...venta,
          nombre_pieza: piezas[index],
        })),
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
          {
            data: "nombre_pieza",
            render: (data, type, row) => data,
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

async function getNombrePieza(id_pieza) {
  const url = `${BASE_URL}getPieza/${id_pieza}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;

    if (data.estado === "ok" && data.codigo === 200) {
      return data.data.nombre;
    }
    return null;
  } catch (e) {
    console.log(e);
    return null;
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
        ).innerHTML = `<i class="fa-solid fa-cash-register m-2"></i><b>${usuariosUnicos.join(
          "<br/>"
        )}</b>`;
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
      detalleProductos.innerHTML = ``;

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
  document.getElementById("propina").value = "";
  getClientes();
  getChicas();
  getProductosPrecio();
  getPiezas();

  verificarCategorias();
}

function atras(e) {
  e.preventDefault();
  document.getElementById("nuevo_venta").hidden = true;
  document.getElementById("lista_venta").hidden = false;
  getVentas();
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
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      const carElement = document.getElementById("bebida_card");
      carElement.innerHTML = ``;

      const itemsHTML = data.data
        .map(
          (item) => `
          <input type="hidden" class="form-control" value="${item.id_producto}">
          <div class="input-group input-group-solid mb-3">
            <small class="m-5" >${item.categoria} ${item.nombre}</small>
            <input id="cantidad-${item.id_producto}" type="number" class="form-control form-control-sm form-control-solid" placeholder="Ingrese una cantidad"/>
            <button onclick="cargarCarrito(${item.id_producto},'${item.categoria}','${item.nombre}', ${item.precio}, ${item.comision}, document.getElementById('cantidad-${item.id_producto}').value)" class="btn btn-light-dark btn-block btn-sm hover-elevate-up" type="button">
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

function cargarCarrito(
  id_producto,
  categoria,
  nombre,
  precio,
  comision,
  cantidad
) {
  const parsedCantidad = Number.parseInt(cantidad);
  if (Number.isNaN(cantidad) || cantidad <= 0) {
    document.getElementById(`cantidad-${id_producto}`).focus();
    return toast("La cantidad debe ser mayor a cero", "info");
  }
  const parsedPrecio = Number.parseInt(precio) || 0;
  const subtotal = cantidad * precio;
  const producto = {
    id_producto,
    categoria,
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

  const subtotal_ = carrito.reduce((acc, item) => {
    return acc + (item.subtotal || 0);
  }, 0);
  const total_comision = carrito.reduce((acc, item) => {
    return acc + (item.cantidad * item.comision || 0);
  }, 0);
  
  // Obtener la propina actual y calcular el total
  const propina = parseFloat(document.getElementById("propina").value) || 0;
  const total = subtotal_ + propina;

  const totales = {
    total: total,
    subtotal: subtotal_,
    total_comision: total_comision,
  };
  
  localStorage.setItem("carrito_venta", JSON.stringify(carrito));
  localStorage.setItem("totales", JSON.stringify(totales));
  actualizarTablaCarrito(carrito);
  document.getElementById("total").innerText = total;
  toast("Producto agregado al carrito", "info");
}

function actualizarTablaCarrito(carrito) {
  const tbody = document.querySelector("#tbCarritoVenta tbody");
  tbody.innerHTML = ``;

  const rows = carrito.map((item) => {
    const row = document.createElement("tr");
    row.innerHTML = `
              <td>${item.categoria} ${item.nombre}</td>
              <td>${item.cantidad}</td>
              <td>${item.precio || 0}</td>
              <td>${item.subtotal || 0}</td>
              <td><button onclick="eliminarProducto(${item.id_producto})" class="btn btn-danger btn-icon btn-sm"><i class="fa-solid fa-trash"></i></button></td>
            `;
    return row;
  });

  for (const row of rows) {
    tbody.appendChild(row);
  }

  const subtotal = carrito.reduce((acc, item) => acc + (parseFloat(item.subtotal) || 0), 0);
  const propina = parseFloat(document.getElementById("propina").value) || 0;
  const total_a_pagar = subtotal + propina;
  document.getElementById("total").innerText = total_a_pagar;
}

function eliminarProducto(id_producto) {
  let carrito = JSON.parse(localStorage.getItem("carrito_venta")) || [];
  
  carrito = carrito.filter((item) => item.id_producto !== id_producto);
  
  const subtotal = carrito.reduce((acc, item) => acc + (parseFloat(item.subtotal) || 0), 0);
  const propina = parseFloat(document.getElementById("propina").value) || 0;
  const total = subtotal + propina;
  
  const totales = {
    total: total,
    subtotal: subtotal,
    total_comision: carrito.reduce((acc, item) => acc + (item.cantidad * item.comision || 0), 0)
  };

  localStorage.setItem("carrito_venta", JSON.stringify(carrito));
  localStorage.setItem("totales", JSON.stringify(totales));
  actualizarTablaCarrito(carrito);
  toast("Producto eliminado del carrito", "info");
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
  const pieza_id = document.getElementById("pieza_id").value;
  const tiempo = document.getElementById("tiempo").value;
  let propina = parseFloat(document.getElementById("propina").value) || 0;
  if (propina === "") {
    propina = 0;
  }

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
    pieza_id: pieza_id,
    productos: productos,
 
    total_comision: totales.total_comision,
    subtotal: totales.subtotal || 0,
    metodo_pago: metodo_pago,
    codigo: codigo,
    propina: propina,
    total: totales.total + propina || 0,
  };

  const url = `${BASE_URL}createVenta`;
  const categorias = productos.map((producto) => producto.categoria);
  if (categorias.includes("Champaña")) {
    if (pieza_id === "0") {
      toast("Seleccione una pieza", "info");
      return;
    }
    if (Number.isNaN(tiempo) || tiempo <= 0) {
      toast("Ingrese un tiempo válido para el uso de la pieza", "info");
      return;
    }
    iniciarTemporizadorLocalStorage(tiempo, pieza_id);
    updatePiezaVenta(pieza_id);
    localStorage.removeItem("carrito_venta");
    localStorage.removeItem("totales");
  }

  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;
    console.log(data);
    if (data.estado === "ok" && data.codigo === 201) {
      localStorage.removeItem("carrito_venta");
      localStorage.removeItem("totales");
      toast("Venta creada correctamente", "info");
      atras(e);
    }
  } catch (e) {
    console.error(e);
    toast("Error al crear la venta", "error");
  }
}

function verificarCategorias() {
  const productos = JSON.parse(localStorage.getItem("carrito_venta")) || [];
  const categorias = productos.map((producto) => producto.categoria);
  if (categorias.includes("Champaña")) {
    document.getElementById("pieza_venta").hidden = false;
  } else {
    document.getElementById("pieza_venta").hidden = true;
  }
}

const observer = new MutationObserver(verificarCategorias);
observer.observe(document.body, { childList: true, subtree: true });

document.addEventListener("change", () => {
  verificarCategorias();
});
