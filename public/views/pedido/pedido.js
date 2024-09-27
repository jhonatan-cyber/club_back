let tbPedido;
document.addEventListener("DOMContentLoaded", function () {
  getClientes();
  getChicas();
  getPedidos();
  getProductosPrecio();
  const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
  actualizarTablaCarrito(carrito);
});

async function getPedidos() {
  const url = `${BASE_URL}getPedidos`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;

    if (data.codigo == 200 && data.estado == "ok") {
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
            render: (data, type, row) => `${row.nombre_u} ${row.apellido_u}`,
          },
          {
            data: null,
            render: (data, type, row) => `${row.nombre_c} ${row.apellido_c}`,
          },

          { data: "total" },
          {
            data: null,
            render: (data, type, row) =>
              `<button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_pedido}" onclick="aceptarPedido('${row.id_pedido}')">
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
function aceptarPedido(id) {
  localStorage.setItem("id_pedido", id);
  console.log(id);
  /*   window.location.href = `${BASE_URL}ventas`; */
}
function nuevoPedido(e) {
  e.preventDefault();
  document.getElementById("nuevo_pedido").hidden = false;
  document.getElementById("lista_pedido").hidden = true;
}
function atras(e) {
  e.preventDefault();
  document.getElementById("nuevo_pedido").hidden = true;
  document.getElementById("lista_pedido").hidden = false;
}

async function getProductosPrecio() {
  const url = `${BASE_URL}getProductosprecio`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      const precios = data.data;
      for (let i = 0; i < precios.length; i++) {
        const precio = precios[i];
        const precioElement = `
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
                        </div>`;

        document.getElementById("precio_bebidas").innerHTML += precioElement;
      }
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
      data.data.forEach((item) => {
        carElement.innerHTML += `
                  <input type="hidden" class="form-control" value="${item.id_producto}">
                  <div class="input-group input-group-solid mb-3">
                      <small style="font-size: 1rem; width: auto; min-width: 120px;">${item.categoria} - ${item.nombre}</small>
                      <input id="cantidad-${item.id_producto}" type="number" class="form-control form-control-sm form-control-solid" placeholder="Ingrese una cantidad" style="width: 100px;" min="1" />
                      <button onclick="cargarCarrito(${item.id_producto}, '${item.nombre}', ${item.precio}, document.getElementById('cantidad-${item.id_producto}').value)" class="btn btn-light-dark btn-block btn-sm hover-elevate-up" type="button">
                          <i class="fas fa-plus"></i> Agregar
                      </button>
                  </div>`;
      });

      $("#ModalBebida").modal("show");
    }
  } catch (error) {
    console.error("Error en la petición:", error);
  }
}

function actualizarTablaCarrito(carrito) {
  const tbody = document.querySelector("#tbCarritoPedido tbody");
  tbody.innerHTML = "";

  carrito.forEach((item) => {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>${item.nombre}</td>
            <td>${item.cantidad}</td>
            <td>${(item.precio || 0).toFixed(2)}</td>
            <td>${(item.subtotal || 0).toFixed(2)}</td>
            <td><button onclick="eliminarProducto(${
              item.id_producto
            })" class="btn btn-danger btn-sm">Eliminar</button></td>
        `;
    tbody.appendChild(row);
  });

  const total = carrito.reduce((acc, item) => {
    return acc + (item.subtotal || 0);
  }, 0);

  document.getElementById("total").innerText = total.toFixed(2);
}

function eliminarProducto(id_producto) {
  let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

  carrito = carrito.filter((item) => item.id_producto !== id_producto);
  localStorage.setItem("carrito", JSON.stringify(carrito));

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
        option.text = cliente.nombre + " " + cliente.apellido;
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
      const defaultOption = document.createElement("option");
      defaultOption.value = "0";
      defaultOption.text = "Seleccione una compañera";
      defaultOption.selected = true;
      select.appendChild(defaultOption);
      for (let i = 0; i < datos.data.length; i++) {
        const chica = datos.data[i];
        const option = document.createElement("option");
        option.value = chica.id_usuario;
        option.text = chica.nombre + " " + chica.apellido;
        select.appendChild(option);
      }
    }
  } catch (error) {
    console.log(error);
  }
}
function cargarCarrito(id_producto, nombre, precio, cantidad) {
  cantidad = parseInt(cantidad);
  if (isNaN(cantidad) || cantidad <= 0) {
    document.getElementById(`cantidad-${id_producto}`).focus();
    return toast("La cantidad debe ser mayor a cero", "info");
  }
  precio = parseFloat(precio) || 0;
  const subtotal = cantidad * precio;
  let comision;
  if (precio === 20000) {
    comision = 8000;
  } else if (precio === 30000) {
    comision = 10000;
  }
  const producto = {
    id_producto,
    nombre,
    precio,
    cantidad,
    subtotal,
    comision,
  };

  let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

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
    return acc + (item.comision || 0);
  }, 0);

  localStorage.setItem("carrito", JSON.stringify(carrito));
  localStorage.setItem("total_carrito", total.toFixed(2));
  localStorage.setItem("subtotal", subtotal_.toFixed(2));
  localStorage.setItem("total_comision", total_comision.toFixed(2));
  actualizarTablaCarrito(carrito);
  document.getElementById("total").innerText = total.toFixed(2);
}
async function createPedido(e) {
  e.preventDefault();
  let cliente_id = document.getElementById("cliente_id").value;
  const usuario_id = document.getElementById("usuario_id").value;
  if (usuario_id == 0) {
    return toast("Seleccione una compañera", "info");
  }
  if (cliente_id == 0) {
    cliente_id = 1;
  }
  const productos = JSON.parse(localStorage.getItem("carrito")) || [];
  const datos = {
    cliente_id,
    usuario_id,
    productos: JSON.stringify(productos),
    total: localStorage.getItem("total_carrito") || 0,
    total_comision: localStorage.getItem("total_comision") || 0,
    subtotal: localStorage.getItem("subtotal") || 0,
  };
  const url = `${BASE_URL}createPedido`;
  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;
    console.log(data);
    if (data.codigo == 201 && data.estado == "ok") {
      toast("Pedido creado exitosamente", "success");
      localStorage.removeItem("carrito");
      localStorage.removeItem("total_carrito");
      localStorage.removeItem("subtotal");
      localStorage.removeItem("total_comision");
      let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
      actualizarTablaCarrito(carrito);
      getPedidosTotal()
      getPedidos()
      atras(e);
    }
  } catch (error) {
    console.error(error);
  }
}
