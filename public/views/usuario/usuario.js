let tbUsuario;
document.addEventListener("DOMContentLoaded", () => {
    getUsuarios();
    getRoles();
    $("#rol_id").select2({
        placeholder: "Seleccionar Rol",
        dropdownParent: $("#ModalUsuario .modal-body"),
    });

    const input = document.getElementById("foto");
    if (input) {
        input.addEventListener("change", preview);
    }
    enterKey();
});

function enterKey() {
    const run = document.getElementById("run");
    const nombre = document.getElementById("nombre");
    const apellido = document.getElementById("apellido");
    const direccion = document.getElementById("direccion");
    const telefono = document.getElementById("telefono");
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
            document.getElementById("txt_apellido").innerHTML = "<b>Apellido(s)</b>";
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
            correo.setAttribute("placeholder", "");
            document.getElementById("txt_correo").innerHTML =
                "<b>Correo electronico</b>";
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
            document.getElementById("txt_contraseña").innerHTML = "<b>Contraseña</b>";
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
                            `<span class="badge badge-sm badge-primary">${meta.row + 1
                            }</span>`,
                    },
                    {
                        data: null,
                        render: (data, type, row) =>
                            `<a href="${BASE_URL}public/assets/img/usuarios/${row.foto}" target="_blank"><img src="${BASE_URL}public/assets/img/usuarios/${row.foto}" alt="Foto" style="width: 50px; height: 50px; border-radius: 40%;"></a>`,
                    },
                    { data: "run" },
                    { data: "nombre" },
                    { data: "apellido" },
                    { data: "direccion" },
                    { data: "telefono" },
                    { data: "rol" },
                    {
                        data: null,
                        render: (data, type, row) => `
                        <button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_usuario}" onclick="getUsuario('${row.id_usuario}')">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_usuario}" onclick="deleteUsuario('${row.id_usuario}')">
                          <i class="fas fa-trash"></i>
                        </button>`,
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
        title: "NuweSoft",
        text: "¿Está seguro de eliminar el usuario ?",
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
        const url = `${BASE_URL}deleteUsuario/${id}`;
        try {
            const resp = await axios.get(url, config);
            const data = resp.data;
            if (data.estado === "ok" && data.codigo === 200) {
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
    const nombre = document.getElementById("nombre");
    const apellido = document.getElementById("apellido");
    const direccion = document.getElementById("direccion");
    const telefono = document.getElementById("telefono");
    const correo = document.getElementById("correo");
    const password = document.getElementById("password");
    const repetir = document.getElementById("repetir");
    const rol_id = document.getElementById("rol_id");
    const fotoInput = document.getElementById("foto");
    const foto = fotoInput.files[0];
    const imagen_anterior = document.getElementById("imagen_anterior");
    validarDatos(
      id_usuario,
      run,
      nombre,
      apellido,
      direccion,
      telefono,
      correo,
      password,
      repetir,
      rol_id
    );
    const formData = new FormData();
    formData.append("id_usuario", id_usuario.value);
    formData.append("run", run.value);
    formData.append("nombre", nombre.value);
    formData.append("apellido", apellido.value);
    formData.append("direccion", direccion.value);
    formData.append("telefono", telefono.value);
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
      console.log(data);
      if (data.estado === "ok" && data.codigo === 201) {
        toast("Usuario registrado correctamente", "success");
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
    document.getElementById("txt_nombre").innerHTML = "<b>Nombre(s)</b>";
    document.getElementById("txt_apellido").innerHTML = "<b>Apellido(s)</b>";
    document.getElementById("txt_direccion").innerHTML = "<b>Direccion</b>";
    document.getElementById("txt_telefono").innerHTML = "<b>Telefono</b>";
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
        document.getElementById("nombre").value = data.data.nombre;
        document.getElementById("apellido").value = data.data.apellido;
        document.getElementById("direccion").value = data.data.direccion;
        document.getElementById("telefono").value = data.data.telefono;
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
  function validarDatos(
    id_usuario,
    run,
    nombre,
    apellido,
    direccion,
    telefono,
    correo,
    password,
    repetir,
    rol_id
  ) {
    if (
      !run.value &&
      !nombre.value &&
      !apellido.value &&
      !direccion.value &&
      !telefono.value
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
      rol_id.value == 0 ||
      rol_id.value == undefined
    ) {
      toast("Seleccione un rol", "info");
      return;
    }
  }