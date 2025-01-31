let tbAsistencia;
document.addEventListener("DOMContentLoaded", () => {
  getAsistencias();
  $("#usuario_id").select2({
    placeholder: "Seleccionar Usuario",
    dropdownParent: $("#ModalHoraExtra .modal-body"),
  });
});

async function getAsistencias() {
  const url = `${BASE_URL}getAsistencias`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" || data.codigo !== 200) {
      return toast("No se encontraron asistencias", "info");
    }
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      const fechas = data.data.fechas_globales;
      const asistencias = data.data.usuarios;
      if (
        fechas.primera_asistencia_global === null ||
        fechas.ultima_asistencia_global === null
      ) {
        document.getElementById("txt_fecha").innerHTML =
          "<b>No se encontraron asistencias</b>";
      }
      if (
        fechas.primera_asistencia_global !== null ||
        fechas.ultima_asistencia_global !== null
      ) {
        document.getElementById(
          "txt_fecha"
        ).innerHTML = `<b>Desde : ${fechas.primera_asistencia_global} Hasta : ${fechas.ultima_asistencia_global}</b>`;
      }

      tbAsistencia = $("#tbAsistencia").DataTable({
        data: asistencias,
        language: LENGUAJE,
        destroy: true,
        responsive: true,
        info: true,
        lengthMenu: [DISPLAY_LENGTH, 10, 25, 50],
        autoWidth: true,
        paging: true,
        searching: true,
        columns: [
          { data: "nombre_completo" },
          {
            data: "total_asistencias",
            render: (data, type, row) => {
              return data === 1
                ? `<span class="badge badge-sm badge-primary">${data} Día Asistido</span>`
                : `<span class="badge badge-sm badge-primary">${data} Días Asistidos</span>`;
            },
          },
          {
            data: "sueldo_total",
          },
          {
            data: "aporte_total",
          },
          {
            data: "total_final",
          },
          {
            data: null,
            render: (data, type, row) => {
              return `
                <button class="btn btn-outline-dark btn-sm hover-scale" onclick="getAsistencia(${row.usuario_id})">
                  <i class="fas fa-eye"></i>
                </button>`;
            },
          },
        ],
      });
    }
  } catch (error) {
    console.error("Error al obtener asistencias:", error);
  }
}

async function getAsistencia(id) {
  const url = `${BASE_URL}getAsistencia/${id}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" || data.codigo !== 200) {
      return toast("No se encontraron asistencias", "info");
    }
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      const asistencias = data.data.asistencias;
      const totales = data.data.totales;

      document.getElementById(
        "usuario"
      ).innerHTML = `Usuario : ${asistencias[0].nombre} ${asistencias[0].apellido}`;
      document.getElementById(
        "total_sueldo"
      ).innerHTML = `Sueldo Total : ${totales.total_sueldos}`;
      document.getElementById(
        "total_aporte"
      ).innerHTML = `Aporte Total : ${totales.total_aportes}`;

      document.getElementById(
        "total_pagar"
      ).innerHTML = `Total a Pagar : ${totales.gran_total}`;

      const asistenciasHTML = await Promise.all(
        asistencias.map(async (asistencia) => {
          const fecha = moment(asistencia.fercha_asistencia).format(
            "DD/MM/YYYY"
          );

          const sub_total = asistencia.sueldo - asistencia.aporte;
          return `
            <tr>
              <td>${fecha}</td>
              <td>${asistencia.hora_asistencia}</td>
              <td>${asistencia.sueldo}</td>
              <td>${asistencia.aporte}</td>

              <td>${sub_total}</td>
            </tr>
          `;
        })
      );

      document.getElementById("detalle_asistencia").innerHTML =
        asistenciasHTML.join("");
      $("#ModalAsistencia").modal("show");
    }
  } catch (error) {
    console.error("Error al obtener asistencias:", error);
  }
}
