let tbServicios;
let tbDevoluciones;
let tbDevolucionesVenta;
document.addEventListener("DOMContentLoaded",  () => {
  document.getElementById("cantidad").addEventListener("input", () => {
    const cantidad = Number($("#cantidad").val());
    const precio = document
      .getElementById("producto_id")
      .options[
        document.getElementById("producto_id").selectedIndex
      ].getAttribute("data-precio");
    document.getElementById("subtotal").innerHTML = `<b>Pagó : ${
      cantidad * precio
    }</b>`;
  });
});
async function getServicios() {
  const url = `${BASE_URL}getAllServicios`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" && data.codigo !== 200) {
      return toast("No se encontraron servicios Activos", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      tbServicios = $("#tbServicios").DataTable({
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
              `<span class="badge badge-sm badge-primary">${
                meta.row + 1
              }</span>`,
          },
          {
            data: null,
            render: (data, type, row) => {
              if (data?.chica) {
                if (data.chica.includes(",")) {
                  return data.chica
                    .split(",")
                    .map((item) => `<div>${item.trim()}</div>`)
                    .join("");
                }
                return data.chica;
              }
              return "";
            },
          },
          { data: "pieza" },
          {
            data: null,
            render: (data, type, row) => `${row.nombre} ${row.apellido}`,
          },
          { data: "sub_total" },
          { data: "precio_pieza" },
          { data: "iva" },
          { data: "total" },
          { data: "metodo_pago" },
          {
            data: null,
            render: (data, type, row) => {
              if (data?.fecha_crea) {
                const formattedDate = moment(data.fecha_crea).format(
                  "DD/MM/YYYY"
                );
                const formattedTime = moment(data.fecha_crea).format("HH:mm");
                return `<div>${formattedDate}</div><div>${formattedTime}</div>`;
              }
              return "";
            },
          },
          {
            data: null,
            render: (data, type, row) => `
                  <button title="Realizar Devolución" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.servicio_id}" 
                  onclick="devolverServicio(${row.servicio_id}, ${row.pieza_id}, ${row.cliente_id}, '${row.id_usuario}',${row.sub_total})">
                      <i class="fas fa-trash"></i>
                  </button>`,
          },
        ],
      });
    }
  } catch (error) {
    console.log(error);
  }
}

async function devolverServicio(
  servicio_id,
  pieza_id,
  cliente_id,
  usuario_id,
  sub_total
) {
  const usuarioArray = usuario_id.split(",").map((id) => id.trim());

  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "Ingrese el monto de devolución",
    input: "number",
    inputAttributes: {
      min: 0,
    },
    icon: "info",
    showCancelButton: true,
    confirmButtonText: "Procesar",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
      cancelButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
      input: "form-control form-control-sm form-control-solid rounded-pill",
      popup: "swal2-dark",
      title: "swal2-title",
      htmlContainer: "swal2-html-container",
    },
    buttonsStyling: false,
    confirmButtonColor: "#dc3545",
    background: "var(--bs-body-bg)",
    color: "var(--bs-body-color)",
    preConfirm: (value) => {
      if (!value || Number.isNaN(value) || Number.parseInt(value) <= 0) {
        toast("Por favor ingrese un monto válido", "info");
        Swal.getInput().focus();
        return false;
      }
      if (Number.parseInt(value) > Number.parseInt(sub_total)) {
        toast(
          "El monto de devolución no puede ser mayor al total del servicio",
          "info"
        );
        Swal.getInput().focus();
        return false;
      }

      return value;
    },
  });

  if (result.isConfirmed) {
    const total = result.value;
    const datos = {
      servicio_id,
      pieza_id,
      cliente_id,
      usuario_id: usuarioArray,
      total,
    };
    const url = `${BASE_URL}createDevolucion`;
    const resp = await axios.post(url, datos, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 201) {
      localStorage.removeItem(`tiempoRestante_${datos.servicio_id}`);
      await updatePieza(datos.pieza_id);
      toast("Devolución procesada correctamente", "success");
      atras();
    }
  }
}

