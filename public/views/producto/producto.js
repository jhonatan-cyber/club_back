let tbProducto;
document.addEventListener("DOMContentLoaded", () => {
  getCategorias();
  const input = document.getElementById("foto");
  if (input) {
    input.addEventListener("change", preview);
  }
  enterKey();
});
async function getCategorias() {
  const url = `${BASE_URL}getCategorias`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      const categoriaElements = document.getElementById("categorias");
      categoriaElements.innerHTML = "";
      const categorias = data.data;
      for (let i = 0; i < categorias.length; i++) {
        const categoria = categorias[i];
        const categoriaElement = ` 
                    <a onclick="getProductoCategoria('${categoria.id_categoria}', '${categoria.nombre}')" class="col-xl-3 col-md-3 col-sm-6 mb-md-5 mb-xl-2">
                        <div>
                            <div class="card overflow-hidden h-md-50 mb-5 mb-xl-2 hover-scale shadow-sm parent-hover btn-sm">
                                <div class="card-body d-flex justify-content-between flex-column px-0 pb-0">
                                    <div class="mb-4 px-9">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                                                <i class="fa-solid fa-martini-glass-citrus"></i>
                                                <small>${categoria.nombre}</small>
                                            </span>
                                        </div>
                                        <span class="fs-6 fw-semibold text-gray-600">
                                            <small><b>${categoria.descripcion}</b></small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>`;
        categoriaElements.innerHTML += categoriaElement;
      }
    }
  } catch (error) {
    console.error("Error fetching categories:", error);
  }
}
async function getProductoCategoria(id_categoria, nombre) {
  localStorage.setItem("id_categoria", id_categoria);
  localStorage.setItem("nombre_categoria", nombre);
  document.getElementById("productos").hidden = false;
  document.getElementById("categorias").hidden = true;
  document.getElementById("nombr_bebida").innerHTML = nombre;

  const url = `${BASE_URL}getProductoCategoria/${id_categoria}`;

  try {
    const resp = await axios.get(url, config);
    const data = resp.data;

    if (data.estado === "ok" && data.codigo === 200) {
      const productos = data.data.length > 0 ? data.data : [];

      if ($.fn.dataTable.isDataTable("#tbProducto")) {
        $("#tbProducto").DataTable().clear().destroy();
      }

      tbProducto = $("#tbProducto").DataTable({
        data: productos,
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
              `<span class="badge badge-sm badge-primary">${
                meta.row + 1
              }</span>`,
          },
          { data: "codigo" },
          {
            data: null,
            render: (data, type, row) =>
              `<a href="${BASE_URL}public/assets/img/productos/${row.foto}" target="_blank"><img src="${BASE_URL}public/assets/img/productos/${row.foto}" alt="Foto" style="width: 50px; height: 50px; border-radius: 40%;"></a>`,
          },
          { data: "nombre" },
          { data: "descripcion" },
          { data: "precio" },
          { data: "comision" },
          {
            data: null,
            render: (data, type, row) =>
              `<button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_producto}" onclick="getProducto('${row.id_producto}')"><i class="fas fa-edit"></i></button>
               <button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_producto}" onclick="deleteProducto('${row.id_producto}')"><i class="fas fa-trash"></i></button>`,
          },
        ],
      });
      if (productos.length === 0) {
        toast("No se encontraron datos registrados", "info");
      }
    }
  } catch (error) {
    console.error("Error:", error);
  }
}
function Atras(e) {
  e.preventDefault();
  document.getElementById("productos").hidden = true;
  document.getElementById("categorias").hidden = false;
  getCategorias();
  localStorage.removeItem("id_categoria");
  localStorage.removeItem("nombre_categoria");
}
function Mproducto(e) {
  e.preventDefault();
  reset();
  const codigo = generarCodigoAleatorio(8);
  document.getElementById("codigo").value = codigo;
  document.getElementById("tituloProducto").innerHTML = "Nuevo Producto";
  const wrapper = document.getElementById("imagen");
  wrapper.style.backgroundImage = "none";
  $("#ModalProducto").modal("show");
  $("#ModalProducto").on("shown.bs.modal", () => {
    document.getElementById("txt_nombre").innerHTML = "<b>Nombre</b>";
    document.getElementById("nombre").setAttribute("placeholder", "");
    document.getElementById("nombre").focus();
  });
}
function reset() {
  document.getElementById("id_producto").value = "";
  document.getElementById("codigo").value = "";
  document.getElementById("nombre").value = "";
  document.getElementById("descripcion").value = "";
  document.getElementById("precio").value = "";
  document.getElementById("comision").value = "";
  const wrapper = document.getElementById("imagen");
  wrapper.style.backgroundImage = "none";
}
async function deleteProducto(id) {
  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "¿Está seguro de eliminar el producto ?",
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
    try {
      const url = `${BASE_URL}deleteProducto/${id}`;
      const resp = await axios.get(url, config);
      if (resp.data.estado === "ok" && resp.data.codigo === 200) {
        getProductoCategoria(
          localStorage.getItem("id_categoria"),
          localStorage.getItem("nombre_categoria")
        );
        toast("Producto eliminado correctamente", "success");
      }
    } catch (error) {
      resultado = error.response.data;
      if (resultado.codigo === 500 && resultado.estado === "error") {
        return toast(
          "Error al eliminar el producto, intente nuevamente",
          "warning"
        );
      }
    }
  }
}
async function createProducto(e) {
  e.preventDefault();
  const id_producto = document.getElementById("id_producto").value;
  const codigo = document.getElementById("codigo").value;
  const nombre = document.getElementById("nombre").value;
  const descripcion = document.getElementById("descripcion").value;
  const precio = document.getElementById("precio").value;
  const comision = document.getElementById("comision").value;
  const fotoInput = document.getElementById("foto");
  const foto = fotoInput.files[0];
  const imagen_anterior = document.getElementById("imagen_anterior");
  const categoria_id = localStorage.getItem("id_categoria");
  validarDatos(codigo, nombre, descripcion, precio, comision, categoria_id);

  const formData = new FormData();
  formData.append("id_producto", id_producto);
  formData.append("codigo", codigo);
  formData.append("nombre", nombre);
  formData.append("descripcion", descripcion);
  formData.append("precio", precio);
  formData.append("comision", comision);
  if (foto) {
    formData.append("foto", foto);
  }
  if (imagen_anterior !== "") {
    formData.append("imagen_anterior", imagen_anterior.value);
  }

  formData.append("categoria_id", categoria_id);
  try {
    const resp = await axios.post(`${BASE_URL}createProducto`, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        Authorization: `Bearer ${TOKEN}`,
      },
    });

    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 201) {
      toast("Producto registrado correctamente", "success");
      $("#ModalProducto").modal("hide");
      getProductoCategoria(
        localStorage.getItem("id_categoria"),
        localStorage.getItem("nombre_categoria")
      );
    }
  } catch (error) {
    console.error(error);
    if (error.response.status === 409) {
      toast("El producto ingresado ya existe", "warning");
      if (id_producto !== "") {
        codigo.value = "";
        nombre.value = "";
        descripcion.value = "";
        precio.value = "";
        comision.value = "";
        nombre.focus();
        return;
      }
      reset();
      fotoInput.value = "";
      codigo.focus();
      return;
    }
    if (error.response.status === 500) {
      toast("Error al registrar el producto, intente nuevamente", "warning");
      return;
    }
  }
}
function validarDatos(
  codigo,
  nombre,
  descripcion,
  precio,
  comision,
  categoria_id
) {
  if (codigo === "") {
    toast("El codigo es requerido", "info");
    codigo.focus();
    return;
  }
  if (nombre === "") {
    toast("El nombre es requerido", "info");
    nombre.focus();
    return;
  }
  let descrip = descripcion;
  if (descrip === "") {
    descrip = "Sin descripcion";
  }
  if (precio === "") {
    toast("El precio es requerido", "info");
    precio.focus();
    return;
  }
  if (precio < 0) {
    toast("El precio no puede ser negativo", "info");
    precio.focus();
    return;
  }
  if (precio === 0) {
    toast("El precio no puede ser 0", "info");
    precio.focus();
    return;
  }
  if (categoria_id === "") {
    toast("Seleccione una categoria", "info");
    return;
  }
  if (comision < 0) {
    toast("La comisión no puede ser 0", "info");
    return;
  }

  if (comision === "") {
    toast("La comisión es requerida", "info");
    return;
  }
}
function enterKey() {
  const nombre = document.getElementById("nombre");
  const precio = document.getElementById("precio");
  const comision = document.getElementById("comision");
  const descripcion = document.getElementById("descripcion");

  nombre.focus();
  nombre.addEventListener("keyup", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (nombre.value === "") {
        toast("El nombre es requerido", "info");
        nombre.focus();
        return;
      }
      nombre.setAttribute("placeholder", "");
      nombre.value = capitalizarPalabras(nombre.value);
      document.getElementById("txt_precio").innerHTML = "<b>Precio</b>";
      precio.focus();
    }
  });
  precio.addEventListener("keyup", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (precio.value === "") {
        toast("El precio es requerido", "info");
        precio.focus();
        return;
      }
      if (precio.value < 0) {
        toast("El precio no puede ser negativo", "info");
        precio.focus();
        return;
      }
      if (precio.value === 0) {
        toast("El precio no puede ser 0", "info");
        precio.focus();
        return;
      }
      precio.setAttribute("placeholder", "");
      document.getElementById("txt_comision").innerHTML = "<b>Comision</b>";
      comision.focus();
    }
  });
  comision.addEventListener("keyup", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (comision.value === "") {
        toast("La comisión es requerida", "info");
        comision.focus();
        return;
      }
      if (comision.value < 0) {
        toast("La comisión no puede ser 0", "info");
        comision.focus();
        return;
      }

      comision.setAttribute("placeholder", "");
      document.getElementById("txt_descripcion").innerHTML =
        "<b>descripcion</b>";
      descripcion.focus();
    }
  });
  descripcion.addEventListener("keyup", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (descripcion.value === "") {
        toast("La descripción es requerida", "info");
        descripcion.focus();
        return;
      }
      descripcion.value = capitalizarPalabras(descripcion.value);
      descripcion.setAttribute("placeholder", "");
      createProducto(e);
    }
  });
}
async function getProducto(id) {
  document.getElementById("tituloProducto").innerHTML = "Modificar Producto";
  document.getElementById("txt_nombre").innerHTML = "<b>Nombre</b>";
  document.getElementById("txt_precio").innerHTML = "<b>Precio</b>";
  document.getElementById("txt_descripcion").innerHTML = "<b>Descripción</b>";
  document.getElementById("txt_comision").innerHTML = "<b>Comision</b>";

  const url = `${BASE_URL}getProducto/${id}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById("id_producto").value = data.data.id_producto;
      document.getElementById("codigo").value = data.data.codigo;
      document.getElementById("nombre").value = data.data.nombre;
      document.getElementById("descripcion").value = data.data.descripcion;
      document.getElementById("precio").value = Number.parseInt(
        data.data.precio
      );
      document.getElementById("comision").value = Number.parseInt(
        data.data.comision
      );
      document.getElementById("imagen_anterior").value = data.data.foto;
      const wrapper = document.querySelector("#imagen");
      if (data.data.foto !== "default.png") {
        const imageUrl = `${BASE_URL}public/assets/img/productos/${data.data.foto}`;
        wrapper.style.backgroundImage = `url(${imageUrl})`;
      } else {
        wrapper.style.backgroundImage = `url(${BASE_URL}public/assets/img/productos/default.png)`;
      }
      $("#ModalProducto").modal("show");
      $("#ModalProducto").on("shown.bs.modal", () => {
        document.getElementById("nombre").focus();
      });
    }
  } catch (error) {
    console.error("Error fetching product:", error);
  }
}
