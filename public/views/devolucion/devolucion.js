let tbServicios;
let tbDevoluciones;
let tbDevolucionesVenta;

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
  document.getElementById("title_servicios").innerHTML =
    "Listado de servicios activos";
}

function devolucionServicio(e) {
  e.preventDefault();
  document.getElementById("devoluciones").hidden = true;
  document.getElementById("devolucion_servicio").hidden = false;
  document.getElementById("servicio_table").hidden = true;
  document.getElementById("devolucion_table").hidden = false;
  document.getElementById("btn_atras").hidden = true;
  document.getElementById("btn_nuevo").hidden = false;
  document.getElementById("btn_atras_dev").hidden = false;
  document.getElementById("title_servicios").innerHTML =
    "Listado de devoluciones de servicios";
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
          { data: "codigo" },
          { data: "cliente" },
          { data: "total" },
          {
            data: null,
            render: (data, type, row) => {
              const date = moment(row.fecha_crea);
              const formattedDate = date.format("DD/MM/YYYY");
              const formattedTime = date.format("HH:mm:ss");
              return `<div>${formattedDate}</div><div>${formattedTime}</div>`;
            },
          },
          {
            data: null,
            render: (data, type, row) => {
              if (row.estado === 0) {
                return `<span class="badge badge-sm badge-info">Venta anulada</span>`;
              }
            },
          },
          {
            data: null,
            render: (data, type, row) => `
                  <button title="Ver detalles" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.venta_id}" 
                  onclick="verDetallesVenta(${row.venta_id})">
                      <i class="fas fa-eye"></i>
                  </button>`,
          },
        ],
      });
    }
  } catch (error) {
    console.log(error);
  }
}

async function verDetallesVenta(id_venta) {
  const url = `${BASE_URL}getVenta/${id_venta}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    detalleVenta = data.data;
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
            if (item.usuario === null) {
              return "Venta en barra";
            }
            return `${item.usuario}`;
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
