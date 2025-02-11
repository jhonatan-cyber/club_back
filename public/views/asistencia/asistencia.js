let tbAsistencia;
document.addEventListener("DOMContentLoaded", async () => {
  await getAsistencias();
});

async function getAsistencias() {
  const url = `${BASE_URL}getAsistencias`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (
      data.estado !== "ok" ||
      data.codigo !== 200 ||
      data.data["usuarios"].length <= 0
    ) {
      return toast("No se encontraron asistencias", "info");
    }

    if (data.estado === "ok" && data.codigo === 200) {
      const fechas = data.data.fechas_globales;
      const asistencias = data.data.usuarios;
      if (
        fechas.primera_asistencia_global == null ||
        fechas.ultima_asistencia_global == null
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
            data: null,
            render: (data, type, row) => {
              return `$ ${data.sueldo_total.toLocaleString("es-CL")}`;
            },
          },
          {
            data: null,
            render: (data, type, row) => {
              return `$ ${row.aporte_total.toLocaleString("es-CL")}`;
            },
          },
          {
            data: null,
            render: (data, type, row) => {
              return `$ ${data.total_final.toLocaleString("es-CL")}`;
            },
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
    return toast(
      "Error al obtener asistencia, por favor intente de nuevo",
      "error"
    );
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
    if (data.estado === "ok" && data.codigo === 200) {
      const asistencias = data.data.asistencias;
      const totales = data.data.totales;

      document.getElementById(
        "usuario"
      ).innerHTML = `Usuario : ${asistencias[0].nombre} ${asistencias[0].apellido}`;
      document.getElementById(
        "total_sueldo"
      ).innerHTML = `Sueldo Total : $ ${totales.total_sueldos.toLocaleString(
        "es-CL"
      )}`;
      document.getElementById(
        "total_aporte"
      ).innerHTML = `Aporte Total : $ ${totales.total_aportes.toLocaleString(
        "es-CL"
      )}`;

      document.getElementById(
        "total_pagar"
      ).innerHTML = `Total a Pagar : $ ${totales.gran_total.toLocaleString(
        "es-CL"
      )}`;

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
              <td>$ ${asistencia.sueldo.toLocaleString("es-CL")}</td>
              <td>$ ${asistencia.aporte.toLocaleString("es-CL")}</td>
              <td>$ ${sub_total.toLocaleString("es-CL")}</td>
            </tr>
          `;
        })
      );

      document.getElementById("detalle_asistencia").innerHTML =
        asistenciasHTML.join("");
      $("#ModalAsistencia").modal("show");
    }
  } catch (error) {
    return toast(
      "Error al obtener asistencia, por favor intente de nuevo",
      "error"
    );
  }
}
