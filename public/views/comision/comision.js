let tbComision;
document.addEventListener("DOMContentLoaded", () => {
  console.log(localStorage.getItem("usuario"));
  const usuario = JSON.parse(localStorage.getItem("usuario"));
  if (usuario.rol === "Administrador") {
    getComisiones();
  }
});
async function getComisiones() {
  const url = `${BASE_URL}getComisiones`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
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
            data: null,
            render: (data, type, row, meta) =>
              `<span class="badge badge-sm badge-primary">${formatNumber(
                meta.row + 1
              )}</span>`,
          },
          { data: "nombre" },
          { data: "apellido" },
          { data: "total_comision" },
          {
            data: null,
            render: (data, type, row) => {
              if (row.estado === 1) {
                return `<span class="badge badge-sm badge-info">Pendiente</span>`;
              }
              return `<span class="badge badge-sm badge-success">Pagado</span>`;
            },
          },
          {
            data: null,
            render: (data, type, row) =>
              `<button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.usuario_id}" onclick="getComision('${row.usuario_id}')"><i class="fas fa-edit"></i></button>`,
          },
        ],
      });
    } else {
      return toast("No se encontraron comisiones", "info");
    }
    console.log(data);
  } catch (error) {
    console.log(error);
  }
}
