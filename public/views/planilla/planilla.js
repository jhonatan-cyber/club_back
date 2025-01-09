let tbPlanilla;
document.addEventListener("DOMContentLoaded", () => {
  getPlanillas();
});

async function getPlanillas() {
  const url = `${BASE_URL}getPlanillas`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      tbPlanilla = $("#tbPlanilla").DataTable({
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
          { data: "nombre_completo" },
          { data: "sueldo" },
          { data: "ventas" },
          { data: "servicios" },
          { data: "propinas" },
          { data: "aporte" },
          { data: "anticipos" },
          { data: "total" },
          {
            data: null,
            render: (data, type, row) =>
              ` <button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_usuario}" onclick="pagarPlanilla('${row.id_usuario}')">
                          <i class="fa-solid fa-money-bill-transfer"></i>
                        </button>`,
          },
        ],
      });
    }
  } catch (error) {
    console.error("Error al obtener planillas:", error);
  }
}

async function pagarPlanilla(id) {
  /*   const result = await Swal.fire({
      title: "Las Muñecas de Ramón",
      text: "¿Está seguro de realizar el pago ?",
      icon: "info",
      showCancelButton: true,
      confirmButtonText: "Si, pagar",
      cancelButtonText: "No, cancelar",
      customClass: {
        confirmButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
        cancelButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
        popup: "swal2-dark",
        title: "swal2-title",
        htmlContainer: "swal2-html-container"
      },
      buttonsStyling: false,
      confirmButtonColor: "#dc3545",
      background: "var(--bs-body-bg)",
      color: "var(--bs-body-color)",
    });
    if (result.isConfirmed) {
      console.log(id)
    } */

  const url = `${BASE_URL}pagarPlanilla/${id}`;

  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (resp.status === 204) {
      console.log("No hay recursos que mostrar.");
      return;
    }
    console.log(data);
  } catch (error) {
    console.error("Error al pagar planilla:", error);
  }
}
