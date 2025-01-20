let tbComision;
document.addEventListener("DOMContentLoaded", () => {
  const usuario = JSON.parse(localStorage.getItem("usuario"));
  if (usuario.rol === "Administrador") {
    getComisiones();
  }
  getComisiones();
});

async function getComisiones() {
  const url = `${BASE_URL}getComisiones`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log("entra");
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      tbComision = $("#tbComision").DataTable({
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
            data: "chica",
            className: "text-center"
          },
          { 
            data: "total_venta",
            render: (data) => `${parseFloat(data)}`,
            className: "text-center"
          },
          { 
            data: "total_servicio",
            render: (data) => ` ${parseFloat(data)}`,
            className: "text-center"
          },
          { 
            data: "anticipo",
            render: (data) => ` ${parseFloat(data)}`,
            className: "text-center"
          },
          { 
            data: "total",
            render: (data) => `${parseFloat(data)}`,
            className: "text-center fw-bold"
          },
          {
            data: "estado",
            render: (data) => {
              return data === 1 
                ? '<span class="badge badge-danger">Pendiente</span>'
                : '<span class="badge badge-success">Pagado</span>';
            },
            className: "text-center"
          }
        ],
      });
    } else {
      return toast("No se encontraron comisiones", "info");
    }
  } catch (error) {
    console.log(error);
  }
}
