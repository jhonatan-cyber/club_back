document.addEventListener("DOMContentLoaded", () => {
  const usuario = JSON.parse(localStorage.getItem("usuario"));

  if (usuario.rol === "Administrador") {
    setTimeout(() => {
      getCodigo();
      setInterval(getCodigo, 60000);
    }, 1000);
  }
});

async function getCodigo() {
  const url = `${BASE_URL}getCodigo`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
<<<<<<< HEAD
=======
    console.log(data.data);
>>>>>>> 21c0d6e (arreglos)
    if (data.estado === "ok" && data.codigo === 200) {
      console.log(data.data.codigo);
      document.getElementById("codigo").innerHTML = data.data.codigo;
    }
  } catch (error) {
    console.log("Error al obtener código:", error);
  }
}
