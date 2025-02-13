let tbHoraExtra;
document.addEventListener("DOMContentLoaded", async () => {
  await getHorasExtras();
  $("#usuario_id").select2({
    placeholder: "Seleccionar Usuario",
    dropdownParent: $("#ModalHoraExtra .modal-body"),
  });
});

async function Mhora(e) {
  e.preventDefault();
  await getMesero();
  document.getElementById("id_hora_extra").value = "";
  document.getElementById("hora").value = "";
  document.getElementById("monto").value = "";
  async function actualizarFechaHora() {
    const fecha = new Date();
    const dia = String(fecha.getDate()).padStart(2, "0");
    const mes = String(fecha.getMonth() + 1).padStart(2, "0");
    const anio = fecha.getFullYear();
    const horas = String(fecha.getHours()).padStart(2, "0");
    const minutos = String(fecha.getMinutes()).padStart(2, "0");
    const segundos = String(fecha.getSeconds()).padStart(2, "0");
    const fechaFormateada = `${dia}/${mes}/${anio} ${horas}:${minutos}:${segundos}`;
    document.getElementById("fecha").value = fechaFormateada;
  }
  await actualizarFechaHora();
  setInterval(actualizarFechaHora, 1000);
  $("#ModalHoraExtra").modal("show");
}

async function getMesero() {
  const url = `${BASE_URL}getUsuarios`;
  try {
    const response = await axios.get(url, config);
    const datos = response.data;
    if (datos.estado === "ok" && datos.codigo === 200) {
      const select = document.getElementById("usuario_id");
      select.innerHTML = "";
      const usuariosFiltrados = datos.data.filter(
        (usuario) => usuario.rol === "Mesero" || usuario.rol === "Cajero"
      );
      for (const usuario of usuariosFiltrados) {
        const option = document.createElement("option");
        option.value = usuario.id_usuario;
        option.text = `${usuario.nombre} ${usuario.apellido}`;
        select.appendChild(option);
      }
    }
  } catch (error) {
    return toast(
      "Error al obtener los usuarios, por favor intente de nuevo",
      "error"
    );
  }
}

async function createHoraExtra(e) {
  e.preventDefault();
  const hora = document.getElementById("hora").value;
  const monto = document.getElementById("monto").value;
  if (hora === "") {
    return toast("Ingrese las horas extra", "info");
  }
  if (hora <= 0) {
    return toast("Las horas extra deben ser mayores a 0", "info");
  }
  if (monto === "") {
    return toast("Ingrese el monto", "info");
  }
  if (monto <= 0) {
    return toast("El monto debe ser mayor a 0", "info");
  }
  const datos = {
    usuario_id: document.getElementById("usuario_id").value,
    hora: hora,
    monto: monto,
  };
  const url = `${BASE_URL}createHoraExtra`;
  try {
    const resp = await axios.post(url, datos, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 201) {
      $("#ModalHoraExtra").modal("hide");
      await getHorasExtras();
      return toast("Hora Extra creada correctamente", "success");
    }
  } catch (error) {
    return toast(
      "Error al crear la hora extra, por favor intente de nuevo",
      "error"
    );
  }
}

async function getHorasExtras() {
  const url = `${BASE_URL}getHorasExtras`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" && data.codigo !== 200) {
      return toast("No se encontraron horas extras registradas.", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      tbHoraExtra = $("#tbHoraExtra").DataTable({
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
              ` <span class="badge badge-sm badge-primary" >${formatNumber(
                meta.row + 1
              )}</span>`,
          },
          {
            data: null,
            render: (data, type, row) => `${row.nombre} ${row.apellido}`,
          },
          { data: "total_horas" },
          {
            data: null,
            render: (data, type, row) =>
              `$ ${row.total_monto.toLocaleString("es-CL")}`,
          },
          {
            data: null,
            render: (data, type, row) =>
              `<button title="Ver detalles" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_usuario}" onclick="gethoraExtra(\'${row.id_usuario}\')"><i class="fas fa-eye"></i></button> `,
          },
        ],
      });
    }
  } catch (error) {
    return toast(
      "Error al obteber las horas extras, por favor intente de nuevo",
      "error"
    );
  }
}

async function gethoraExtra(id) {
  const url = `${BASE_URL}getHoraExtra/${id}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" && data.codigo !== 200) {
      return toast("No se encontraron detalles de las horas extras ", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById(
        "usuario_hora"
      ).innerHTML = `Usuario : ${data.data.registros[0].nombre} ${data.data.registros[0].apellido}`;
      document.getElementById(
        "total_horas_extras"
      ).innerHTML = `Total Horas : ${data.data.totales.total_horas}`;
      document.getElementById(
        "total_pagar_horas"
      ).innerHTML = `Total Monto : $ ${data.data.totales.total_monto.toLocaleString(
        "es-CL"
      )}`;

      let html = "";
      const detalle = data.data.registros.map((item) => {
        return `
            <tr>
              <td>${item.fecha}</td>
              <td>${item.hora}</td>
              <td>$ ${item.monto.toLocaleString("es-CL")}</td>
            </tr>
          `;
      });
      html += detalle.join("");
      document.getElementById("detalle_horas_extra").innerHTML = html;

      $("#ModalDetalleHoraExtra").modal("show");
    }
  } catch (error) {
    return toast(
      "Error al obteber el detalle de horas extras, por favor intente de nuevo",
      "error"
    );
  }
}
