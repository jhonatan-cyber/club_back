let tbCategoria;

document.addEventListener("DOMContentLoaded", () => {
  getCategorias();
  const nombre = document.getElementById("nombre_c");
  const descripcion = document.getElementById("descripcion_c");
  document.getElementById("nombre_c").focus();
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
    document.getElementById("nombre_c").focus();
  });
}

async function getCategorias() {
  const url = `${BASE_URL}getCategorias`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
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
            render: (data, type, row) =>
              `<button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_categoria}" onclick="getCategoria(\'${row.id_categoria}\')"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_categoria}" onclick="deleteCategoria(\'${row.id_categoria}\')"><i class="fas fa-trash"></i></button>`,
          },
        ],
      });
    } else {
      return toast("No se encontraron datos", "info");
    }
  } catch (e) {
    resultado = e.response.data;
    if (resultado.codigo === 400 && resultado.error === "Error") {
      return toast(resultado.data, "info");
    }
  }
}

async function getCategoria(id) {
  const url = `${BASE_URL}getCategoria/${id}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById("id_categoria").value = data.data.id_categoria;
      document.getElementById("nombre_c").value = data.data.nombre;
      document.getElementById("descripcion_c").value = data.data.descripcion;
      document.getElementById("tituloCategoria").innerHTML = "Editar Categoria";
      $("#ModalCategoria").modal("show");
      $("#ModalCategoria").on("shown.bs.modal", () => {
        document.getElementById("nombre_c").focus();
      });
    }
  } catch (e) {
    console.log(e);
  }
}

async function createCategoria(e) {
  e.preventDefault();
  const id_categoria = document.getElementById("id_categoria").value;
  const nombre = document.getElementById("nombre_c").value;
  const descripcion = document.getElementById("descripcion_c").value;

  if (!nombre) {
    return toast("El nombre del categoria es obligatorio", "warning");
  }
  if (!descripcion) {
    return toast("La descripción del categoria es obligatoria", "warning");
  }

  try {
    const data = {
      nombre: nombre,
      descripcion: descripcion,
      id_categoria: id_categoria,
    };
    const url = `${BASE_URL}createCategoria`;
    const resp = await axios.post(url, data, config);
    const result = resp.data;
    if (result.estado === "ok" && result.codigo === 201) {
      toast("Categoria registrado correctamente", "success");
      $("#ModalCategoria").modal("hide");
      getCategorias();
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
    title: "NuweSoft",
    text: "¿Está seguro de eliminar el categoria ?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, eliminar",
    cancelButtonText: "No, cancelar",
    customClass: {
      confirmButton: "btn btn-danger btn-sm rounded-pill",
      cancelButton: "btn btn-secondary btn-sm rounded-pill",
    },
    buttonsStyling: false,
    confirmButtonColor: "#dc3545",
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
