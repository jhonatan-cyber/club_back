let tbComision;
document.addEventListener("DOMContentLoaded", () => {
  const usuario = JSON.parse(localStorage.getItem("usuario"));
  getComisiones();
});

async function getComisiones() {
  const url = `${BASE_URL}getComisiones`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      tbComision = $("#tbComision").DataTable({
        data: data.data.comisiones,
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
            render: (data) => `${Number(data).toLocaleString('es-ES')}`,
            className: "text-center"
          },
          { 
            data: "total_servicio",
            render: (data) => `${Number(data).toLocaleString('es-ES')}`,
            className: "text-center"
          },
          { 
            data: "anticipo",
            render: (data) => `${Number(data).toLocaleString('es-ES')}`,
            className: "text-center"
          },
          { 
            data: "total",
            render: (data) => `${Number(data).toLocaleString('es-ES')}`,
            className: "text-center fw-bold"
          },
          {
            data: "estado",
            render: (data) => {
              return data === 1 
                ? '<span class="badge badge-info">Pendiente</span>'
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
