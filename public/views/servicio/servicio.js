let tbServicio;
window.addEventListener("globalTimerUpdate", (e) => {
  const { id, remainingTime } = e.detail;
  const displayElement = document.getElementById(`tiempo-servicio-${id}`);
  if (displayElement) {
    const minutos = Math.floor(remainingTime / 60);
    const segundos = remainingTime % 60;
    displayElement.textContent = `${minutos
      .toString()
      .padStart(2, "0")}:${segundos.toString().padStart(2, "0")}`;
    displayElement.classList.remove("tiempo-critico", "tiempo-advertencia");
    if (remainingTime <= 60) {
      displayElement.classList.add("tiempo-critico");
    } else if (remainingTime <= 180) {
      displayElement.classList.add("tiempo-advertencia");
    }
  }
});

document.addEventListener("DOMContentLoaded", () => {
  localStorage.removeItem("datos_servicio");
  localStorage.removeItem("carrito_cuenta");
  localStorage.removeItem("totales");
  getServicios();
  validaciones();

  document
    .getElementById("metodo_pago_c")
    .addEventListener("change", cobrarCuenta);
});

function validaciones() {
  document.getElementById("precio").addEventListener("input", validarPrecio);

  document
    .getElementById("metodo_pago")
    .addEventListener("change", validarMetodoPago);
  document.getElementById("iva").addEventListener("input", validarIva);
}

function nuevoServicio(e) {
  e.preventDefault();
  document.getElementById("nuevo_servicio").hidden = false;
  document.getElementById("lista_servicio").hidden = true;
  document.getElementById("btn-registrar").hidden = false;
  document.getElementById("btn-generar").hidden = true;
  getClientes();
  getChicas();
  getPiezas();
}

function atras() {
  document.getElementById("nuevo_servicio").hidden = true;
  document.getElementById("lista_servicio").hidden = false;
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

function validarPrecio() {
  let precio = document.getElementById("precio").value;
  if (precio === "") {
    precio = 0;
  }
  if (precio < 0) {
    toast("El precio no puede ser negativo", "info");
    return;
  }
}

function validarMetodoPago() {
  const metodo_pago = document.getElementById("metodo_pago").value;
  const iva = document.getElementById("iva");
  if (metodo_pago === "0") {
    toast("Seleccione un metodo de pago", "info");
    return;
  }
  if (metodo_pago === "Tarjeta") {
    iva.disabled = false;
    validarIva();
    document.getElementById("iva").focus();
  } else {
    iva.disabled = true;
    validarIva();
  }
}

function validarIva() {
  const ivaInput = document.getElementById("iva");
  let iva = ivaInput.value;
  if (!ivaInput.disabled && (iva === "" || Number.isNaN(iva) || iva < 0)) {
    toast("El iva no puede ser menor a cero", "info");
    return;
  }
  if (ivaInput.disabled && iva === "") {
    iva = 0;
  }
}

async function createServicio(e) {
  e.preventDefault();
  const codigo = generarCodigoAleatorio(8);
  let cliente_id = document.getElementById("cliente_id").value || 1;
  const metodo_pago = document.getElementById("metodo_pago").value;
  const precio_servicio = document.getElementById("precio").value;
  const pieza_id = document.getElementById("pieza_id").value;
  const iva = document.getElementById("iva").value || 0;
  const tiempo = document.getElementById("tiempo").value;
  const selectElement = document.getElementById("usuario_id");
  const precio_pieza = document
    .getElementById("pieza_id")
    .options[document.getElementById("pieza_id").selectedIndex].getAttribute(
      "data-precio"
    );
  const usuario_id = [...selectElement.selectedOptions].map(
    (option) => option.value
  );

  if (cliente_id === "0") {
    cliente_id = 1;
  }

  if (pieza_id === "0") {
    toast("Seleccione una pieza", "info");
    return;
  }
  if (usuario_id.length === 0) {
    toast("Seleccione una dama acompañante", "info");
    return;
  }

  if (metodo_pago === "0") {
    toast("Seleccione un metodo de pago", "info");
    return;
  }
  if (tiempo === "") {
    toast("Ingrese el tiempo de servicio", "info");
    return;
  }
  if (tiempo < 0) {
    toast("El tiempo de servicio no puede ser negativo", "info");
    return;
  }
  if (tiempo === 0) {
    toast("El tiempo de servicio no puede ser 0", "info");
    return;
  }

  if (precio_pieza < 0) {
    toast("El precio de la pieza no puede ser negativo", "info");
    return;
  }

  const datos = {
    cliente_id: Number(cliente_id),
    usuario_id: Number(usuario_id),
    metodo_pago: metodo_pago,
    codigo: codigo,
    pieza_id: Number(pieza_id),
    precio_servicio: Number(precio_servicio),
    iva: Number(iva),
    tiempo: Number(tiempo),
    precio_pieza: Number(precio_pieza),
    total: Number(precio_servicio) + Number(iva) + Number(precio_pieza),
  };

  document.getElementById("total").innerText = datos.total;

  if (cliente_id !== 1 && usuario_id.length > 0) {
    const confirmed = await confirmar();
    if (confirmed) {
      localStorage.setItem("datos_servicio", JSON.stringify(datos));
      document.getElementById("producto_servicio").hidden = false;
      document.getElementById("btn-registrar").hidden = true;
      document.getElementById("btn-generar").hidden = false;
      getProductosPrecio();
    } else {
      await servicio(datos);
    }
  }
  if (cliente_id === 1 && usuario_id.length > 0) {
    await servicio(datos);
  }
}

async function confirmar() {
  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "¿Quiere crear una cuenta de consumo para el cliente?",
    icon: "info",
    showCancelButton: true,
    confirmButtonText: "Si, Crear cuenta",
    cancelButtonText: "No, Seguir con el servicio",
    customClass: {
      confirmButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
      cancelButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
      popup: "swal2-dark",
      title: "swal2-title",
      htmlContainer: "swal2-html-container"
    },
    buttonsStyling: false,
    confirmButtonColor: "#dc3545",
    background: "var(--bs-body-bg)",
    color: "var(--bs-body-color)",
  });
  return result.isConfirmed;
}

