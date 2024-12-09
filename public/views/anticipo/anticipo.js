let tbAnticipo;
document.addEventListener("DOMContentLoaded", () => {
  getUsuarios();
  getAnticipos();
  $("#usuario_id").select2({
    placeholder: "Seleccionar Usuario",
    dropdownParent: $("#ModalAnticipo .modal-body"),
  });

  const monto = document.getElementById("monto");

  monto.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (monto.value === "") {
        toast("El monto es requerido", "info");
        monto.focus();
        return;
      }
      if (monto.value < 0) {
        toast("El monto no puede ser negativo", "info");
        monto.focus();
        return;
      }
      createAnticipo(event);
    }
  });
});

function MAnticipo(e) {
  e.preventDefault();
  document.getElementById("id_anticipo").value = "";
  document.getElementById("tituloAnticipo").innerHTML = "Nuevo Anticipo";
 document.getElementById("monto").value="";

  function actualizarFechaHora() {
    const fecha = new Date();
    const dia = String(fecha.getDate()).padStart(2, "0");
    const mes = String(fecha.getMonth() + 1).padStart(2, "0");
    const anio = fecha.getFullYear();
    const horas = String(fecha.getHours()).padStart(2, "0");
    const minutos = String(fecha.getMinutes()).padStart(2, "0");
    const segundos = String(fecha.getSeconds()).padStart(2, "0");
    const fechaFormateada = `${dia}/${mes}/${anio} ${horas}:${minutos}:${segundos}`;
    document.getElementById("fecha").value = fechaFormateada;
  }

  actualizarFechaHora();

  setInterval(actualizarFechaHora, 1000);

  $("#ModalAnticipo").modal("show");
}

async function getUsuarios() {
  const url = `${BASE_URL}getUsuarios`;
  try {
    const response = await axios.get(url, config);
    const datos = response.data;
    if (datos.estado === "ok" && datos.codigo === 200) {
      const select = document.getElementById("usuario_id");
      const usuariosFiltrados = datos.data.filter(
        (usuario) => usuario.rol_id !== 1
      );
      for (const usuario of usuariosFiltrados) {
        const option = document.createElement("option");
        option.value = usuario.id_usuario;
        option.text = `${usuario.nombre} ${usuario.apellido}`;
        select.appendChild(option);
      }
    }
  } catch (error) {
    console.log(error);
  }
}
async function createAnticipo(e) {
  e.preventDefault();
  const usuario_id = document.getElementById("usuario_id").value;
  const monto = document.getElementById("monto").value;
  const url = `${BASE_URL}createAnticipo`;
  if (monto === "") {
    toast("El monto es requerido", "info");
    monto.focus();
    return;
  }
  if (monto <= 0) {
    toast("El monto no puede ser cero o negativo", "info");
    monto.focus();
    return;
  }
  const data = {
    usuario_id,
    monto,
  };
  try {
    const response = await axios.post(url, data, config);
    const datos = response.data;
    if (datos.estado === "ok" && datos.codigo === 201) {
      toast("Anticipo registrado correctamente", "info");
      $("#ModalAnticipo").modal("hide");
      getAnticipos();
    }
  } catch (error) {
    resultado = error.response.data;
    if (resultado.codigo === 500 && resultado.estado === "error") {
      return toast(
        "Ocurrio un error al registrar el anticipo, Intentelo mas tarde ",
        "info"
      );
    }
    console.log(error);
  }
}

async function getAnticipos() {
  const url = `${BASE_URL}getAnticipos`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      tbAnticipo = $("#tbAnticipo").DataTable({
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
          { data: "apellido" },
          { data: "monto" },
          {
            data: null,
            render: (data, type, row) =>
              moment(row.fecha_crea).format("DD/MM/YYYY hh:mm"),
          },
          {
            data: null,
            render: (data, type, row) =>
              row.estado === 1
                ? `<span class="badge badge-sm badge-danger">Por cobrar</span>`
                : `<span class="badge badge-sm badge-success">Pagado</span>`,
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

