document.addEventListener("DOMContentLoaded", () => {
  getCodigo();
  getComisionusuario();
});

async function getCodigo() {
  const url = `${BASE_URL}getCodigo`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById("codigo").innerHTML = data.data.codigo;
    }
  } catch (error) {
    console.log(error);
  }
}
async function getComisionusuario() {
  const url = `${BASE_URL}getComisionUsuario`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);
  } catch (error) {
    console.log(error);
  }
}
