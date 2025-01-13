let tbCategoria;

document.addEventListener("DOMContentLoaded", () => {
  getCategorias();
  const nombre = document.getElementById("nombre");
  const descripcion = document.getElementById("descripcion");
  document.getElementById("nombre").focus();
  nombre.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (nombre.value === "") {
        toast("El nombre del categoria es obligatorio", "warning");
        nombre.focus();
        return;
      }
      nombre.value = capitalizarPalabras(nombre.value);
      descripcion.focus();
    }
  });
  descripcion.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (descripcion.value === "") {
        toast("La descripcion del categoria es obligatoria", "warning");
        descripcion.focus();
        return;
      }
      descripcion.value = primeraLetraMayuscula(descripcion.value);
      createCategoria(e);
    }
  });
});
function MCategoria(e) {
  e.preventDefault();
  document.getElementById("id_categoria").value = "";
  document.getElementById("tituloCategoria").innerHTML = "Nuevo Categoria";
  document.getElementById("frmCategoria").reset();
  $("#ModalCategoria").modal("show");
  $("#ModalCategoria").on("shown.bs.modal", () => {
    document.getElementById("nombre").focus();
  });
}

async function getCategorias() {
  const url = `${BASE_URL}getCategorias`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" && data.codigo !== 200) {
      return toast("No se encontraron categorias", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      tbCategoria = $("#tbCategoria").DataTable({
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
            data: null,
            render: (data, type, row, meta) => {
              const descripcion = row.descripcion;
              let resultado = "";
              for (let i = 0; i < descripcion.length; i += 24) {
                resultado += `${descripcion.substring(i, i + 24)}<br>`;
              }
              return resultado;
            },
          },
          {
            data: null,
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
                return `<button title="Editar categoria" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_categoria}" onclick="getCategoria(\'${row.id_categoria}\')"><i class="fas fa-edit"></i></button> 
                <button title="Eliminar categoria" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_categoria}" onclick="deleteCategoria(\'${row.id_categoria}\')"><i class="fas fa-trash"></i></button>`;
              } else {
                return `<button title="Activar categoria" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_categoria}" onclick="highCategoria('${row.id_categoria}')"><i class="fa-solid fa-check-to-slot"></i></button>`;
              }
            },
          },
        ],
      });
    }
  } catch (e) {
    console.log(e);
  }
}

async function getCategoria(id) {
  const url = `${BASE_URL}getCategoria/${id}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado !== "ok" && data.codigo !== 200) {
      return toast("No se encontraron datos", "info");
    }
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById("id_categoria").value = data.data.id_categoria;
      document.getElementById("nombre").value = data.data.nombre;
      document.getElementById("descripcion").value = data.data.descripcion;
      document.getElementById("tituloCategoria").innerHTML = "Editar Categoria";
      $("#ModalCategoria").modal("show");
      $("#ModalCategoria").on("shown.bs.modal", () => {
        document.getElementById("nombre").focus();
      });
    }
  } catch (e) {
    console.log(e);
  }
}

async function createCategoria(e) {
  e.preventDefault();
  const id_categoria = document.getElementById("id_categoria").value;
  const nombre = document.getElementById("nombre").value;
  const descripcion = document.getElementById("descripcion").value;

  if (!nombre) {
    return toast("El nombre del categoria es obligatorio", "warning");
  }
  if (!descripcion) {
    return toast("La descripción del categoria es obligatoria", "warning");
  }

  try {
    const datos = {
      nombre: nombre,
      descripcion: descripcion,
      id_categoria: id_categoria,
    };
    const url = `${BASE_URL}createCategoria`;
    const resp = await axios.post(url, datos, config);
    const result = resp.data;
    if (result.estado === "ok" && result.codigo === 201) {
      toast("Categoria registrado correctamente", "success");
      $("#ModalCategoria").modal("hide");
      getCategorias();
      return;
    }
  } catch (error) {
    resultado = error.response.data;
    if (resultado.codigo === 409 && resultado.estado === "error") {
      return toast("El categoria ingresado ya existe", "info");
    }
    if (resultado.codigo === 500 && resultado.estado === "error") {
      return toast(
        "Error al registrar el categoria, intente nuevamente",
        "warning"
      );
    }
  }
}

async function deleteCategoria(id) {
  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "¿Está seguro de eliminar el categoria ?",
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
    const url = `${BASE_URL}deleteCategoria/${id}`;
    try {
      const resp = await axios.get(url, config);
      const data = resp.data;
      if (data.estado === "ok" && data.codigo === 200) {
        toast("Categoria eliminado correctamente", "success");
        getCategorias();
      }
    } catch (error) {
      resultado = error.response.data;
      if (resultado.codigo === 500 && resultado.estado === "error") {
        return toast(
          "Error al eliminar el categoria, intente nuevamente",
          "warning"
        );
      }
    }
  }
}
async function highCategoria(id) {
  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "¿Está seguro de activar la categoria?",
    icon: "info",
    showCancelButton: true,
    confirmButtonText: "Si, activar",
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
    const url = `${BASE_URL}highCategoria/${id}`;
    try {
      const resp = await axios.get(url, config);
      const data = resp.data;
      if (data.estado === "ok" && data.codigo === 200) {
        toast("Categoria activada correctamente", "success");
        getCategorias();
      }
    } catch (error) {
      resultado = error.response.data;
      if (resultado.codigo === 500 && resultado.estado === "error") {
        return toast("Error al activar la categoria, intente nuevamente", "info");
      }
    }
  }
}