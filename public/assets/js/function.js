const defaultThemeMode = "light";
let themeMode;
if (document.documentElement) {
  if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
  } else {
    if (localStorage.getItem("data-bs-theme") !== null) {
      themeMode = localStorage.getItem("data-bs-theme");
    } else {
      themeMode = defaultThemeMode;
    }
  }
  if (themeMode === "system") {
    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches
      ? "dark"
      : "light";
  }
  document.documentElement.setAttribute("data-bs-theme", themeMode);
}
const DISPLAY_LENGTH = 5;
const config = {
  headers: {
    Authorization: `Bearer ${TOKEN}`,
  },
};
const LENGUAJE = {
  sProcessing: "Procesando...",
  sLengthMenu: "Listar _MENU_ registros",
  sZeroRecords: "No se encontraron resultados",
  sEmptyTable: "Ningún dato disponible en esta tabla",
  sInfo: "Listando _END_ de _TOTAL_",
  sInfoEmpty: "Listando 0 de 0 ",
  sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
  sInfoPostFix: "",
  sSearch: "Buscar:",
  sUrl: "",
  sInfoThousands: ",",
  sLoadingRecords: "Cargando...",
  oPaginate: {
    sFirst: "Primero",
    sLast: "Último",
    sNext: ">",
    sPrevious: "<",
  },
  oAria: {
    sSortAscending: ": Activar para ordenar la columna de manera ascendente",
    sSortDescending: ": Activar para ordenar la columna de manera descendente",
  },
};
function capitalizarPalabras(texto) {
  return texto
    .split(" ")
    .map((word) => {
      return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    })
    .join(" ");
}
function toast(mensaje, tipoMensaje) {
  toastr.options = {
    closeButton: false,
    debug: false,
    newestOnTop: false,
    progressBar: true,
    positionClass: "toast-top-center",
    preventDuplicates: true,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
  };

  toastr[tipoMensaje](mensaje);
}
function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}
const boton = document.getElementById("mod");
const modo_movil = document.querySelectorAll(".mobile-hide");
function ajustes() {
  if (boton) {
    if (window.innerWidth >= 900) {
      boton.style.display = "none";
    } else {
      boton.style.display = "block";
    }
  }
  for (let i = 0; i < modo_movil.length; i++) {
    if (window.innerWidth < 900) {
      modo_movil[i].style.display = "none";
    } else {
      modo_movil[i].style.display = "block";
    }
  }
}
ajustes();
window.addEventListener("resize", () => {
  ajustes();
});
function mostrarPassword(idInput, idIcono) {
  const tipoInput = document.getElementById(idInput).getAttribute("type");
  const icono = document.getElementById(idIcono);

  if (tipoInput === "password") {
    document.getElementById(idInput).setAttribute("type", "text");
    icono.classList.remove("fa-eye");
    icono.classList.add("fa-eye-slash");
  } else {
    document.getElementById(idInput).setAttribute("type", "password");
    icono.classList.remove("fa-eye-slash");
    icono.classList.add("fa-eye");
  }
}

function preview(event) {
  const input = event.target;
  if (!input.files || !input.files[0]) {
    console.error("No se ha seleccionado ningún archivo");
    return;
  }

  const wrapper = document.getElementById("imagen");
  const file = input.files[0];
  const reader = new FileReader();
  reader.onload = () => {
    wrapper.style.backgroundImage = `url(${reader.result})`;
    foto = file.name;
  };

  reader.readAsDataURL(file);
}
function deleteImg(button) {
  const input = button
    .closest(".image-input")
    .querySelector('input[type="file"]');
  input.value = "";
  preview({ target: input });
}
function validarCorreo(correo) {
  const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  return regex.test(correo);
}
async function usuarioAvatar() {
  id_usuario = localStorage.getItem("id_usuario");
  const url = `${BASE_URL}getUsuario/${id_usuario}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById('avatar').innerHTML = `<img id="foto_perfil" alt="Pic" src="${BASE_URL}public/assets/img/usuarios/${data.data.foto}" />`;
      document.getElementById('correoo').innerHTML = data.data.correo;
    }
    return null;
  } catch (error) {
    console.error(error);
    return null;
  }
}
usuarioAvatar();
async function logaout(e) {
  e.preventDefault();
  const url = `${BASE_URL}logout`;
  try {
    const resp = await axios.get(url, config);
    const response = resp;
    if (response.status === 200) {
      toast("Cerrando sesión", "success");
      setTimeout(() => {
        window.location.href = `${BASE_URL}`;
      }, 2000);
    }
  } catch (e) {
    console.log(e);
  }
}
function primeraLetraMayuscula(cadena) {
  return cadena.charAt(0).toUpperCase() + cadena.slice(1);
}
