const user = JSON.parse(localStorage.getItem("usuario"));
document.addEventListener("DOMContentLoaded", async () => {
  vistas(user);
  if (user.rol === "Mesero") {
    await getPropinas();
    const elementosMostrar = ["cServicios", "cComisiones"];
    for (const id of elementosMostrar) {
      const elemento = document.getElementById(id);
      if (elemento) {
        elemento.hidden = true;
      }
    }
  }
  if (user.rol === "Chica") {
    const elementosMostrar = ["cPropinas", "cPedidos"];
    for (const id of elementosMostrar) {
      const elemento = document.getElementById(id);
      if (elemento) {
        elemento.hidden = true;
      }
    }
  }
});

function vistas(user) {
  if (user.rol === "Chica" || user.rol === "Mesero") {
    document.getElementById("cardAdmin").hidden = true;
    document.getElementById("cardEmpleado").hidden = false;
    document.getElementById(
      "nombreChica"
    ).innerHTML = `${user.nombre} ${user.apellido}`;
    if (user.rol === "Chica") {
      document.getElementById("cHoraExtra").hidden = true;
    }
  }

  if (user.rol === "Administrador" || user.rol === "Cajero") {
    document.getElementById("cardAdmin").hidden = false;
    document.getElementById("cardEmpleado").hidden = true;
  }
}

document
  .getElementById("btnAsistencias")
  .addEventListener("click", getAsistencia);

async function getAsistencia() {
  const url = `${BASE_URL}getAsistencia/${user.id_usuario}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" || data.codigo !== 200) {
      return toast("No se encontraron asistencias", "info");
    }
    if (
      Array.isArray(data.data.asistencia) &&
      data.data.asistencia.length === 0
    ) {
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
      ).innerHTML = `Sueldos Total : $ ${totales.total_sueldos.toLocaleString(
        "es-CL"
      )}`;
      document.getElementById(
        "total_aporte"
      ).innerHTML = `Aportes Total : $ ${totales.total_aportes.toLocaleString(
        "es-CL"
      )}`;

      document.getElementById(
        "total_anticipo"
      ).innerHTML = `Anticipos Total : $ ${totales.total_anticipos.toLocaleString(
        "es-CL"
      )}`;

      document.getElementById(
        "total_pagar"
      ).innerHTML = `Total a Cobrar : $ ${totales.gran_total.toLocaleString(
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
    console.error("Error al obtener asistencias:", error);
  }
}
document.getElementById("btnAnticipos").addEventListener("click", getAnticipos);

async function getAnticipos() {
  const url = `${BASE_URL}getAnticipoUsuario/${user.id_usuario}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);

    if (data.estado !== "ok" || data.codigo !== 200) {
      return toast("No se encontraron anticipos", "info");
    }

    if (data.estado === "ok" && data.codigo === 200) {
      const { total, anticipos } = data.data;

      document.getElementById(
        "anticipo_total"
      ).innerHTML = `Monto Total : $ ${total.toLocaleString("es-CL")}`;
      document.getElementById(
        "usuario_anticipo"
      ).innerHTML = `Usuario: ${user.nombre} ${user.apellido}`;

      if (!Array.isArray(anticipos) || anticipos.length === 0) {
        document.getElementById("detalle_anticipo").innerHTML =
          "<tr><td colspan='4'>No hay anticipos disponibles</td></tr>";
      } else {
        const anticipoHTML = anticipos
          .map((anticipo) => {
            const fecha = moment(anticipo.fecha_crea).format("DD/MM/YYYY");
            const hora = moment(anticipo.fecha_crea).format("HH:mm");

            const estadoBadge =
              anticipo.estado === 0
                ? '<span class="badge badge-sm badge-success">Pagado</span>'
                : '<span class="badge badge-sm badge-info">Pendiente</span>';

            return `
            <tr>
              <td>${fecha}</td>
              <td>${hora}</td>
              <td>$ ${anticipo.monto.toLocaleString("es-CL")}</td>
              <td>${estadoBadge}</td>
            </tr>
          `;
          })
          .join("");

        document.getElementById("detalle_anticipo").innerHTML = anticipoHTML;
      }

      $("#ModalAnticipo").modal("show");
    }
  } catch (error) {
    console.error("Error al obtener anticipos:", error);
  }
}

document.getElementById("btnServicios").addEventListener("click", getServicios);

async function getServicios() {
  const url = `${BASE_URL}getServicioUsuario/${user.id_usuario}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;

    if (data.estado !== "ok" || data.codigo !== 200) {
      return toast("No se encontraron servicios", "info");
    }

    if (data.estado === "ok" && data.codigo === 200) {
      const serviciosData = data.data;

      const servicios = Array.isArray(serviciosData.servicios)
        ? serviciosData.servicios
        : [];

      document.getElementById("total_servicio").innerHTML = `Monto Total: $ ${
        serviciosData.total.toLocaleString("es-CL") || 0
      }`;

      document.getElementById(
        "usuario_servicio"
      ).innerHTML = `Usuario: ${user.nombre} ${user.apellido}`;

      const servicioHTML = servicios.map((servicio) => {
        const fecha = moment(servicio.fecha_crea).format("DD/MM/YYYY");
        const hora = moment(servicio.fecha_crea).format("HH:mm");

        let estadoBadge = "";
        if (servicio.estado === 0) {
          estadoBadge =
            '<span class="badge badge-sm badge-info">Pendiente</span>';
        } else if (servicio.estado === 1) {
          estadoBadge =
            '<span class="badge badge-sm badge-primary">En proceso</span>';
        } else if (servicio.estado === 2) {
          estadoBadge =
            '<span class="badge badge-sm badge-success">Pagado</span>';
        }

        return `
          <tr>
            <td>${fecha}</td>
            <td>${hora}</td>
            <td>$ ${servicio.precio_servicio.toLocaleString("es-CL")}</td>
            <td>${estadoBadge}</td>
          </tr>
        `;
      });

      document.getElementById("detalle_servicio").innerHTML =
        servicioHTML.join("");
      $("#ModalServicio").modal("show");
    }
  } catch (error) {
    console.error("Error al obtener los servicios:", error);
  }
}

