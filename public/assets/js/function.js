const defaultThemeMode = "light";
let themeMode;
const MAX_PIEZAS = 50;
document.addEventListener("DOMContentLoaded", async () => {
  ajustes();
  window.addEventListener("resize", ajustes);
  verificarTemporizadorActivo();
  usuarioAvatar();

  const usuario = JSON.parse(localStorage.getItem("usuario"));
  if (usuario.rol === "Mesero" || usuario.rol === "Chica") {
    document.getElementById("btn_menu").hidden = true;
  }
  if (usuario.rol === "Mesero") {
    if (document.getElementById("btn_home")) {
      document.getElementById("btn_home").hidden = false;
    }
  }
  getCajas();
  if (
    usuario &&
    (usuario.rol === "Administrador" || usuario.rol === "Cajero")
  ) {
    getPedidosTotal();
  }
  getToken();
});

async function getToken() {
  const url = `${BASE_URL}tokenVerify`;
  const token = localStorage.getItem("token");

  if (!token) {
    localStorage.clear();
    window.location.href = `${BASE_URL}`;
    return;
  }

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  try {
    const resp = await axios.post(url, {}, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      if (data.data.newToken && data.data.tokenRefreshed === true) {
        localStorage.setItem("token", data.data.newToken);
        config.headers.Authorization = `Bearer ${data.data.newToken}`;
      }
    }
  } catch (error) {
    console.error(
      "Error al verificar el token:",
      error.response?.data || error.message
    );

    if (
      error.response?.status === 401 ||
      error.response?.status === 403 ||
      error.response?.status === 500
    ) {
      localStorage.clear();
      window.location.href = `${BASE_URL}`;
    }
  }
}

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

