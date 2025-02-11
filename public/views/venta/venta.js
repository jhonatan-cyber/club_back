let tbVenta;
document.addEventListener("DOMContentLoaded", async () => {
  await getVentas();

  const carrito = JSON.parse(localStorage.getItem("carrito_venta")) || [];
  actualizarTablaCarrito(carrito);

  document.getElementById("propina").addEventListener("input", () => {
    const carrito = JSON.parse(localStorage.getItem("carrito_venta")) || [];
    const subtotal = carrito.reduce(
      (acc, item) => acc + (Number.parseInt(item.subtotal) || 0),
      0
    );
    const propina =
      Number.parseInt(document.getElementById("propina").value) || 0;
    const total_a_pagar = subtotal + propina;
    document.getElementById(
      "total"
    ).innerText = `$ ${total_a_pagar.toLocaleString("es-CL")}`;
  });
  const observer = new MutationObserver(verificarCategorias);
  observer.observe(document.body, { childList: true, subtree: true });

  document.addEventListener("change", async () => {
    await verificarCategorias();
  });
});

async function getPiezas() {
  const url = `${BASE_URL}getPiezasLibres`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      const select = document.getElementById("pieza_id");
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
            data: "cliente",
          },
          {
            data: "nombre_pieza",
            render: (data, type, row) => data,
          },

          { data: "metodo_pago" },
          {
            data: null,
            render: (data, type, row) =>
              `$ ${row.total.toLocaleString("es-CL")} `,
          },

          {
            data: "fecha_crea",
            render: (data, type, row) =>
              moment(data).format("DD/MM/YYYY HH:mm"),
          },
          {
            data: null,
            render: (data, type, row) =>
              row.estado === 1
                ? `<span class="badge badge-sm badge-success">Activo</span>`
                : `<span class="badge badge-sm badge-info">Anulado</span>`,
          },
          {
            data: null,
            render: (data, type, row) => {
              if (row.estado === 1) {
                return `
                        <button title="Detalles de venta" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_venta}" onclick="verVenta('${row.id_venta}')">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button title="Anular venta" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_venta}" onclick="anularVenta('${row.id_venta}')">
                            <i class="fa-solid fa-store-slash"></i>
                        </button>`;
              }
              return `
                        <button title="Detalles de venta" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_venta}" onclick="verVenta('${row.id_venta}')">
                            <i class="fa-solid fa-eye"></i>
                        </button>`;
            },
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
    detalleVenta = data.data;
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
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
        "codigo_d_venta"
      ).innerHTML = `<i class="fa-solid fa-tag m-2"></i><b>Codigo: ${detalleVenta[0].codigo}</b>`;

      const usuariosUnicos = [
        ...new Set(
          detalleVenta.map((item) => {
            if (item.usuario === null || item.usuario.trim() === "") {
              return "Venta en barra";
            }
            return `${item.usuario}`;
          })
        ),
      ];

      const esVentaEnBarra = usuariosUnicos.every((usuario) => usuario === "");

      if (esVentaEnBarra) {
        document.getElementById(
          "usuario"
        ).innerHTML = `<i class="fa-solid fa-cash-register m-2"></i><b>${usuariosUnicos.join(
          "<br/>"
        )}</b>`;
      } else {
        document.getElementById(
          "usuario"
        ).innerHTML = `<i class="fa-solid fa-user-group m-2"></i><b>Anfitriona(s): <br/> ${usuariosUnicos.join(
          "<br/>"
        )}</b>`;
      }

      document.getElementById(
        "cliente"
      ).innerHTML = `<i class="fa-solid fa-users m-2"></i><b>Cliente: ${detalleVenta[0].cliente}</b>`;
      document.getElementById(
        "total"
      ).innerHTML = `<b>Total: $${detalleVenta[0].total}</b>`;
      document.getElementById(
        "total_comision"
      ).innerHTML = `<i class="fa-solid fa-hand-holding-dollar m-2"></i><b>Comision: $ ${detalleVenta[0].total_comision.toLocaleString(
        "es-CL"
      )}</b>`;

      document.getElementById(
        "metodo"
      ).innerHTML = `<i class="fa-solid fa-money-bill-transfer m-2"></i><b>Metodo de Pago: ${detalleVenta[0].metodo_pago}</b>`;

      const productosMap = new Map();

      for (const item of detalleVenta) {
        const key = `${item.categoria}-${item.producto}`;
        if (!productosMap.has(key)) {
          productosMap.set(key, {
            producto: `${item.categoria} ${item.producto}`,
            cantidad: Number.parseInt(item.cantidad),
            precio: Number.parseInt(item.precio),
            comision: Number.parseInt(item.comision),
            sub_total: Number.parseInt(item.sub_total),
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
              <td>$ ${item[1].precio.toLocaleString("es-CL")}</td>
              <td>$ ${item[1].sub_total.toLocaleString("es-CL")}</td>
            </tr>
          `;
        let total = 0;
        total += item[1].sub_total;
        document.getElementById(
          "total_"
        ).innerHTML = `<b>Total: $ ${total.toLocaleString("es-CL")}</b>`;
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

async function nuevoVenta(e) {
  e.preventDefault();
  document.getElementById("nuevo_venta").hidden = false;
  document.getElementById("lista_venta").hidden = true;
  document.getElementById("propina").value = "";
  await getClientes();
  await getChicas();
  await getProductosPrecio();
  await getPiezas();
  await verificarCategorias();
}

async function atras(e) {
  e.preventDefault();
  document.getElementById("nuevo_venta").hidden = true;
  document.getElementById("lista_venta").hidden = false;
  await getVentas();
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
          (
            precio
          ) => `<div class="col-xl-2 col-md-2 col-sm-6 mb-2"><a onclick="getBebidasPrecio(${precio.precio})" class="text-decoration-none">
          <div class="card shadow-sm btn btn-outline btn-outline-dashed btn-outline-default rounded overflow-hidden cardi">
          <div class="card-body d-flex flex-column align-items-center text-center p-4"><i class="fa-solid fa-martini-glass-citrus fs-2hx text-gray-900 mb-3"></i>
          <h5 class="fw-bold text-gray-900 mb-1">Bebidas de ${precio.precio}</h5></div></div></a></div>`
        )
        .join("");
      document.getElementById("precio_bebidas").innerHTML = preciosHTML;
    }
  } catch (error) {
    return toast(
      "Error al obtener los productos, por favor intente de nuevo",
      "error"
    );
  }
}

async function getBebidasPrecio(precio) {
  const url = `${BASE_URL}getBebidasPrecio/${precio}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" && data.codigo !== 200) {
      return toast("No se encontraron bebidas para ese precio", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      const carElement = document.getElementById("bebida_card");
      carElement.innerHTML = "";
      const itemsHTML = data.data
        .map(
          (
            item
          ) => `<input type="hidden" class="form-control form-control-sm form-control-solid" value="${item.id_producto}"><div class="input-group input-group-solid mb-3">
          <small class="text-muted m-2"><b>${item.categoria} ${item.nombre}</b></small><input min="0" step="1" id="cantidad-${item.id_producto}" type="number" class="form-control form-control-sm form-control-solid" placeholder="Cantidad"/>
          <button title="Agregar al carrito" onclick="cargarCarrito(${item.id_producto},'${item.categoria}','${item.nombre}', ${item.precio}, ${item.comision}, document.getElementById('cantidad-${item.id_producto}').value)" class="btn btn-light-dark btn-sm hover-elevate-up" type="button">
          <i class="fas fa-plus"></i> Agregar</button></div>`
        )
        .join("");

      carElement.innerHTML = itemsHTML;
      $("#ModalBebida").modal("show");
    }
  } catch (error) {
    return toast(
      "Error al obtener las bebidas, por favor intente de nuevo",
      "error"
    );
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

  const propina =
    Number.parseInt(document.getElementById("propina").value) || 0;
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
  return toast("Producto agregado al carrito", "info");
}

function actualizarTablaCarrito(carrito) {
  const tbody = document.querySelector("#tbCarritoVenta tbody");
  tbody.innerHTML = "";

  const rows = carrito.map((item) => {
    const row = document.createElement("tr");
    row.innerHTML = `
              <td>${item.categoria} ${item.nombre}</td>
              <td>${item.cantidad}</td>
              <td>$ ${item.precio.toLocaleString("es-CL") || 0}</td>
              <td>$ ${item.subtotal.toLocaleString("es-CL") || 0}</td>
              <td><button onclick="eliminarProducto(${
                item.id_producto
              })" class="btn btn-danger btn-icon btn-sm"><i class="fa-solid fa-trash"></i></button></td>
            `;
    return row;
  });

  for (const row of rows) {
    tbody.appendChild(row);
  }

  const subtotal = carrito.reduce(
    (acc, item) => acc + (Number.parseInt(item.subtotal) || 0),
    0
  );
  const propina =
    Number.parseInt(document.getElementById("propina").value) || 0;
  const total_a_pagar = subtotal + propina;
  document.getElementById(
    "total"
  ).innerText = `$ ${total_a_pagar.toLocaleString("es-CL")}`;
}

function eliminarProducto(id_producto) {
  let carrito = JSON.parse(localStorage.getItem("carrito_venta")) || [];

  carrito = carrito.filter((item) => item.id_producto !== id_producto);

  const subtotal = carrito.reduce(
    (acc, item) => acc + (Number.parseInt(item.subtotal) || 0),
    0
  );
  const propina =
    Number.parseInt(document.getElementById("propina").value) || 0;
  const total = subtotal + propina;

  const totales = {
    total: total,
    subtotal: subtotal,
    total_comision: carrito.reduce(
      (acc, item) => acc + (item.cantidad * item.comision || 0),
      0
    ),
  };

  localStorage.setItem("carrito_venta", JSON.stringify(carrito));
  localStorage.setItem("totales", JSON.stringify(totales));
  actualizarTablaCarrito(carrito);
  return toast("Producto eliminado del carrito", "info");
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
    return toast(
      "Error al obtener los clientes, por favor intente de nuevo",
      "error"
    );
  }
}

async function getChicas() {
  const url = `${BASE_URL}getChicas`;
  try {
    const response = await axios.get(url, config);
    const datos = response.data;
    if (datos.estado !== "ok" || datos.codigo !== 200) {
      return toast("No hay anfitrionas disponibles", "info");
    }
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
    return toast(
      "Error al obtener las anfitrionas, por favor intente de nuevo",
      "error"
    );
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
  let propina = Number.parseInt(document.getElementById("propina").value) || 0;
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
      return toast("solo puede seleccionar a una acompañante", "info");
    }
  } else {
    if (selectedOptions.length > maxUsuarios) {
      return toast(
        `Puedes seleccionar hasta ${maxUsuarios} acompañantes`,
        "info"
      );
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
      return toast("Seleccione una pieza", "info");
    }
    if (Number.isNaN(tiempo) || tiempo <= 0) {
      return toast("Ingrese un tiempo válido para el uso de la pieza", "info");
    }
    await iniciarTemporizadorLocalStorage(tiempo, pieza_id);
    await updatePiezaVenta(pieza_id);
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
      atras(e);
      return toast("Venta realizada correctamente", "success");
    }
  } catch (e) {
    return toast(
      "Error al crear la venta, por favor intente de nuevo",
      "error"
    );
  }
}

