let tbUsuario;
document.addEventListener("DOMContentLoaded", () => {
  getUsuarios();
  getRoles();
  if (document.getElementById("rol_id")) {
    $("#rol_id").select2({
      placeholder: "Seleccionar Rol",
      dropdownParent: $("#ModalUsuario .modal-body"),
    });
  }

  const input = document.getElementById("foto");
  if (input) {
    input.addEventListener("change", preview);
  }
  enterKey();
});

async function getRoles() {
  const url = `${BASE_URL}getRoles`;
  try {
    const response = await axios.get(url, config);
    const datos = response.data;
    if (datos.estado === "ok" && datos.codigo === 200) {
      if (document.getElementById("rol_id")) {
        const select = document.getElementById("rol_id");
        for (let i = 0; i < datos.data.length; i++) {
          const rol = datos.data[i];
          const option = document.createElement("option");
          option.value = rol.id_rol;
          option.text = rol.nombre;
          select.appendChild(option);
        }
      }
    }
  } catch (error) {
    console.log(error);
  }
}

function enterKey() {
  if (document.getElementById("run")) {
    const run = document.getElementById("run");
    const nick = document.getElementById("nick");
    const nombre = document.getElementById("nombre");
    const apellido = document.getElementById("apellido");
    const direccion = document.getElementById("direccion");
    const telefono = document.getElementById("telefono");
    const estado_civil = document.getElementById("estado_civil");
    const afp = document.getElementById("afp");
    const sueldo = document.getElementById("sueldo");
    const aporte = document.getElementById("aporte");
    const correo = document.getElementById("correo");
    const password = document.getElementById("password");
    const repetir = document.getElementById("repetir");
    const rol_id = document.getElementById("rol_id");

    run.focus();
    run.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (run.value === "") {
          toast("La cedula de identidad es requerida", "info");
          run.focus();
          return;
        }
        nick.setAttribute("placeholder", "");
        document.getElementById("txt_nick").innerHTML = "<b>Nick</b>";
        nick.focus();
      }
    });
    nick.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (nick.value === "") {
          toast("El nick es requerida", "info");
          nick.focus();
          return;
        }
        nick.value = capitalizarPalabras(nick.value);
        nombre.setAttribute("placeholder", "");
        document.getElementById("txt_nombre").innerHTML = "<b>Nombre(s)</b>";
        nombre.focus();
      }
    });
    nombre.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (nombre.value === "") {
          toast("El nombre es requerido", "info");
          nombre.focus();
          return;
        }
        nombre.value = capitalizarPalabras(nombre.value);
        apellido.setAttribute("placeholder", "");
        document.getElementById("txt_apellido").innerHTML =
          "<b>Apellido(s)</b>";
        apellido.focus();
      }
    });
    apellido.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (apellido.value === "") {
          toast("El apellido es requerido", "info");
          apellido.focus();
          return;
        }
        apellido.value = capitalizarPalabras(apellido.value);
        direccion.setAttribute("placeholder", "");
        document.getElementById("txt_direccion").innerHTML = "<b>Direccion</b>";
        direccion.focus();
      }
    });
    direccion.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (direccion.value === "") {
          direccion.value = "SN";
        }
        direccion.value = capitalizarPalabras(direccion.value);
        telefono.setAttribute("placeholder", "");
        document.getElementById("txt_telefono").innerHTML = "<b>Telefono</b>";
        telefono.focus();
      }
    });
    telefono.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (telefono.value === "") {
          toast("El telefono es requerido", "info");
          telefono.focus();
          return;
        }
        estado_civil.setAttribute("placeholder", "");
        document.getElementById("txt_estado").innerHTML = "<b>Estado Civil</b>";
        estado_civil.focus();
      }
    });
    estado_civil.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (estado_civil.value === "") {
          toast("El estado civil es requerido", "info");
          estado_civil.focus();
          return;
        }
        estado_civil.value = capitalizarPalabras(estado_civil.value);
        afp.setAttribute("placeholder", "");
        document.getElementById("txt_afp").innerHTML =
          "<b>Establecimiento (AFP)</b>";
        afp.focus();
      }
    });
    afp.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (afp.value === "") {
          toast("El establecimiento AFP es requerido", "info");
          afp.focus();
          return;
        }
        afp.value = capitalizarPalabras(afp.value);
        sueldo.setAttribute("placeholder", "");
        document.getElementById("txt_sueldo").innerHTML = "<b>Sueldo</b>";
        sueldo.focus();
      }
    });
    sueldo.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (sueldo.value === "") {
          toast("El sueldo es requerido", "info");
          sueldo.focus();
          return;
        }
        aporte.setAttribute("placeholder", "");
        document.getElementById("txt_aporte").innerHTML = "<b>Aporte a AFP</b>";
        aporte.focus();
      }
    });
    aporte.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (aporte.value === "") {
          toast("El aporte es requerido", "info");
          aporte.focus();
          return;
        }
        correo.setAttribute("placeholder", "");
        document.getElementById("txt_correo").innerHTML =
          "<b>Correo Electronico</b>";
        correo.focus();
      }
    });
    correo.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (correo.value === "") {
          toast("El correo electronico es requerido", "info");
          correo.focus();
          return;
        }
        password.setAttribute("placeholder", "");
        document.getElementById("txt_contraseña").innerHTML =
          "<b>Contraseña</b>";
        password.focus();
      }
    });
    password.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (password.value === "") {
          toast("La contraseña es requerida", "info");
          password.focus();
          return;
        }
        repetir.setAttribute("placeholder", "");
        document.getElementById("txt_confirmar").innerHTML =
          "<b>Repetir contraseña</b>";
        repetir.focus();
      }
    });
    repetir.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (repetir.value === "") {
          toast("Confirmar la contraseña", "info");
          repetir.focus();
          return;
        }
        rol_id.focus();
      }
    });
    rol_id.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        createUsuario(e);
      }
    });
  }
}