document
  .getElementById("btnComisiones")
  .addEventListener("click", getComisiones);

async function getComisiones() {
  const url = `${BASE_URL}getComisionesUsuario/${user.id_usuario}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;

    console.log("Respuesta API:", data);

    if (!data || data.estado !== "ok" || data.codigo !== 200) {
      return toast("No se encontraron comisiones", "info");
    }

    const { comisiones, total } = data.data;

    document.getElementById("total_comision").innerHTML = `Monto Total: $ ${
      total.toLocaleString("es-CL") || 0
    }`;

    document.getElementById(
      "usuario_comision"
    ).innerHTML = `Usuario: ${user.nombre} ${user.apellido}`;

    if (!Array.isArray(comisiones) || comisiones.length === 0) {
      document.getElementById("detalle_comision").innerHTML =
        "<tr><td colspan='4'>No hay comisiones registradas</td></tr>";
      return;
    }

    const comisionHTML = comisiones.map((comision) => {
      const fecha = moment(comision.fecha_crea).format("DD/MM/YYYY");
      const hora = moment(comision.fecha_crea).format("HH:mm");

      const estadoBadge =
        comision.estado === 0
          ? '<span class="badge badge-sm badge-success">Pagado</span>'
          : '<span class="badge badge-sm badge-info">Pendiente</span>';

      return `
        <tr>
          <td>${fecha}</td>
          <td>${hora}</td>
          <td>$ ${comision.comision.toLocaleString("es-CL")}</td>
          <td>${estadoBadge}</td>
        </tr>
      `;
    });

    document.getElementById("detalle_comision").innerHTML =
      comisionHTML.join("");
    $("#ModalComision").modal("show");
  } catch (error) {
    console.error("Error al obtener comisiones:", error);
  }
}

async function getPropinas() {
  const url = `${BASE_URL}getPropina/${user.id_usuario}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" || data.codigo !== 200) {
      document.getElementById("propinas_garzon").innerHTML =
        '<span class="badge badge-sm badge-info">Sin propinas</span>';
      return;
    }

    if (data.estado === "ok" && data.codigo === 200) {
      if (document.getElementById("propinas_garzon")) {
        document.getElementById(
          "propinas_garzon"
        ).innerHTML = `<span class="badge badge-sm badge-success">$ ${data.data.total.toLocaleString(
          "es-CL"
        )}</span>`;
      }
    }
  } catch (error) {
    console.error("Error al obtener propinas:", error);
  }
}
document
  .getElementById("btnHorasExtra")
  .addEventListener("click", gethoraExtra);

async function gethoraExtra() {
  const url = `${BASE_URL}getHoraExtra/${user.id_usuario}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);
    if (data.estado !== "ok" && data.codigo !== 200) {
      return toast("No se encontraron horas extras", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById(
        "usuario_hora"
      ).innerHTML = `Usuario : ${user.nombre} ${user.apellido}`;
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
        const estadoBadge =
          item.estado === 0
            ? '<span class="badge badge-sm badge-success">Pagado</span>'
            : '<span class="badge badge-sm badge-info">Pendiente</span>';
        return `
            <tr>
              <td>${item.fecha}</td>
              <td>${item.hora}</td>
              <td>$ ${item.monto.toLocaleString("es-CL")}</td>
              <td>${estadoBadge}</td> 
            </tr>
          `;
      });
      html += detalle.join("");
      document.getElementById("detalle_horas_extra").innerHTML = html;

      $("#ModalDetalleHoraExtra").modal("show");
    }
  } catch (error) {
    console.error(error);
  }
}