async function verificarCategorias() {
  const productos = JSON.parse(localStorage.getItem("carrito_venta")) || [];
  const categorias = productos.map((producto) => producto.categoria);
  if (categorias.includes("Champaña")) {
    document.getElementById("pieza_venta").hidden = false;
  } else {
    document.getElementById("pieza_venta").hidden = true;
  }
}

async function anularVenta(id_venta) {
  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "¿Está seguro de anular la venta ?",
    icon: "info",
    showCancelButton: true,
    confirmButtonText: "Si, anular",
    cancelButtonText: "No, cancelar",
    customClass: {
      confirmButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
      cancelButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
      popup: "swal2-dark",
      title: "swal2-title",
      htmlContainer: "swal2-html-container",
    },
    buttonsStyling: false,
    confirmButtonColor: "#dc3545",
    background: "var(--bs-body-bg)",
    color: "var(--bs-body-color)",
  });

  if (result.isConfirmed) {
    const d_venta = `${BASE_URL}getVenta/${id_venta}`;
    const url = `${BASE_URL}createDevolucionVenta`;
    try {
      const resp = await axios.get(d_venta, config);
      const data = resp.data;
      if (data.estado === "ok" && data.codigo === 200) {
        const ventas = data.data;

        const usuario_id = [
          ...new Set(ventas.map((venta) => venta.id_usuario)),
        ];

        const producto = [
          ...new Map(
            ventas.map((venta) => [
              venta.id_producto,
              {
                id_producto: venta.id_producto,
                cantidad: venta.cantidad,
                precio: venta.precio,
                comision: venta.comision,
                usuario_id: usuario_id,
              },
            ])
          ).values(),
        ];

        const datos = {
          usuario_id: usuario_id,
          cliente_id: ventas[0].id_cliente,
          total: ventas[0].total,
          venta_id: Number.parseInt(id_venta),
          total_comision: ventas[0].total_comision,
          producto: producto,
        };
        const respuesta = await axios.post(url, datos, config);
        const result = respuesta.data;
        console.log(result);
        if (result.estado === "ok" && result.codigo === 200) {
          await getVentas();
          return toast("Venta anulada con exito", "success");
        }
      }
    } catch (e) {
      return toast(
        "Error al anular la venta, por favor intente de nuevo",
        "error"
      );
    }
  }
}
