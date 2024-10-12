let tbContrato;
document.addEventListener("DOMContentLoaded", () => {
  getUsuarios();
  $("#usuario_id").select2({
    placeholder: "Seleccionar Usuario",
    dropdownParent: $("#ModalContrato .modal-body"),
  });

  const sueldo = document.getElementById("sueldo");
  const fonasa = document.getElementById("fonasa");

  sueldo.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (sueldo.value === "") {
        toast("El sueldo del contrato es requerido", "info");
        sueldo.focus();
        return;
      }
      if (sueldo.value < 0) {
        toast("El sueldo no puede ser negativo", "info");
        sueldo.focus();
        return;
      }
      sueldo.setAttribute("placeholder", "");

      fonasa.focus();
    }
  });
  fonasa.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      if (fonasa.value === "") {
        toast("El aporte a fonasa es requerido", "info");
        fonasa.focus();
        return;
      }
      if (fonasa.value < 0) {
        toast("El aporte a fonasa no puede ser negativo", "info");
        fonasa.focus();
        return;
      }
      fonasa.setAttribute("placeholder", "");
      createContrato(e);
    }
  });
});
function MContrato(e) {
  e.preventDefault();
  document.getElementById("id_contrato").value = "";
  document.getElementById("tituloContrato").innerHTML = "Nuevo Contrato";
  $("#ModalContrato").modal("show");
}
async function getUsuarios() {
  const url = `${BASE_URL}getUsuarios`;
  try {
    const response = await axios.get(url, config);
    const datos = response.data;
    console.log(datos);
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
