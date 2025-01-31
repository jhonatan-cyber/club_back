const user = JSON.parse(localStorage.getItem("usuario"));

document.addEventListener("DOMContentLoaded", () => {});

async function perfil() {
  const user = JSON.parse(localStorage.getItem("usuario"));
  const url = `${BASE_URL}getUsuario/${user.id_usuario}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    console.log(data);
    if (data.estado === "ok" && data.codigo === 200) {
      document.getElementById(
        "imgperfil"
      ).innerHTML = `<img alt="imagen" src="${BASE_URL}public/assets/img/usuarios/${data.data.foto}" /><div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>`;
      document.getElementById(
        "nomapes"
      ).innerHTML = `${data.data.nombre} ${data.data.apellido}`;
      document.getElementById("eci").innerHTML = `RUN: ${data.data.run}`;
      document.getElementById("mail").innerHTML = data.data.correo;
      document.getElementById("dir").innerHTML = data.data.direccion;
      document.getElementById("tel").innerHTML = data.data.telefono;
      document.getElementById("id_usuario_perfil").value = data.data.id_usuario;
      document.getElementById("rol_id_perfil").value = data.data.rol_id;

      /*       
       
  
  
      
         document.getElementById("cord").innerHTML = data.data.correo; */
    }
  } catch (error) {
    console.error(error.response.data);
  }
}
