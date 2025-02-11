let tbDplanilla;
document.addEventListener("DOMContentLoaded", () => {
  var calendarEl = document.getElementById("calendar");
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: "es",
    headerToolbar: {
      left: "prev titleCustom next today",
      center: "title",
      right: "prevYear yearLabel nextYear",
    },
    customButtons: {
      titleCustom: {
        text: "Mes",
        click: () => {},
      },
      yearLabel: {
        text: "Año",
        click: () => {},
      },
    },
    dateClick: (info) => {
      openModal(info.dateStr);
    },
    dayCellDidMount: function (info) {
      info.el.classList.add("custom-day-cell");
    },
  });
  calendar.render();
});

async function openModal(date) {
  const url = `${BASE_URL}getPlanillaFecha/${date}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      if (typeof data.data === "string") {
        return toast(data.data, "info");
      }
      document.getElementById(
        "total_anticipos"
      ).innerHTML = `Total Anticipos : $ ${data.data.totales.total_anticipos.toLocaleString(
        "es-CL"
      )}`;
      document.getElementById(
        "total_propinas"
      ).innerHTML = `Total Propinas : $ ${data.data.totales.total_propinas.toLocaleString(
        "es-CL"
      )}`;
      document.getElementById(
        "total_servicios"
      ).innerHTML = `Total Servicios : $ ${data.data.totales.total_servicios.toLocaleString(
        "es-CL"
      )}`;
      document.getElementById(
        "total_ventas"
      ).innerHTML = `Total Ventas : $ ${data.data.totales.total_ventas.toLocaleString(
        "es-CL"
      )}`;
      tbDplanilla = $("#tbDplanilla").DataTable({
        data: data.data.planilla,
        language: LENGUAJE,
        destroy: true,
        responsive: true,
        info: true,
        lengthMenu: [DISPLAY_LENGTH, 10, 25, 50],
        autoWidth: true,
        paging: true,
        searching: true,
        columns: [
          { data: "usuario" },
          {
            data: null,
            render: (data) => `$ ${data.sueldo.toLocaleString("es-CL")}`,
          },
          {
            data: null,
            render: (data) => `$ ${data.comision.toLocaleString("es-CL")}`,
          },
          {
            data: null,
            render: (data) => `$ ${data.servicio.toLocaleString("es-CL")}`,
          },
          {
            data: null,
            render: (data) => `$ ${data.propina.toLocaleString("es-CL")}`,
          },
          { data: "horas" },
          {
            data: null,
            render: (data) => `$ ${data.extras.toLocaleString("es-CL")}`,
          },
          {
            data: null,
            render: (data) => `$ ${data.anticipo.toLocaleString("es-CL")}`,
          },
          {
            data: null,
            render: (data) => `$ ${data.aporte.toLocaleString("es-CL")}`,
          },
          {
            data: null,
            render: (data) => `$ ${data.total_pagar.toLocaleString("es-CL")}`,
          },
          {
            data: null,
            render: (data, type, row) => {
              return `<button title="Ver detalles" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_usuario}" onclick="verDetalles('${row.id_usuario}')">
                                <i class="fa-solid fa-eye"></i>
                            </button>`;
            },
          },
        ],
      });

      $("#ModalDplanilla").modal("show");
    }
  } catch (e) {
    console.log(e);
  }
}
