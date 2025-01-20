const user = JSON.parse(localStorage.getItem("usuario"));
let total_a_cobrar = 0;
document.addEventListener("DOMContentLoaded", async () => {
  vistas(user);
  if (user.rol === "Mesero") {
    await getPropinas();
    const elementosMostrar = ["cServicios", "cComisiones"];
    elementosMostrar.forEach((id) => {
      const elemento = document.getElementById(id);
      if (elemento) {
        elemento.hidden = true;
      }
    });
  }
  if (user.rol === "Chica") {
    const elementosMostrar = ["cPropinas", "cPedidos"];
    elementosMostrar.forEach((id) => {
      const elemento = document.getElementById(id);
      if (elemento) {
        elemento.hidden = true;
      }
    });
  }
});

function vistas(user) {
  if (user.rol === "Chica" || user.rol === "Mesero") {
    document.getElementById("cardAdmin").hidden = true;
    document.getElementById("cardEmpleado").hidden = false;
    document.getElementById("nombreChica").innerHTML =
      user.nombre + " " + user.apellido;
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
    console.log(data);
    if (data.estado !== "ok" || data.codigo !== 200) {
      return toast("No se encontraron asistencias", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      const asistencias = data.data.asistencias;
      const totales = data.data.totales;
      total_a_cobrar = total_a_cobrar + totales.gran_total;

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
      ).innerHTML = `Total a Cobrar : ${totales.gran_total}`;

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
document.getElementById("btnAnticipos").addEventListener("click", getAnticipos);

async function getAnticipos() {
  const url = `${BASE_URL}getAnticipoUsuario/${user.id_usuario}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" || data.codigo !== 200) {
      return toast("No se encontraron anticipos", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      const anticipos = data.data;
      document.getElementById(
        "total_anticipo"
      ).innerHTML = `Monto Total : ${anticipos[0].total}`;
      const anticipoHTML = await Promise.all(
        anticipos.map(async (anticipo) => {
          const fecha = moment(anticipo.fecha_crea).format("DD/MM/YYYY");
          const hora = moment(anticipo.fecha_crea).format("HH:mm");

          document.getElementById(
            "usuario_anticipo"
          ).innerHTML = `Usuario: ${user.nombre} ${user.apellido}`;
          const estadoBadge =
            anticipo.estado === 0
              ? '<span class="badge badge-sm badge-success">Pagado</span>'
              : '<span class="badge badge-sm badge-info">Pendiente</span>';

          return `
              <tr>
                <td>${fecha}</td>
                <td>${hora}</td>
                <td>${anticipo.monto}</td>
                <td>${estadoBadge}</td>
              </tr>
            `;
        })
      );
      document.getElementById("detalle_anticipo").innerHTML =
        anticipoHTML.join("");
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
      const servicios = data.data;
      document.getElementById("total_servicio").innerHTML = `Monto Total: ${
        servicios[0]?.total || 0
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
            <td>${servicio.precio_servicio}</td>
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
    if (data.estado !== "ok" || data.codigo !== 200) {
      return toast("No se encontraron comisiones", "info");
    }

    if (data.estado === "ok" && data.codigo === 200) {
      const comisiones = data.data;
      document.getElementById("total_comision").innerHTML = `Monto Total: ${
        comisiones[0]?.total || 0
      }`;

      document.getElementById(
        "usuario_comision"
      ).innerHTML = `Usuario: ${user.nombre} ${user.apellido}`;

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
            <td>${comision.comision}</td>
            <td>${estadoBadge}</td>
          </tr>
        `;
      });
      document.getElementById("detalle_comision").innerHTML =
        comisionHTML.join("");
      $("#ModalComision").modal("show");
    }
  } catch (error) {
    console.error("Error al obtener comisiones:", error);
  }
}

async function getPropinas() {
  const url = `${BASE_URL}getPropina/${user.id_usuario}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);
    if (data.estado !== "ok" || data.codigo !== 200) {
      return toast("No se encontraron propinas", "info");
    }

    if (data.estado === "ok" && data.codigo === 200) {
      if (document.getElementById("propinas_usuario")) {
        document.getElementById("propinas_usuario").innerHTML =
          data.data.monto_total;
      }
    }
  } catch (error) {
    console.error("Error al obtener propinas:", error);
  }
}