function listarServicios(e) {
  e.preventDefault();
  getServicios();
  document.getElementById("servicio_table").hidden = false;
  document.getElementById("devolucion_table").hidden = true;
  document.getElementById("btn_atras").hidden = false;
  document.getElementById("btn_nuevo").hidden = true;
  document.getElementById("btn_atras_dev").hidden = true;
  document.getElementById("title_servicios").innerHTML = "Listado de servicios activos";
}
function listarDevolucionesServicios() {
  document.getElementById("servicio_table").hidden = true;
  document.getElementById("devolucion_table").hidden = false;
  document.getElementById("btn_atras").hidden = true;
  document.getElementById("btn_nuevo").hidden = false;
  document.getElementById("btn_atras_dev").hidden = false;
  document.getElementById("title_servicios").innerHTML = "Listado de devoluciones de servicios";
  getDevoluciones();
}

async function getDevoluciones() {
  const url = `${BASE_URL}getDevoluciones`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      tbDevoluciones = $("#tbDevoluciones").DataTable({
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
              `<span class="badge badge-sm badge-primary">${
                meta.row + 1
              }</span>`,
          },
          {
            data: null,
            render: (data, type, row) => `${row.nombre} ${row.apellido} `,
          },
          { data: "pieza" },

          { data: "total" },
          {
            data: null,
            render: (data, type, row) => {
              if (data?.fecha_crea) {
                const formattedDate = moment(data.fecha_crea).format(
                  "DD/MM/YYYY"
                );
                const formattedTime = moment(data.fecha_crea).format("HH:mm");
                return `<div>${formattedDate}</div><div>${formattedTime}</div>`;
              }
              return "";
            },
          },
          {
            data: null,
            render: (data, type, row) => `
                  <button title="Ver detalles" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_devolucion}" 
                  onclick="verDetalles(${row.id_devolucion})">
                      <i class="fas fa-eye"></i>
                  </button>`,
          },
        ],
      });
    } else {
      return toast("No se encontraron devoluciones registrados", "info");
    }
  } catch (error) {
    console.log(error);
  }
}

