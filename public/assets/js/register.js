async function getRoles() {
    const url = `${BASE_URL}getRoles`;
    try {
      const response = await axios.get(url, config);
      const datos = response.data;
      if (datos.estado === "ok" && datos.codigo === 200) {
        const select = document.getElementById("rol_id");
        for (let i = 0; i < datos.data.length; i++) {
          const rol = datos.data[i];
          const option = document.createElement("option");
          option.value = rol.id_rol;
          option.text = rol.nombre;
          select.appendChild(option);
        }
      }
    } catch (error) {
      console.log(error);
    }
  } 