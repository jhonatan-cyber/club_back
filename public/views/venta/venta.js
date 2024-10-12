let tbVenta;
document.addEventListener("DOMContentLoaded", () => {
  getVentas();
});

async function getVentas() {
  const url = `${BASE_URL}getVentas`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if(data.estado === "ok" && data.codigo === 200){
        if (data.codigo === 200 && data.estado === "ok") {
            tbVenta = $("#tbVenta").DataTable({
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
                { data: "codigo" },
                {
                  data: null,
                  render: (data, type, row) => `${row.nombre_c} ${row.apellido_c}`,
                },
                { data: "metodo_pago" },
                { data: "total" },
                {
                    data: "fecha_crea",
                    render: (data, type, row) => moment(data).format('DD/MM/YYYY HH:mm'),
                },
                
                {
                  data: null,
                  render: (data, type, row) =>
                    `<button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_venta}" onclick="verVenta('${row.id_venta}')">
                        <i class="fa-solid fa-eye"></i>
                      </button> `,
                },
              ],
            });
          } else {
            return toast("No se encontraron ventas registradas", "info");
          }
    }
  } catch (error) {
    console.error(error);
  }
}

async function verVenta(id_venta) {
    console.log(id_venta);
}