function Musuario(e) {
  e.preventDefault();
  document.getElementById("id_usuario").value = "";
  document.getElementById("tituloUsuario").innerHTML = "Nuevo usuario";
  document.getElementById("frmUsuario").reset();
  document.getElementById("foto").value = "";
  document.getElementById("corre").hidden = false;
  document.getElementById("contraseñas").hidden = false;
  const wrapper = document.getElementById("imagen");
  wrapper.style.backgroundImage = "none";
  $("#rol_id").val(0).trigger("change");
  $("#ModalUsuario").modal("show");
  $("#ModalUsuario").on("shown.bs.modal", () => {
    document.getElementById("run").setAttribute("placeholder", "");
    document.getElementById("run").focus();
  });
}
async function getUsuarios() {
  const url = `${BASE_URL}getUsuarios`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      tbUsuario = $("#tbUsuario").DataTable({
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
              `<span class="badge badge-sm badge-primary">${
                meta.row + 1
              }</span>`,
          },
          {
            data: null,
            render: (data, type, row) =>
              `<a href="${BASE_URL}public/assets/img/usuarios/${row.foto}" target="_blank"><img src="${BASE_URL}public/assets/img/usuarios/${row.foto}" alt="Foto" style="width: 50px; height: 50px; border-radius: 40%;"></a>`,
          },
          { data: "run" },
          { data: "nick" },
          { data: "nombre" },
          { data: "apellido" },
          { data: "estado_civil" },
          { data: "sueldo" },
          { data: "aporte" },
          { data: "afp" },
          { data: "direccion" },
          { data: "telefono" },
          { data: "rol" },
          {
            data: null,
            render: (data, type, row) => {
              if (row.estado === 1) {
                return `<span class="badge badge-sm badge-success">Activo</span>`;
              }
              return `<span class="badge badge-sm badge-danger">Inactivo</span>`;
            },
          },
          {
            data: null,
            render: (data, type, row) => {
              if (row.estado === 1) {
                return `<button title="Editar usuario" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_usuario}" onclick="getUsuario('${row.id_usuario}')">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button title="Eliminar usuario" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_usuario}" onclick="deleteUsuario('${row.id_usuario}')">
                          <i class="fas fa-trash"></i>
                        </button>`;
              }
              return `<button title="Activar Usuario" class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_usuario}" onclick="highUsuario('${row.id_usuario}')">
                                <i class="fa-solid fa-check-to-slot"></i>
                            </button>`;
            },
          },
        ],
      });
    } else {
      toast("No se encontraron usuarios registrados", "info");
    }
  } catch (error) {
    console.log(error);
  }
}

async function deleteUsuario(id) {
  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "¿Está seguro de eliminar el usuario ?",
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
    const url = `${BASE_URL}deleteUsuario/${id}`;
    try {
      const resp = await axios.get(url, config);
      const data = resp.data;
      if (data.estado === "ok" && data.codigo === 201) {
        toast("Usuario eliminado correctamente", "success");
        getUsuarios();
      }
    } catch (error) {
      resultado = error.response.data;
      if (resultado.codigo === 500 && resultado.estado === "error") {
        return toast(
          "Error al eliminar el usuario, intente nuevamente",
          "warning"
        );
      }
    }
  }
}