async function servicio(datos) {
  const url = `${BASE_URL}createServicio`;
  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 201) {
      localStorage.removeItem("datos_servicio");
      localStorage.removeItem("carrito_cuenta");
      localStorage.removeItem("totales");
      toast("Servicio creado correctamente", "success");
      atras();
      reset();
      getServicios();
    }
  } catch (error) {
    console.error("Error creando el servicio:", error);
  }
}

async function getServicios() {
  const url = `${BASE_URL}getServicios`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;

    if (data.estado !== "ok" || data.codigo !== 200) {
      return toast("No se encontraron servicios", "info");
    }

    const serviciosHTML = await Promise.all(
      data.data.map(async (servicio) => createServicioHTML(servicio))
    );

    document.getElementById("servicios").innerHTML = serviciosHTML.join("");
  } catch (error) {
    console.error("Error al obtener servicios:", error);
  }
}

async function createServicioHTML(servicio) {
  const nombrePieza = await getNombrePieza(servicio.id_pieza);
  const tiempoRestante = getGlobalTimerRemaining(servicio.id_servicio);
  if (tiempoRestante === 0 && servicio.tiempo > 0) {
    iniciarContador(servicio.tiempo, servicio);
  }

  const segundosTotales =
    tiempoRestante > 0 ? tiempoRestante : servicio.tiempo * 60;
  const minutos = Math.floor(segundosTotales / 60);
  const segundos = segundosTotales % 60;
  const tiempoHTML = `${minutos.toString().padStart(2, "0")}:${segundos
    .toString()
    .padStart(2, "0")}`;

  return renderServicioCard(servicio, tiempoHTML);
}

async function getNombrePieza(idPieza) {
  const pieza = `${BASE_URL}getPieza/${idPieza}`;
  const respPieza = await axios.get(pieza, config);
  return respPieza.data.data.nombre;
}

