let tbRol;

document.addEventListener("DOMContentLoaded", () => {
  getRoles();
  const nombre = document.getElementById("nombre_r");
  document.getElementById("nombre_r").focus();
  nombre.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (nombre.value === "") {
        toast("El nombre del rol es requerido", "info");
        nombre.focus();
        return;
      }
      nombre.value = capitalizarPalabras(nombre.value);
      createRol(e);
    }
  });
});

function MRol(e) {
  e.preventDefault();
  document.getElementById("id_rol").value = "";
  document.getElementById("tituloRol").innerHTML = "Nuevo Rol";
  document.getElementById("frmRol").reset();
  $("#ModalRol").modal("show");
  $("#ModalRol").on("shown.bs.modal", () => {
    document.getElementById("nombre_r").focus();
  });
}

async function getRoles() {
  const url = `${BASE_URL}getRoles`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      tbRol = $("#tbRol").DataTable({
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
              ` <span class="badge badge-sm badge-primary" >${formatNumber(
                meta.row + 1
              )}</span>`,
          },
          { data: "nombre" },
          {
            data: "estado",
            render: (data, type, row) => {
              if (row.estado === 1) {
                return `<span class="badge badge-sm badge-success">Activo</span>`;
              } else {
                return `<span class="badge badge-sm badge-danger">Inactivo</span>`;
              }
            },
          },
          {
            data: null,
            render: (data, type, row) => {
              if (row.estado === 1) {
                return `<button title="Editar rol" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_rol}" onclick="getRol('${row.id_rol}')">
                                <i class="fas fa-edit"></i>
                            </button> 
                            <button title="Eliminar rol" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_rol}" onclick="deleteRol('${row.id_rol}')">
                                <i class="fas fa-trash"></i>
                            </button>`;
              } else {
                return `<button title="Activar rol" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_rol}" onclick="highRol('${row.id_rol}')">
                                <i class="fa-solid fa-check-to-slot"></i>
                            </button>`;
              }
            },
          },
        ],
      });
    } else {
      return toast("No se encontraron datos", "info");
    }
  } catch (e) {
    console.log(e);
  }
}

async function getRol(id) {
  const url = `${BASE_URL}getRol/${id}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById("id_rol").value = data.data.id_rol;
      document.getElementById("nombre_r").value = data.data.nombre;
      document.getElementById("tituloRol").innerHTML = "Editar Rol";
      $("#ModalRol").modal("show");
      $("#ModalRol").on("shown.bs.modal", () => {
        document.getElementById("nombre_r").focus();
      });
    }
  } catch (e) {
    console.log(e);
  }
}

async function createRol(e) {
  e.preventDefault();
  const id_rol = document.getElementById("id_rol").value;
  const nombre = document.getElementById("nombre_r").value;

  if (!nombre) {
    return toast("El nombre del rol es obligatorio", "warning");
  }

  try {
    const data = {
      nombre: nombre,
      id_rol: id_rol,
    };
    const url = `${BASE_URL}createRol`;
    const resp = await axios.post(url, data, config);
    const result = resp.data;
    if (result.estado === "ok" && result.codigo === 201) {
      toast("Rol registrado correctamente", "success");
      $("#ModalRol").modal("hide");
      sendWebSocketMessage("rol", "createRol", data);

      getRoles();
    }
  } catch (error) {
    resultado = error.response.data;
    console.log(error);
    if (resultado.codigo === 409 && resultado.estado === "error") {
      return toast("El rol ingresado ya existe", "info");
    }
    if (resultado.codigo === 500 && resultado.estado === "error") {
      return toast("Error al registrar el rol, intente nuevamente", "warning");
    }
  }
}

async function deleteRol(id) {
  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "¿Está seguro de eliminar el rol ?",
    icon: "info",
    showCancelButton: true,
    confirmButtonText: "Si, eliminar",
    cancelButtonText: "No, cancelar",
    customClass: {
      confirmButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
      cancelButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
      popup: "swal2-dark",
      title: "swal2-title",
      htmlContainer: "swal2-html-container",
    },
    buttonsStyling: false,
    confirmButtonColor: "#dc3545",
    background: "var(--bs-body-bg)",
    color: "var(--bs-body-color)",
  });
  if (result.isConfirmed) {
    const url = `${BASE_URL}deleteRol/${id}`;
    try {
      const resp = await axios.get(url, config);
      const data = resp.data;
      if (data.estado === "ok" && data.codigo === 200) {
        toast("Rol eliminado correctamente", "success");

        // Notificar a través de WebSocket
        sendWebSocketMessage("rol", "eliminar", { id_rol: id });

        getRoles();
      }
    } catch (error) {
      resultado = error.response.data;
      if (resultado.codigo === 500 && resultado.estado === "error") {
        return toast("Error al eliminar el rol, intente nuevamente", "warning");
      }
    }
  }
}

async function highRol(id) {
  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "¿Está seguro de activar el rol ?",
    icon: "info",
    showCancelButton: true,
    confirmButtonText: "Si, activar",
    cancelButtonText: "No, cancelar",
    customClass: {
      confirmButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
      cancelButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
      popup: "swal2-dark",
      title: "swal2-title",
      htmlContainer: "swal2-html-container",
    },
    buttonsStyling: false,
    confirmButtonColor: "#dc3545",
    background: "var(--bs-body-bg)",
    color: "var(--bs-body-color)",
  });
  if (result.isConfirmed) {
    const url = `${BASE_URL}highRol/${id}`;
    try {
      const resp = await axios.get(url, config);
      const data = resp.data;
      if (data.estado === "ok" && data.codigo === 200) {
        toast("Rol activado correctamente", "success");
        getRoles();
      }
    } catch (error) {
      resultado = error.response.data;
      if (resultado.codigo === 500 && resultado.estado === "error") {
        return toast("Error al activar el rol, intente nuevamente", "info");
      }
    }
  }
}