async function createUsuario(e) {
  e.preventDefault();
  const id_usuario = document.getElementById("id_usuario");
  const run = document.getElementById("run");
  const nick = document.getElementById("nick");
  const nombre = document.getElementById("nombre");
  const apellido = document.getElementById("apellido");
  const direccion = document.getElementById("direccion");
  const telefono = document.getElementById("telefono");
  const estado_civil = document.getElementById("estado_civil");
  const afp = document.getElementById("afp");
  const sueldo = document.getElementById("sueldo");
  const aporte = document.getElementById("aporte");
  const correo = document.getElementById("correo");
  const password = document.getElementById("password");
  const repetir = document.getElementById("repetir");
  const rol_id = document.getElementById("rol_id");
  const fotoInput = document.getElementById("foto");
  const foto = fotoInput.files[0];
  const imagen_anterior = document.getElementById("imagen_anterior");
  validateData(
    id_usuario,
    run,
    nick,
    nombre,
    apellido,
    direccion,
    telefono,
    estado_civil,
    afp,
    sueldo,
    aporte,
    correo,
    password,
    repetir,
    rol_id
  );
  const formData = new FormData();
  formData.append("id_usuario", id_usuario.value);
  formData.append("run", run.value);
  formData.append("nick", nick.value);
  formData.append("nombre", nombre.value);
  formData.append("apellido", apellido.value);
  formData.append("direccion", direccion.value);
  formData.append("telefono", telefono.value);
  formData.append("estado_civil", estado_civil.value);
  formData.append("afp", afp.value);
  formData.append("sueldo", sueldo.value);
  formData.append("aporte", aporte.value);
  formData.append("correo", correo.value);
  formData.append("password", password.value);
  formData.append("rol_id", rol_id.value);
  if (foto) {
    formData.append("foto", foto);
  }
  if (imagen_anterior.value !== "") {
    formData.append("imagen_anterior", imagen_anterior.value);
  }
  try {
    const resp = await axios.post(`${BASE_URL}createUsuario`, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        Authorization: `Bearer ${TOKEN}`,
      },
    });

    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 201) {
      toast("Usuario registrado correctamente", "info");
      $("#ModalUsuario").modal("hide");
      getUsuarios();
    }
  } catch (error) {
    console.error(error);
    if (error.response.status === 409) {
      toast("El usuario ingresado ya existe", "warning");
      if (id_usuario !== "") {
        run.value = "";
        correo.value = "";
        run.focus();
        return;
      }
      reset();
      fotoInput.value = "";
      run.focus();
      return;
    }
    if (error.response.status === 500) {
      toast("Error al registrar el usuario, intente nuevamente", "warning");
      return;
    }
  }
}

function reset() {
  document.getElementById("run").value = "";
  document.getElementById("nombre").value = "";
  document.getElementById("apellido").value = "";
  document.getElementById("direccion").value = "";
  document.getElementById("telefono").value = "";
  document.getElementById("correo").value = "";
  document.getElementById("password").value = "";
  document.getElementById("repetir").value = "";
  fotoInput.value = "";
  const wrapper = document.getElementById("imagen");
  wrapper.style.backgroundImage = "none";
}

