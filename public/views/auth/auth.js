document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("cod").style.display = "none";
  const adorno = document.getElementById("adorno");
  const ador = document.getElementById("ador");

  function updateDisplay() {
    if (window.innerWidth <= 1200) {
      adorno.style.display = "none";
      ador.style.display = "none";
    } else {
      adorno.style.display = "block";
      ador.style.display = "block";
    }
  }
  updateDisplay();
  window.addEventListener("resize", updateDisplay);

  const correo = document.getElementById("correo");
  const password = document.getElementById("password");
  correo.focus();
  correo.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
      event.preventDefault();
      if (!correo.value) {
        toast("Ingrese su correo electrónico ", "info");
        correo.focus();
        return;
      }
      if (!validateEmail(correo.value)) {
        toast("Ingrese un correo electronico válido", "info");
        correo.focus();
        return;
      }
      password.focus();
    }
  });
  password.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
      event.preventDefault();
      if (!password.value) {
        toast("Ingrese su contraseña ", "info");
        password.focus();
        return;
      }
      login(event);
    }
  });

  const codFields = ["cod1", "cod2", "cod3", "cod4"];

  codFields.forEach((id, index) => {
    const inputField = document.getElementById(id);

    inputField.addEventListener("input", (event) => {
      const inputValue = event.target.value;

      if (!/^\d$/.test(inputValue)) {
        toast("Solo se permiten números", "error");
        event.target.value = "";
        return;
      }
      if (inputValue && index < codFields.length - 1) {
        document.getElementById(codFields[index + 1]).focus();
      } else {
        verificarCodigo(event);
      }
    });
  });
});

function toast(mensaje, tipoMensaje) {
  toastr.options = {
    progressBar: true,
    positionClass: "toast-top-center",
    preventDuplicates: true,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
  };

  toastr[tipoMensaje](mensaje);
}
function desencriptarToken(token) {
  const base64Url = token.split(".")[1];
  const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
  const jsonPayload = decodeURIComponent(
    atob(base64)
      .split("")
      .map((c) => {
        return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
      })
      .join("")
  );
  return JSON.parse(jsonPayload);
}

function validateEmail(corrreo) {
  const re =
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(corrreo).toLowerCase());
}
function validate(correo, password) {
  if (!correo && !password) {
    toast("Ingrese su usuario y contraseña", "info");
    document.getElementById("correo").focus();
    return;
  }
  if (!correo) {
    toast("Ingrese su correo electrónico ", "info");
    document.getElementById("correo").focus();
    return;
  }
  if (!validateEmail(correo)) {
    toast("Ingrese un correo electronico válido", "info");
    document.getElementById("correo").focus();
    return;
  }
  if (!password) {
    toast("Ingrese su contraseña ", "info");
    document.getElementById("password").focus();
    return;
  }
}

async function login(e) {
  e.preventDefault();
  const correo = document.getElementById("correo").value;
  const password = document.getElementById("password").value;

  validate(correo, password);
  const data = { correo, password };
  const url = `${BASE_URL}login`;

  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();

    if (result.estado === "ok" && result.codigo === 200) {
      const token = desencriptarToken(result.data.token);
      const { id_usuario, run, nombre, apellido, rol, correo, foto } =
        token.data.token;
      localStorage.setItem("token", result.data.token);
      const usuario = {
        id_usuario: id_usuario,
        nombre: nombre,
        apellido: apellido,
        rol: rol,
        correo: correo,
        run: run,
        foto: foto,
      };
      localStorage.setItem("usuario", JSON.stringify(usuario));

      if (rol === "Administrador" || rol === "Cajero") {
        iniciarActualizacionCodigo();
        toast(`Bienvenido ${usuario.nombre} ${usuario.apellido}`, "success");
        setTimeout(() => {
          window.location.href = `${BASE_URL}home`;
        }, 2000);
      } else {
        document.getElementById("log").style.display = "none";
        document.getElementById("cod").style.display = "block";
        document.getElementById("cod1").focus();
      }
    }
    if (response.status === 400) {
      return toast("El correo o la contraseña son incorrectos", "info");
    }

    if (response.status === 429) {
      const segundos = result.retry_after;
      return toast(
        `Demasiados intentos. Por favor espere ${segundos} segundos antes de intentar nuevamente.`,
        "info"
      );
    }
  } catch (e) {
    console.error(e);
    if (e.response.status === 500) {
      toast("Error al iniciar sesión. Intenta nuevamente.", "error");
    }
  }
}

async function verificarCodigo(e) {
  e.preventDefault();
  const cod1 = document.getElementById("cod1").value;
  const cod2 = document.getElementById("cod2").value;
  const cod3 = document.getElementById("cod3").value;
  const cod4 = document.getElementById("cod4").value;

  const codigo = `${cod1}${cod2}${cod3}${cod4}`;
  const url = `${BASE_URL}validarCodigo/${codigo}`;
  const token = localStorage.getItem("token");
  const usuario = JSON.parse(localStorage.getItem("usuario"));
  try {
    const resp = await axios.get(url, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    const response = resp.data;
    console.log(response);
    if (response.data === undefined) {
      document.getElementById("cod1").value = "";
      document.getElementById("cod2").value = "";
      document.getElementById("cod3").value = "";
      document.getElementById("cod4").value = "";
      document.getElementById("cod1").focus();
      return toast("El código ingresado es incorrecto.", "info");
    }
    if (response.estado === "ok" && response.codigo === 200) {
      if (response.data.estado === 1) {
        if (usuario.rol !== "Administrador") {
          try {
            const url2 = `${BASE_URL}createAsistencia/${usuario.id_usuario}`;
            const respu = await axios.get(url2, {
              headers: {
                Authorization: `Bearer ${token}`,
              },
            });
            const respuesta = respu.data;
            if (respuesta.estado === "ok" && respuesta.codigo === 201) {
              toast(
                `Bienvenido ${usuario.nombre} ${usuario.apellido}`,
                "success"
              );
              setTimeout(() => {
                window.location.href = `${BASE_URL}home`;
              }, 2000);
            }

            if (respuesta.estado === "ok" && respuesta.codigo === 200) {
              toast(
                `Bienvenido ${usuario.nombre} ${usuario.apellido}, ${respuesta.data}`,
                "info"
              );

              setTimeout(() => {
                window.location.href = `${BASE_URL}home`;
              }, 2000);
            }
          } catch (error) {
            console.error(error);
          }
        } else {
          toast(`Bienvenido ${usuario.nombre} ${usuario.apellido}`, "success");
          setTimeout(() => {
            window.location.href = `${BASE_URL}home`;
          }, 2000);
        }
      }
    }
  } catch (e) {
    console.error(e);
    document.getElementById("cod1").value = "";
    document.getElementById("cod2").value = "";
    document.getElementById("cod3").value = "";
    document.getElementById("cod4").value = "";
    document.getElementById("cod1").focus();
    return toast("Error al verificar el código. Intenta nuevamente.", "error");
  }
}

async function actualizarCodigo() {
  try {
    const updateUrl = `${BASE_URL}updateCodigo`;
    await axios.get(updateUrl);

    const createUrl = `${BASE_URL}createCodigo`;
    const response = await axios.get(createUrl);

    if (response.data.estado === "ok" && response.data.codigo === 201) {
      return true;
    }
  } catch (error) {
    console.error("Error al actualizar código:", error);
  }
}

function iniciarActualizacionCodigo() {
  actualizarCodigo();
  const intervalId = setInterval(actualizarCodigo, 60000);
  localStorage.setItem("codigoIntervalId", intervalId);
}
