document.addEventListener("DOMContentLoaded", () => {
    getCodigo()
  
});

async function getCodigo() {
    const url = `${BASE_URL}getCodigo`;
    try {
      const resp = await axios.get(url, config);
      const data = resp.data;
      console.log(data);
      if (data.estado === "ok" && data.codigo === 200) {
        document.getElementById("codigo").innerHTML = data.data.codigo;
      }
    } catch (error) {
      console.log(error);
    }
  }