async function getUsuario(id) {
  document.getElementById("tituloUsuario").innerHTML =
    "<b>Modificar Usuario</b>";
  document.getElementById("txt_nick").innerHTML = "<b>Nick</b>";
  document.getElementById("txt_nombre").innerHTML = "<b>Nombre(s)</b>";
  document.getElementById("txt_apellido").innerHTML = "<b>Apellido(s)</b>";
  document.getElementById("txt_direccion").innerHTML = "<b>Direccion</b>";
  document.getElementById("txt_telefono").innerHTML = "<b>Telefono</b>";
  document.getElementById("txt_estado").innerHTML = "<b>Estado civil</b>";
  document.getElementById("txt_afp").innerHTML = "<b>Establecimiento AFP</b>";
  document.getElementById("txt_sueldo").innerHTML = "<b>Sueldo</b>";
  document.getElementById("txt_aporte").innerHTML = "<b>Aporte a AFP</b>";
  document.getElementById("frmUsuario").reset();
  document.getElementById("id_usuario").value = id_usuario;
  document.getElementById("corre").hidden = true;
  document.getElementById("contraseñas").hidden = true;
  const url = `${BASE_URL}getUsuario/${id}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById("id_usuario").value = data.data.id_usuario;
      document.getElementById("run").value = data.data.run;
      document.getElementById("nick").value = data.data.nick;
      document.getElementById("nombre").value = data.data.nombre;
      document.getElementById("apellido").value = data.data.apellido;
      document.getElementById("direccion").value = data.data.direccion;
      document.getElementById("telefono").value = data.data.telefono;
      document.getElementById("estado_civil").value = data.data.estado_civil;
      document.getElementById("afp").value = data.data.afp;
      document.getElementById("sueldo").value = data.data.sueldo;
      document.getElementById("aporte").value = data.data.aporte;
      document.getElementById("correo").value = data.data.correo;
      document.getElementById("password").value = data.data.password;
      document.getElementById("repetir").value = data.data.password;
      $("#rol_id").val(data.data.rol_id).trigger("change");
      document.getElementById("imagen_anterior").value = data.data.foto;
      const wrapper = document.querySelector("#imagen");
      if (data.data.foto !== "default.png") {
        const imageUrl = `${BASE_URL}public/assets/img/usuarios/${data.data.foto}`;
        wrapper.style.backgroundImage = `url(${imageUrl})`;
      } else {
        wrapper.style.backgroundImage = `url(${BASE_URL}public/assets/img/usuarios/default.png)`;
      }

      $("#ModalUsuario").modal("show");
      $("#ModalUsuario").on("shown.bs.modal", () => {
        document.getElementById("run").focus();
      });
    }
  } catch (error) {
    console.log(error);
  }
}

function validateData(
  id_usuario,
  run,
  nick,
  nombre,
  apellido,
  direccion,
  telefono,
  estado_civil,
  afp,
  sueldo,
  aporte,
  correo,
  password,
  repetir,
  rol_id
) {
  if (
    !run.value &&
    !nick.value &&
    !nombre.value &&
    !apellido.value &&
    !direccion.value &&
    !telefono.value &&
    !estado_civil.value &&
    !afp.value &&
    !sueldo.value &&
    !aporte.value
  ) {
    run.focus();
    return toast(
      "Por favor, complete todos los campos obligatorios correctamente",
      "info"
    );
  }
  if (run.value === "") {
    toast("La cedula es requerida", "info");
    run.focus();
    return false;
  }
  if (nick.value === "") {
    toast("El nick es requerido", "info");
    nick.focus();
    return false;
  }
  if (nombre.value === "") {
    toast("El nombre es requerido", "info");
    nombre.focus();
    return false;
  }
  if (apellido.value === "") {
    toast("El apellido es requerido", "info");
    apellido.focus();
    return false;
  }
  if (direccion.value === "") {
    toast("La dirección es requerida", "info");
    direccion.focus();
    return false;
  }
  if (telefono.value === "") {
    toast("El telefono es requerido", "info");
    telefono.focus();
    return false;
  }
  if (estado_civil.value === "") {
    toast("El estado civil es requerido", "info");
    estado_civil.focus();
    return false;
  }
  if (afp.value === "") {
    toast("El establecimiento AFP es requerido", "info");
    afp.focus();
    return false;
  }
  if (sueldo.value === "") {
    toast("El sueldo es requerido", "info");
    sueldo.focus();
    return false;
  }
  if (aporte.value === "") {
    toast("El aporte es requerido", "info");
    aporte.focus();
    return false;
  }
  if (id_usuario.value === "" && !correo.value.trim()) {
    correo.focus();
    toast("El correo electronico es requerido", "info");
    return;
  }
  if (id_usuario.value === "" && !password.value.trim()) {
    password.focus();
    toast("La contraseña es requerida", "info");
    return;
  }
  if (id_usuario.value === "" && !repetir.value.trim()) {
    repetir.focus();
    toast("Repita la contraseña", "info");
    return;
  }
  if (!validarCorreo(correo.value.trim())) {
    correo.value = "";
    correo.focus();
    toast("El correo electrónico ingresado no es válido", "info");
    return;
  }
  if (correo.value.trim() === "") {
    toast("El correo electrónico es requerido", "info");
    correo.focus();
    return false;
  }
  if (
    id_usuario.value === "" &&
    password.value.trim() !== repetir.value.trim()
  ) {
    password.value = "";
    repetir.value = "";
    password.focus();
    toast("Las contraseñas no coinciden", "info");
    return;
  }
  if (
    rol_id.value === "" ||
    rol_id.value === null ||
    rol_id.value === 0 ||
    rol_id.value === undefined
  ) {
    toast("Seleccione un rol", "info");
    return;
  }
}

async function highUsuario(id) {
  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "¿Está seguro de activar el usuario ?",
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
    const url = `${BASE_URL}highUsuario/${id}`;
    try {
      const resp = await axios.get(url, config);
      const data = resp.data;
      if (data.estado === "ok" && data.codigo === 200) {
        toast("Usuario activado correctamente", "success");
        getUsuarios();
      }
    } catch (error) {
      resultado = error.response.data;
      if (resultado.codigo === 500 && resultado.estado === "error") {
        return toast("Error al activar el usuario, intente nuevamente", "info");
      }
    }
  }
}
