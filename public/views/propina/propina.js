let tbPropina;
document.addEventListener("DOMContentLoaded", () => {
  getPropinas();
});

const getPropinas = async () => {
  const url = `${BASE_URL}getPropinas`;
  await axios
    .get(url, config)
    .then((response) => {
      if (response.data.estado !== "ok" && response.data.codigo !== 200) {
        return toast("No se encontraron propinas", "info");
      }

      if (response.data.estado === "ok" && response.data.codigo === 200) {
        console.log(response.data);
        tbPropina = $("#tbPropina").DataTable({
          data: response.data.data,
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
            { data: "monto_total" },
            {
              data: "fecha_crea",
              render: (data) => {
                return data ? new Date(data).toLocaleDateString() : "";
              },
            },
          ],
        });
      }
    })
    .catch((error) => {
      console.error(error);
    });
};