async function verDetalles(id_devolucion) {
  const url = `${BASE_URL}getDevolucion/${id_devolucion}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      const datos = data.data;
      const fecha = moment(datos[0].fecha_crea).format("DD/MM/YYYY");
      const hora = moment(datos[0].fecha_crea).format("HH:mm");
      document.getElementById(
        "cliente"
      ).innerHTML = `<b>Cliente : ${datos[0].nombre} ${datos[0].apellido}</b>`;
      document.getElementById("fecha").innerHTML = `<b>Fecha : ${fecha}</b>`;
      document.getElementById("hora").innerHTML = `<b>Hora : ${hora}</b>`;
      document.getElementById(
        "total"
      ).innerHTML = `<b>Total : ${datos[0].total}</b>`;
      document.getElementById(
        "pieza"
      ).innerHTML = `<b>Habitación : ${datos[0].pieza}</b>`;
      const detalle = data.data;
      let html = "";
      for (const item of detalle) {
        html += `
          <tr>
            <td>${item.nick}</td>
            <td>${item.monto}</td>
          </tr>
        `;
      }
      document.getElementById("detalle_devolucion").innerHTML = html;
      $("#ModalDetalleDevolucion").modal("show");
    } else {
      return toast("No se encontraron detalles registrados", "info");
    }
  } catch (error) {
    console.log(error);
  }
}

function devolucionServicio(e) {
  e.preventDefault();
  document.getElementById("devoluciones").hidden = true;
  document.getElementById("devolucion_servicio").hidden = false;
  document.getElementById("btn_atras_dev").hidden = false;
  document.getElementById("btn_nuevo").hidden = false;
  document.getElementById("title_servicios").innerHTML = "Listado de devoluciones de servicios";
  getDevoluciones();
}

function atras() {
  document.getElementById("devoluciones").hidden = false;
  document.getElementById("devolucion_servicio").hidden = true;
  document.getElementById("devolucion_venta").hidden = true;
}

function devolucionVenta(e) {
  e.preventDefault();
  document.getElementById("devoluciones").hidden = true;
  document.getElementById("devolucion_servicio").hidden = true;
  document.getElementById("devolucion_venta").hidden = false;
  getDevolucionesVentas();
}

function MDevolucionVenta(e) {
  e.preventDefault();

  document.getElementById("monto").value = "";
  document.getElementById("cantidad").value = "";
  document.getElementById("subtotal").innerHTML = "";
  getChicas();
  getClientes();
  getProductos();
  $("#cliente_id").select2({
    dropdownParent: $("#ModalDevolucionVenta .modal-body"),
  });
  $("#chica_id").select2({
    dropdownParent: $("#ModalDevolucionVenta .modal-body"),
  });
  $("#producto_id").select2({
    dropdownParent: $("#ModalDevolucionVenta .modal-body"),
  });
  $("#ModalDevolucionVenta").modal("show");
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
      const select = document.getElementById("chica_id");
      select.innerHTML = "";
      const defaultOption = document.createElement("option");
      defaultOption.value = "0";
      defaultOption.text = "Seleccione una acompañante";
      defaultOption.selected = true;
      select.appendChild(defaultOption);
      for (let i = 0; i < datos.data.length; i++) {
        const chica = datos.data[i];
        const option = document.createElement("option");
        option.value = chica.usuario_id;
        option.text = `${chica.nombre} ${chica.apellido}`;
        select.appendChild(option);
      }
    }
  } catch (error) {
    console.log(error);
  }
}

async function getProductos() {
  const url = `${BASE_URL}getProductos`;
  try {
    const response = await axios.get(url, config);
    const datos = response.data;
    if (datos.estado === "ok" && datos.codigo === 200) {
      const select = document.getElementById("producto_id");
      select.innerHTML = "";
      const defaultOption = document.createElement("option");
      defaultOption.value = "0";
      defaultOption.text = "Seleccione un producto";
      defaultOption.selected = true;
      select.appendChild(defaultOption);
      for (let i = 0; i < datos.data.length; i++) {
        const producto = datos.data[i];
        const option = document.createElement("option");
        option.value = producto.id_producto;
        option.text = `${producto.nombre}`;
        option.setAttribute("data-precio", producto.precio);
        option.setAttribute("data-comision", producto.comision);
        select.appendChild(option);
      }
    }
  } catch (error) {
    console.log(error);
  }
}

async function createDevolucionVenta(e) {
  e.preventDefault();
  let cliente_id = Number($("#cliente_id").val()) || 1;
  const chica_id = Number($("#chica_id").val()) || 0;
  const producto_id = Number($("#producto_id").val());
  const cantidad = Number($("#cantidad").val()) || 1;
  const monto = Number($("#monto").val());
  const comisionInput = document
    .getElementById("producto_id")
    .options[document.getElementById("producto_id").selectedIndex].getAttribute(
      "data-comision"
    );
  const comision = Number(comisionInput) * cantidad;

  if (cliente_id === 0 || cliente_id == null || cliente_id === "") {
    cliente_id = 1;
  }
  if (producto_id === 0 || producto_id == null || producto_id === "") {
    return toast("Seleccione un producto", "info");
  }
  if (monto === 0 || monto === null || monto === "") {
    return toast("Ingrese un monto", "info");
  }
  const datos = {
    cliente_id: cliente_id,
    chica_id: chica_id,
    producto_id: producto_id,
    cantidad: cantidad,
    monto: monto,
    comision: Number(comision),
  };
  const url = `${BASE_URL}createDevolucionVenta`;
  const resp = await axios.post(url, datos, config);
  const data = resp.data;
  console.log(data);
  if (data.estado === "ok" && data.codigo === 201) {
    toast("Devolucion creada correctamente", "success");
    getDevolucionesVentas();
    $("#ModalDevolucionVenta").modal("hide");
  }
}
async function getDevolucionesVentas() {
  const url = `${BASE_URL}getDevolucionesVentas`;
  try {
    const response = await axios.get(url, config);
    const data = response.data;

    if (data.estado !== "ok" && data.codigo !== 200) {
      return toast("No se encontraron devoluciones de ventas", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      tbDevolucionesVentas = $("#tbDevolucionesVentas").DataTable({
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
              `<span class="badge badge-sm badge-primary">${
                meta.row + 1
              }</span>`,
          },
          { data: "cliente" },
          { data: "nick" },
          { data: "producto" },
          { data: "cantidad" },
          { data: "monto" },
          {
            data: null,
            render: (data, type, row) => {
              const date = moment(row.fecha_crea);
              const formattedDate = date.format("DD/MM/YYYY");
              const formattedTime = date.format("HH:mm:ss");
              return `<div>${formattedDate}</div><div>${formattedTime}</div>`;
            },
          },
        ],
      });
    }
  } catch (error) {
    console.log(error);
  }
}
