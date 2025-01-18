let tbCaja;

function Mcaja(e) {
  e.preventDefault();
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
  document.getElementById("id_caja").value = "";
  document.getElementById("monto").value = "";
  $("#ModalApertura").modal("show");
  $("#ModalApertura").on("shown.bs.modal", () => {
    document.getElementById("monto").focus();
  });
}

async function createCaja(e) {
  e.preventDefault();
  const monto = document.getElementById("monto").value;
  const url = `${BASE_URL}createCaja`;
  if (monto === "") {
    return toast("Ingrese el monto de apertura", "info");
  }
  if (monto < 0) {
    return toast("El monto debe ser mayor a 0", "info");
  }
  const datos = {
    monto_apertura: monto,
  };
  try {
    const response = await axios.post(url, datos, config);
    const data = response.data;
    if (data.estado === "ok" && data.codigo === 201) {
      $("#ModalApertura").modal("hide");
      getCajas();
      return toast("Caja abierta correctamente", "success");
    }
  } catch (error) {
    result = error.response.data;
    console.log(result)
    if (result.codigo === 500 && result.estado === "error") {
      return toast("Error al abrir la caja", "error");
    }
  }
}

async function cerrarCaja(id) {
  const result = await Swal.fire({
    title: "Las Muñecas de Ramón",
    text: "¿Está seguro de cerrar la caja?",
    icon: "info",
    showCancelButton: true,
    confirmButtonText: "Si, cerrar",
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
    const url = `${BASE_URL}cerrarCaja/${id}`;
    try {
      const resp = await axios.get(url, config);
      const data = resp.data;
      if (data.estado === "ok" && data.codigo === 201) {
        getCajas();
        return toast("Caja cerrada correctamente", "success");
      }
    } catch (error) {
      result = error.response.data;
      if (result.codigo === 500 && result.estado === "error") {
        return toast("Error al cerrar la caja, intente nuevamente", "warning");
      }
    }
  }
}
async function verCaja(id) {
  console.log(id);
}
