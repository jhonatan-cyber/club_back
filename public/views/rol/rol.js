let tbRol;

document.addEventListener("DOMContentLoaded", () => {
  getRoles();
  const nombre = document.getElementById("nombre");
  document.getElementById("nombre").focus();
  nombre.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (nombre.value === "") {
        nombre.focus();
        return toast("El nombre del rol es requerido", "info");
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
    document.getElementById("nombre").focus();
  });
}

async function getRoles() {
  const url = `${BASE_URL}getRoles`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" && data.codigo !== 200) {
      return toast("No se encontraron roles registrados", "info");
    }
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
    }
  } catch (e) {
    result = error.response.data;
    if (result.codigo === 500 && result.estado === "error") {
      return toast("Error al obtener los roles, intente nuevamente", "warning");
    }
  }
}

async function getRol(id) {
  const url = `${BASE_URL}getRol/${id}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" && data.codigo !== 200) {
      return toast("No se encontraron datos del rol", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById("id_rol").value = data.data.id_rol;
      document.getElementById("nombre").value = data.data.nombre;
      document.getElementById("tituloRol").innerHTML = "Editar Rol";
      $("#ModalRol").modal("show");
      $("#ModalRol").on("shown.bs.modal", () => {
        document.getElementById("nombre").focus();
      });
    }
  } catch (e) {
    result = error.response.data;
    if (result.codigo === 500 && result.estado === "error") {
      return toast("Error al obtener el rol, intente nuevamente", "warning");
    }
  }
}

async function createRol(e) {
  e.preventDefault();
  const id_rol = document.getElementById("id_rol").value;
  const nombre = document.getElementById("nombre").value;
  if (!nombre) {
    return toast("El nombre del rol es requerido", "info");
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
      $("#ModalRol").modal("hide");
      sendWebSocketMessage("rol", "createRol", data);
      getRoles();
      return toast("Rol registrado correctamente", "success");
    }
  } catch (error) {
    resultado = error.response.data;
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
        sendWebSocketMessage("rol", "eliminar", { id_rol: id });
        getRoles();

        return toast("Rol eliminado correctamente", "success");
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
        getRoles();
        return toast("Rol activado correctamente", "success");
      }
    } catch (error) {
      resultado = error.response.data;
      if (resultado.codigo === 500 && resultado.estado === "error") {
        return toast("Error al activar el rol, intente nuevamente", "info");
      }
    }
  }
}