const TOKEN = localStorage.getItem("token");
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
  const id_usuario = JSON.parse(localStorage.getItem("usuario")).id_usuario;
  const url = `${BASE_URL}getUsuario/${id_usuario}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById(
        "avatar"
      ).innerHTML = `<img id="foto_perfil" alt="Pic" src="${BASE_URL}public/assets/img/usuarios/${data.data.foto}" />`;
      document.getElementById("correoo").innerHTML = data.data.correo;
    }
    return null;
  } catch (error) {
    console.error(error);
    return null;
  }
}

async function logaout(e) {
  e.preventDefault();
  const url = `${BASE_URL}logout`;
  try {
    const resp = await axios.get(url, config);
    const response = resp;
    if (response.status === 200) {
      toast("Cerrando sesión", "success");
      localStorage.clear();
      setTimeout(() => {
        window.location.href = `${BASE_URL}`;
      }, 2000);
    }
  } catch (e) {
    console.error(e);
  }
}

function primeraLetraMayuscula(cadena) {
  return cadena.charAt(0).toUpperCase() + cadena.slice(1);
}

async function getPedidosTotal() {
  const url = `${BASE_URL}getPedidos`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.codigo === 200 && data.estado === "ok") {
      const pedidoCount = data.data.length;
      const pedidoCountElement = document.getElementById("pedido-count");
      if (pedidoCount > 0) {
        pedidoCountElement.textContent = pedidoCount;
        pedidoCountElement.classList.remove("d-none");
      } else {
        pedidoCountElement.classList.add("d-none");
      }
      const pedidoLista = document.getElementById("pedido_");
      pedidoLista.innerHTML = "";
      for (let i = 0; i < data.data.length; i++) {
        const pedido = data.data[i];
        pedidoLista.innerHTML += `<div class="d-flex align-items-center bg-hover-lighten py-3 px-9">
                        <div class="symbol symbol-40px symbol-circle me-5">
                            <span class="symbol-label bg-light-success">
                                <span class="svg-icon svg-icon-success svg-icon-1">
                                    <i class="fa-solid fa-champagne-glasses"></i>
                                </span>
                            </span>
                        </div>
                        <div class="mb-1 pe-3 flex-grow-1" >
                            <a href="${BASE_URL}pedidos" class="fs-6 text-dark text-hover-primary fw-bold">
                            <div class="text-gray-400 text-dark text-hover-primary fw-bold fs-7">Cliente: ${pedido.nombre_c} ${pedido.apellido_c}</div>
                            <div class="text-gray-400 fw-bold fs-7">Acompañante: ${pedido.nombre_u} ${pedido.apellido_u}</div>
                            <div class="text-gray-400 fw-bold fs-7">Subtotal: $${pedido.subtotal}</div>
                            <div class="text-gray-400 fw-bold fs-7">Total: $${pedido.total}</div>
                            </a>                       
                        </div>
                    </div>`;
      }
    }
  } catch (error) {
    console.error(error);
  }
}

async function updatePiezaVenta(id_pieza) {
  const pieza = `${BASE_URL}updatePieza/${id_pieza}`;
  try {
    const resp_pieza = await axios.get(pieza, config);
    const data = resp_pieza.data;
    if (data.estado === "ok" && data.codigo === 201) {
      toast("Habitacion actualizada", "info");
    }
  } catch (e) {
    console.log(e);
  }
}

async function iniciarTemporizadorLocalStorage(tiempoEnMinutos, piezaId) {
  let intervalo;
  const tiempoInicial = localStorage.getItem(`temporizadorInicio_${piezaId}`);
  const tiempoRestanteAlmacenado = localStorage.getItem(
    `temporizadorRestante_${piezaId}`
  );
  let tiempoRestante;

  const url = `${BASE_URL}getPieza/${piezaId}`;

  const resp = await axios.get(url, config);
  const data = resp.data;
  if (data.estado === "ok" && data.codigo === 200) {
    nombrePieza = data.data.nombre;
  }
  if (tiempoInicial && tiempoRestanteAlmacenado) {
    const tiempoTranscurrido = Math.floor((Date.now() - tiempoInicial) / 1000);
    tiempoRestante = tiempoRestanteAlmacenado - tiempoTranscurrido;

    if (tiempoRestante <= 0) {
      clearInterval(intervalo);
      localStorage.removeItem(`temporizadorRestante_${piezaId}`);
      localStorage.removeItem(`temporizadorInicio_${piezaId}`);
      localStorage.removeItem(`piezaId_${piezaId}`);
      localStorage.setItem(`tiempoFinalizado_${piezaId}`, true);
      updatePiezaVenta(piezaId);
      Swal.fire({
        title: "Tiempo Finalizado",
        text: `El tiempo de uso de la pieza ${nombrePieza} ha terminado.`,
        icon: "info",
      });
      return;
    }
  } else {
    tiempoRestante = tiempoEnMinutos * 60;
    localStorage.setItem(`temporizadorInicio_${piezaId}`, Date.now());
    localStorage.setItem(`temporizadorRestante_${piezaId}`, tiempoRestante);
    localStorage.setItem(`piezaId_${piezaId}`, piezaId);
  }

  const actualizarTemporizador = () => {
    const tiempoTranscurrido = Math.floor(
      (Date.now() - localStorage.getItem(`temporizadorInicio_${piezaId}`)) /
        1000
    );
    const tiempoActualizado = tiempoRestante - tiempoTranscurrido;

    if (tiempoActualizado <= 0) {
      clearInterval(intervalo);
      localStorage.removeItem(`temporizadorRestante_${piezaId}`);
      localStorage.removeItem(`temporizadorInicio_${piezaId}`);
      localStorage.removeItem(`piezaId_${piezaId}`);

      localStorage.setItem(`tiempoFinalizado_${piezaId}`, true);
      updatePiezaVenta(piezaId);
      Swal.fire({
        title: "Tiempo Finalizado",
        text: `El tiempo de uso de la pieza ${nombrePieza} ha terminado.`,
        icon: "info",
      });
    } else {
      localStorage.setItem(
        `temporizadorRestante_${piezaId}`,
        tiempoActualizado
      );
    }
  };

  intervalo = setInterval(actualizarTemporizador, 1000);
  actualizarTemporizador();
}

function verificarTemporizadorActivo() {
  for (let piezaId = 1; piezaId <= MAX_PIEZAS; piezaId++) {
    const tiempoRestante = localStorage.getItem(
      `temporizadorRestante_${piezaId}`
    );
    if (tiempoRestante && tiempoRestante > 0) {
      iniciarTemporizadorLocalStorage(0, piezaId);
    }
  }
}

async function showTiempoTerminadoAlert(nombrePieza, mensajeAdicional = "") {
  await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: `El tiempo de servicio en ${nombrePieza} ha terminado.${mensajeAdicional}`,
    icon: "info",
    confirmButtonText: "Aceptar",
    customClass: {
      confirmButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
      popup: "swal2-dark",
      title: "swal2-title",
      htmlContainer: "swal2-html-container",
    },
    buttonsStyling: false,
    confirmButtonColor: "#dc3545",
    background: "var(--bs-body-bg)",
    color: "var(--bs-body-color)",
  });
}

function generarCodigoAleatorio(length) {
  const chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  return Array.from({ length }, () =>
    chars.charAt(Math.floor(Math.random() * chars.length))
  ).join("");
}

async function getCajas() {
  const url = `${BASE_URL}getCajas`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;

    if (data.estado !== "ok" && data.codigo !== 200) {
      if (document.getElementById("btn_nuevo_caja")) {
        document.getElementById("btn_nuevo_caja").hidden = false;
        toast("No se encontraron cajas registradas", "info");
      }

      if (document.getElementById("btn_nuevo_venta")) {
        document.getElementById("btn_nuevo_venta").hidden = true;
      }
      return;
    }

    if (data.estado === "ok" && data.codigo === 200) {
      const cajas = Array.isArray(data.data) ? data.data : [data.data];

      if (document.getElementById("btn_nuevo_venta")) {
        document.getElementById("btn_nuevo_venta").hidden = cajas.some(
          (caja) => caja.estado === 0
        );
      }
      if (document.getElementById("btn_nuevo_caja")) {
        document.getElementById("btn_nuevo_caja").hidden = cajas.some(
          (caja) => caja.estado === 1
        );
      }
      if (document.getElementById("tbCaja")) {
        tbCaja = $("#tbCaja").DataTable({
          data: cajas,
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
            {
              data: null,
              render: (data, type, row) =>
                moment(row.fecha_apertura).format("DD/MM/YYYY HH:mm"),
            },
            { data: "monto_apertura" },
            {
              data: null,
              render: (data, type, row) =>
                row.estado === 1
                  ? `<span class="badge badge-sm badge-primary">Abierta</span>`
                  : row.monto_cierre,
            },
            {
              data: null,
              render: (data, type, row) =>
                row.estado === 1
                  ? `<span class="badge badge-sm badge-primary">Abierta</span>`
                  : moment(row.fecha_cierre).format("DD/MM/YYYY HH:mm"),
            },
            {
              data: null,
              render: (data, type, row) => {
                if (row.estado === 1) {
                  return `
                  <button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_caja}" onclick="cerrarCaja('${row.id_caja}')">
                    <i class="fa-solid fa-store-slash"></i>
                  </button>`;
                }
                if (row.estado === 0) {
                  return `
                  <button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_caja}" onclick="verCaja('${row.id_caja}')">
                    <i class="fas fa-eye"></i>
                  </button>`;
                }
              },
            },
          ],
        });
      }
    }
  } catch (error) {
    result = error.response.data;
    if (result.codigo === 500 && result.estado === "error") {
      return toast("Error al obtener cajas, intente nuevamente", "warning");
    }
  }
}
