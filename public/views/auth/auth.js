document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("cod").style.display = 'none'
    const adorno = document.getElementById('adorno');
    const ador = document.getElementById('ador');

    function updateDisplay() {
        if (window.innerWidth <= 1200) {
            adorno.style.display = 'none';
            ador.style.display = 'none';
        } else {
            adorno.style.display = 'block';
            ador.style.display = 'block';
        }
    }
    updateDisplay();
    window.addEventListener('resize', updateDisplay);

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

async function login(e) {
    e.preventDefault();
    const correo = document.getElementById("correo").value;
    const password = document.getElementById("password").value;

    validate(correo, password)
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
            const { id_usuario, nombre, apellido, rol, correo } = token.data.token;
 
            localStorage.setItem("token", result.data.token);
            localStorage.setItem("id_usuario", id_usuario);
            localStorage.setItem("nombre", nombre);
            localStorage.setItem("apellido", apellido);
            if (rol === "Administrador") {
                const uptdate = `${BASE_URL}updateCodigo`;
                const resp = await axios.post(uptdate);
                const response = resp.data;
                if (response.estado === 'ok' && response.codigo === 201) {
                    const create = `${BASE_URL}createCodigo`;
                    const res = await axios.post(create);
                    const respon = res.data;

                    if (respon.estado === 'ok' && respon.codigo === 201) {
                        toast("Bienvenido " + nombre + " " + apellido, "success");
                        setTimeout(() => {
                            window.location.href = `${BASE_URL}home`;
                        }, 2000)
                    }
                }
            } else {
                document.getElementById("log").style.display = 'none'
                document.getElementById("cod").style.display = 'block'
                document.getElementById("cod1").focus();
            }
        } else {
            toast("Usuario o contraseña incorrecta", "warning");
        }

    } catch (e) {
        console.error(e);
    }
}
function desencriptarToken(token) {
    const base64Url = token.split(".")[1];
    const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
    const jsonPayload = decodeURIComponent(
        atob(base64)
            .split("")
            .map(function (c) {
                return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
            })
            .join("")
    );
    return JSON.parse(jsonPayload);
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

function validateEmail(corrreo) {
    const re =
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(corrreo).toLowerCase());
}

async function verificarCodigo(e) {
    e.preventDefault();
    const cod1 = document.getElementById("cod1").value;
    const cod2 = document.getElementById("cod2").value;
    const cod3 = document.getElementById("cod3").value;
    const cod4 = document.getElementById("cod4").value;

    const codigo = `${cod1}${cod2}${cod3}${cod4}`;
    const url = `${BASE_URL}validarCodigo/${codigo}`;
    const datos = {
        id: localStorage.getItem("id_usuario"),
        nombre: localStorage.getItem("nombre"),
        apellido: localStorage.getItem("apellido"),
        token: localStorage.getItem("token"),
    };
    try {
        const resp = await axios.get(url, {
            headers: {
                Authorization: `Bearer ${datos.token}`,
            },
        });
        const response = resp.data;
        if (response.estado === "ok" && response.codigo === 200) {
            if (response.data.estado === 0) {                
                toast("Bienvenido " + datos.nombre + " " + datos.apellido, "success");
                setTimeout(() => {
                    window.location.href = `${BASE_URL}home`;
                }, 2000);
            } else {
         
                const url2 = `${BASE_URL}createAsistencia`;
                const respu = await axios.post(url2);
                const respuesta = respu.data;
                if (respuesta.estado === "ok" && respuesta.codigo === 201) {
                    toast("Bienvenido " + datos.nombre + " " + datos.apellido, "success");
                    setTimeout(() => {
                        window.location.href = `${BASE_URL}home`;
                    }, 2000);
                }
            }

        } else {
             document.getElementById("cod1").value="";
             document.getElementById("cod2").value="";
             document.getElementById("cod3").value="";
             document.getElementById("cod4").value="";
             document.getElementById("cod1").focus();
            return toast("El código ingresado es incorrecto.", "warning");
        }

    } catch (e) {
        console.error(e);
        toast("Error al verificar el código. Intenta nuevamente.", "error");
    }
}