function iniciarContador(tiempo, servicio) {
  startGlobalTimer(servicio.id_servicio, tiempo, {
    habitacion: servicio.habitacion,
    codigo: servicio.codigo,
    id_pieza: servicio.id_pieza,
  });
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
      carElement.innerHTML = ``;

      const itemsHTML = data.data
        .map(
          (item) => `
          <input type="hidden" class="form-control" value="${item.id_producto}">
          <div class="input-group input-group-solid mb-3">
            <small style="font-size: 1rem; width: auto; min-width: 120px;">${item.categoria} ${item.nombre}</small>
            <input id="cantidad-${item.id_producto}" type="number" class="form-control form-control-sm form-control-solid" placeholder="Ingrese una cantidad" style="width: 100px;" min="1" />
            <button onclick="cargarCarrito(${item.id_producto},'${item.nombre}', ${item.precio}, ${item.comision}, document.getElementById('cantidad-${item.id_producto}').value)" class="btn btn-light-dark btn-block btn-sm hover-elevate-up" type="button">
              <i class="fa-solid fa-plus"></i> Agregar
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
  try {
    if (
      !id_producto ||
      !nombre ||
      precio === undefined ||
      comision === undefined
    ) {
      toast("Los parámetros son inválidos", "info");
      return;
    }

    const producto = {
      id_producto: Number(id_producto),
      nombre: String(nombre).trim(),
      precio: Number(precio),
      cantidad: Number(cantidad),
      comision: Number(comision),
      subtotal: 0,
    };

    if (isNaN(producto.id_producto) || producto.id_producto <= 0) {
      return toast("ID de producto inválido", "info");
    }
    if (isNaN(producto.precio) || producto.precio < 0) {
      return toast("Precio inválido", "info");
    }
    if (isNaN(producto.cantidad) || producto.cantidad <= 0) {
      document.getElementById(`cantidad-${id_producto}`).focus();
      return toast("La cantidad debe ser mayor a cero", "info");
    }
    if (isNaN(producto.comision) || producto.comision < 0) {
      return toast("Comisión inválida", "info");
    }
    producto.subtotal = producto.cantidad * producto.precio;
    const carrito = JSON.parse(localStorage.getItem("carrito_cuenta")) || [];
    const index = carrito.findIndex(
      (item) => item.id_producto === producto.id_producto
    );

    if (index > -1) {
      carrito[index].cantidad += producto.cantidad;
      carrito[index].subtotal = carrito[index].cantidad * carrito[index].precio;
    } else {
      carrito.push(producto);
    }

    const totales = {
      subtotal: carrito.reduce((sum, item) => sum + item.subtotal, 0),
      total_comision: carrito.reduce(
        (sum, item) => sum + item.comision * item.cantidad,
        0
      ),
    };

    localStorage.setItem("carrito_cuenta", JSON.stringify(carrito));
    localStorage.setItem("totales", JSON.stringify(totales));
    actualizarTablaCarrito(carrito);

    const servicioTotal = JSON.parse(
      localStorage.getItem("datos_servicio")
    ).total;
    const carritoTotal = totales.subtotal || 0;
    document.getElementById("total").innerText = servicioTotal + carritoTotal;
    document.getElementById(`cantidad-${id_producto}`).value = "";

    return toast("Producto agregado al carrito", "success");
  } catch (error) {
    console.error("Error al cargar producto al carrito:", error);
  }
}
function actualizarTablaCarrito(carrito) {
  const tbody = document.querySelector("#tbCarritoCuenta tbody");
  tbody.innerHTML = ``;

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
  let carrito = JSON.parse(localStorage.getItem("carrito_cuenta")) || [];

  carrito = carrito.filter((item) => item.id_producto !== id_producto);
  localStorage.setItem("carrito_cuenta", JSON.stringify(carrito));

  actualizarTablaCarrito(carrito);
}
async function createCuentaServicio(e) {
  e.preventDefault();
  const cliente_id = document.getElementById("cliente_id").value || 1;
  const usuario_id = document.getElementById("usuario_id").value;
  const metodo_pago = document.getElementById("metodo_pago").value;
  const pieza_id = document.getElementById("pieza_id").value;
  const tiempo = document.getElementById("tiempo").value;
  const precio_servicio = document.getElementById("precio").value;
  const precio_pieza = document
    .getElementById("pieza_id")
    .options[document.getElementById("pieza_id").selectedIndex].getAttribute(
      "data-precio"
    );
  const iva = document.getElementById("iva").value;

  const datos_servicio =
    JSON.parse(localStorage.getItem("datos_servicio")) || [];

  datos_servicio.cliente_id = Number(cliente_id);
  datos_servicio.usuario_id = Number(usuario_id);
  datos_servicio.metodo_pago = metodo_pago;
  datos_servicio.pieza_id = Number(pieza_id);
  datos_servicio.tiempo = Number(tiempo);
  datos_servicio.precio_servicio = Number(precio_servicio) || 0;
  datos_servicio.precio_pieza = Number(precio_pieza);
  datos_servicio.iva = Number(iva);
  datos_servicio.total =
    Number(precio_servicio) + Number(iva) + Number(precio_pieza);
  localStorage.setItem("datos_servicio", JSON.stringify(datos_servicio));
  const productos = JSON.parse(localStorage.getItem("carrito_cuenta")) || [];
  const totales = JSON.parse(localStorage.getItem("totales")) || {};
  document.getElementById("total").innerText =
    datos_servicio.total + totales.subtotal;
  const datos = {
    cliente_id: datos_servicio.cliente_id,
    usuario_id: datos_servicio.usuario_id,
    metodo_pago: datos_servicio.metodo_pago,
    codigo: datos_servicio.codigo,
    pieza_id: datos_servicio.pieza_id,
    precio_servicio: datos_servicio.precio_servicio,
    iva: datos_servicio.iva,
    tiempo: datos_servicio.tiempo,
    precio_pieza: datos_servicio.precio_pieza,
    productos: productos,
    total: totales.subtotal || 0,
    total_comision: totales.total_comision || 0,
    subtotal: totales.subtotal || 0,
  };
  const url = `${BASE_URL}createServicio`;
  try {
    const response = await axios.post(url, datos, config);
    const data = response.data;
    if (data.estado === "ok" && data.codigo === 201) {
      localStorage.removeItem("datos_servicio");
      localStorage.removeItem("carrito_cuenta");
      localStorage.removeItem("totales");
      toast("Cuenta y servicio creado correctamente", "success");
      atras();
      reset();
      getServicios();
    }
  } catch (error) {
    console.log(error);
  }
}
function reset() {
  document.getElementById("cliente_id").value = "0";
  document.getElementById("usuario_id").value = "0";
  document.getElementById("tiempo").value = "";
  document.getElementById("pieza_id").value = "0";
  document.getElementById("precio").value = "0";
  document.getElementById("iva").value = "";
  document.getElementById("metodo_pago").value = "0";
  document.getElementById("total").innerText = "0";
  document.getElementById("btn-registrar").hidden = false;
  document.getElementById("btn-generar").hidden = true;
}

async function cobrarCuenta(e, id_cuenta) {
  e.preventDefault();
  const metodo_pago = document.getElementById("metodo_pago_c").value;
  const url = `${BASE_URL}updateCuenta`;

  if (id_cuenta) {
    const datos = {
      id_cuenta: id_cuenta,
      metodo_pago: metodo_pago,
    };
    try {
      const resp = await axios.post(url, datos, config);
      const data = resp.data;
      if (data.estado === "ok" && data.codigo === 201) {
        toast("Cuenta cobrada correctamente", "success");
      }
    } catch (error) {
      console.log(error);
    }
  }
}

async function cortarServicio(codigo) {
  const url = `${BASE_URL}getServicio/${codigo}`;
  const resp = await axios.get(url, config);
  const data = resp.data;
  const servicio = data.data;
  if (data.estado === "ok" && data.codigo === 200) {
    const uri = `${BASE_URL}getCuenta/${servicio.codigo}`;
    const respCuenta = await axios.get(uri, config);
    const dataCuenta = respCuenta.data;
    const cuenta = dataCuenta.data;
    if (dataCuenta.estado === "ok" && dataCuenta.codigo === 200) {
      await getServicio(cuenta.codigo);
      await getDetalleCuenta(cuenta.id_cuenta);
      document.getElementById(
        "total_c"
      ).innerHTML = `<b>Total : $ ${cuenta.total}</b>`;
      $("#ModalCuenta").modal("show");

      document.getElementById("btn_finalizar_servicio").onclick = async () => {
        localStorage.removeItem(`tiempoRestante_${servicio.id_servicio}`);
        await updatePieza(servicio.pieza_id);
      };

      document.getElementById("btn_cobrar_cuenta").onclick = async (event) => {
        localStorage.removeItem(`tiempoRestante_${servicio.id_servicio}`);
        const metodo_pago = document.getElementById("metodo_pago_c").value;
        if (metodo_pago === "0") {
          return toast("Seleccione un metodo de pago", "info");
        }
        await cobrarCuenta(event, cuenta.id_cuenta);
        await updatePieza(servicio.pieza_id);
      };
    } else {
      const result = await Swal.fire({
        title: "Las Muñecas de Ramón",
        text: `¿Desea finalizar el tiempo de la habitacion ${data.data.habitacion}?`,
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Si, Finalizar",
        cancelButtonText: "No, Cancelar",
        reverseButtons: true,

        customClass: {
          confirmButton: "btn btn-danger btn-sm rounded-pill",
          cancelButton: "btn btn-secondary btn-sm rounded-pill",
        },
        buttonsStyling: false,
        confirmButtonColor: "#dc3545",
      });
      if (result.isConfirmed) {
        localStorage.removeItem(`tiempoRestante_${servicio.id_servicio}`);
        await updatePieza(servicio.pieza_id);
      }
    }
  }
}

function updateTiempoDisplay(idServicio, segundosRestantes) {
  const minutos = Math.floor(segundosRestantes / 60);
  const segundos = segundosRestantes % 60;
  const tiempoElement = document.getElementById(
    `tiempo-servicio-${idServicio}`
  );

  if (tiempoElement) {
    tiempoElement.innerText = `${minutos}:${
      segundos < 10 ? "0" : ""
    }${segundos}`;
    if (segundosRestantes <= 0) {
      localStorage.removeItem(`tiempoRestante_${idServicio}`);
    }
  } else {
    localStorage.removeItem(`tiempoRestante_${idServicio}`);
  }
}

function formatTiempo(minutos, segundos) {
  return `${minutos}:${segundos < 10 ? "0" : ""}${segundos}`;
}

function renderServicioCard(servicio, tiempoHTML) {
  return `
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-2">
      <a onclick="cortarServicio('${servicio.codigo}')">
        <div class="card-wrapper">
          <div class="card overflow-hidden mb-4 shadow-sm parent-hover bg-light-primary btn btn-outline btn-outline-dashed btn-outline-default">
            <div class="card-body d-flex justify-content-between flex-column px-0 pb-0">
              <div class="row mb-2 px-2 align-items-start text-start">
                <div class="col-6">
                  <small><b>Codigo: ${servicio.codigo}</b></small>
                </div>
                <div class="col-6">
                  <small><b>Tiempo: <span id="tiempo-servicio-${servicio.id_servicio}">${tiempoHTML}</span></b></small>
                </div>
              </div>
              <div class="mb-4 px-2">
                <div class="d-flex align-items-center mb-2">
                  <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                    <i class="fa-solid fa-bed-pulse"></i>
                  </span>
                  <small class="text-bold fs-6 fw-normal ms-1"><b>Habitacion: ${servicio.habitacion}</b></small>
                </div>
              </div>
              <div class="row mb-2 px-2 align-items-start text-start">
                <div class="col-6">
                  <small><b>Servicio: $ ${servicio.precio_servicio}</b></small><br>
                  <small><b>Pieza: $ ${servicio.precio_pieza}</b></small><br>
                  <small><b>Iva: $ ${servicio.iva}</b></small><br>
                </div>
                <div class="col-6">
                  <small><b>Sub Total: $ ${servicio.sub_total}</b></small><br>
                  <small><b>Total: $ ${servicio.total}</b></small><br>
                  <small><b>Metodo de Pago: ${servicio.metodo_pago}</b></small><br>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  `;
}

async function cobrarCuenta(e, id_cuenta) {
  e.preventDefault();
  const metodo_pago = document.getElementById("metodo_pago_c").value;
  const url = `${BASE_URL}updateCuenta`;

  if (id_cuenta) {
    const datos = {
      id_cuenta: id_cuenta,
      metodo_pago: metodo_pago,
    };
    try {
      const resp = await axios.post(url, datos, config);
      const data = resp.data;
      if (data.estado === "ok" && data.codigo === 201) {
        toast("Cuenta cobrada correctamente", "success");
      }
    } catch (error) {
      console.log(error);
    }
  }
}

async function getServicio(codigo) {
  const url = `${BASE_URL}getServicio/${codigo}`;
  const resp = await axios.get(url, config);
  const data = resp.data;

  if (data.estado === "ok" && data.codigo === 200) {
    const servicio = data.data;
    const fechaMoment = moment(servicio.fecha_crea);
    document.getElementById(
      "fecha_s"
    ).innerHTML = `<b>Fecha : ${fechaMoment.format("DD-MM-YYYY")}</b>`;
    document.getElementById(
      "hora_s"
    ).innerHTML = `<b>Hora : ${fechaMoment.format("HH:mm:ss")}</b>`;
    document.getElementById(
      "codigo_s"
    ).innerHTML = `<b>Codigo : ${servicio.codigo}</b>`;
    document.getElementById(
      "usuario_s"
    ).innerHTML = `<b>Acompañante : ${servicio.nombre_u} ${servicio.apellido_u}</b>`;
    document.getElementById(
      "cliente_s"
    ).innerHTML = `<b>Cliente : ${servicio.nombre_c} ${servicio.apellido_c}</b>`;
    document.getElementById(
      "pieza_s"
    ).innerHTML = `<b>Pieza : ${servicio.habitacion}</b>`;
    document.getElementById(
      "precio_pieza_s"
    ).innerHTML = `<b>Precio Pieza : $ ${servicio.precio_pieza}</b>`;
    document.getElementById(
      "precio_servicio_s"
    ).innerHTML = `<b>Precio Servicio : $ ${servicio.precio_servicio}</b>`;
    document.getElementById(
      "tiempo_s"
    ).innerHTML = `<b>Tiempo de servicio : ${servicio.tiempo} minutos</b>`;
    document.getElementById(
      "iva_s"
    ).innerHTML = `<b>Iva : $ ${servicio.iva}</b>`;
    document.getElementById(
      "total_s"
    ).innerHTML = `<b>Total : $ ${servicio.total}</b>`;
    document.getElementById(
      "metodo_s"
    ).innerHTML = `<b>Metodo de pago : <span class="badge badge-sm badge-success">${servicio.metodo_pago}</span></b>`;
  }
}
async function getDetalleCuenta(idCuenta) {
  const url = `${BASE_URL}getDetalleCuenta/${idCuenta}`;
  const resp = await axios.get(url, config);
  const data = resp.data;

  if (data.estado === "ok" && data.codigo === 200) {
    const detalleCuentaElement = document.getElementById("detalle_cuenta");
    detalleCuentaElement.innerHTML = "";
    data.data.map((detalle) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${detalle.nombre_producto}</td>
        <td>${detalle.cantidad}</td>
        <td>${detalle.precio}</td>
        <td>${detalle.subtotal}</td>
      `;
      detalleCuentaElement.appendChild(tr);
    });
  }
}