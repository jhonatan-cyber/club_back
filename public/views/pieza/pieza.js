let tbPieza;
document.addEventListener("DOMContentLoaded", () => {
  getPiezas();
  const nombre = document.getElementById("nombre");
  const precio = document.getElementById("precio");
  nombre.focus();
  nombre.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (nombre.value === "") {
        toast("El nombre es requerido", "info");
        nombre.focus();
        return;
      }
      nombre.value = capitalizarPalabras(nombre.value);
      precio.focus();
    }
  });
  precio.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (precio.value === "") {
        toast("El precio es requerido", "info");
        precio.focus();
        return;
      }
      createPieza(e);
    }
  });
});

async function getPiezas() {
  const url = `${BASE_URL}getPiezas`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      tbPieza = $("#tbPieza").DataTable({
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
          { data: "precio" },
          {
            data: null,
            render: (data, type, row) => {
              if (row.estado === 1) {
                return `<span class="badge badge-sm badge-success">Disponible</span>`;
              }
              return `<span class="badge badge-sm badge-danger">Ocupado</span>`;
            },
          },
          {
            data: null,
            render: (data, type, row) =>
              `<button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_pieza}" onclick="getPieza('${row.id_pieza}')"><i class="fas fa-edit"></i></button>
               <button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_pieza}" onclick="deletePieza('${row.id_pieza}')"><i class="fas fa-trash"></i></button>`,
          },
        ],
      });
    } else {
      return toast("No se encontraron habitaciones", "info");
    }
  } catch (error) {
    console.error(error);
  }
}

function MPieza(e) {
  e.preventDefault();
  document.getElementById("id_pieza").value = "";
  document.getElementById("nombre").value = "";
  document.getElementById("precio").value = "";
  document.getElementById("tituloPieza").innerHTML = "Nueva Habitacion";
  $("#ModalPieza").modal("show");
  $("#ModalPieza").on("shown.bs.modal", () => {
    document.getElementById("nombre").focus();
  });
}
async function createPieza(e) {
  e.preventDefault();
  const id_pieza = document.getElementById("id_pieza").value;
  const nombre = document.getElementById("nombre").value;
  const precio = document.getElementById("precio").value;

  if (!nombre) {
    return toast("El nombre es requerido", "info");
  }
  if (!precio) {
    return toast("El precio es requerido", "info");
  }

  try {
    const data = {
      nombre: nombre,
      precio: precio,
      id_pieza: id_pieza,
    };
    const url = `${BASE_URL}createPieza`;
    const resp = await axios.post(url, data, config);
    const result = resp.data;
    if (result.estado === "ok" && result.codigo === 201) {
      toast("Habitacion registrada correctamente", "success");
      getPiezas();
      $("#ModalPieza").modal("hide");
    }
  } catch (error) {
    resultado = error.response.data;
    if (resultado.codigo === 409 && resultado.estado === "error") {
      return toast("La habitacion ingresado ya existe", "info");
    }
    if (resultado.codigo === 500 && resultado.estado === "error") {
      return toast(
        "Error al registrar la habitacion, intente nuevamente",
        "warning"
      );
    }
  }
}

async function getPieza(id) {
  const url = `${BASE_URL}getPieza/${id}`;
  document.getElementById("tituloPieza").innerHTML = "Modificar Habitacion";
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById("id_pieza").value = data.data.id_pieza;
      document.getElementById("nombre").value = data.data.nombre;
      document.getElementById("precio").value = data.data.precio;

      $("#ModalPieza").modal("show");
      $("#ModalPieza").on("shown.bs.modal", () => {
        document.getElementById("nombre").focus();
      });
    }
  } catch (error) {
    console.error(error);
  }
}

async function deletePieza(id) {
  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "¿Está seguro de eliminar la habitacion ?",
    icon: "info",
    showCancelButton: true,
    confirmButtonText: "Si, eliminar",
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
    const url = `${BASE_URL}deletePieza/${id}`;
    try {
      const resp = await axios.get(url, config);
      const data = resp.data;
      if (data.estado === "ok" && data.codigo === 201) {
        toast("Habitacion eliminada correctamente", "success");
        getPiezas();
      }
    } catch (error) {
      resultado = error.response.data;
      if (resultado.codigo === 500 && resultado.estado === "error") {
        return toast(
          "Error al eliminar la habitacion, intente nuevamente",
          "warning"
        );
      }
    }
  }
}
