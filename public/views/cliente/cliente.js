let tbCliente;
document.addEventListener("DOMContentLoaded", () => {
    getClientes();
    enterKey();
});

async function getClientes() {
    const url = `${BASE_URL}getClientes`;
    try {
        const resp = await axios.get(url, config);
        const data = resp.data;
        if (data.estado === "ok" && data.codigo === 200) {
            tbCliente = $("#tbCliente").DataTable({
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
                            `<span class="badge badge-sm badge-primary">${meta.row + 1
                            }</span>`,
                    },
                    { data: "run" },
                    { data: "nombre" },
                    { data: "apellido" },
                    { data: "telefono" },
                    {
                        data: null,
                        render: (data, type, row) => `
                          <button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_cliente}" onclick="getCliente('${row.id_cliente}')">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn btn-outline-dark btn-sm hover-scale" data-id="${row.id_cliente}" onclick="deleteCliente('${row.id_cliente}')">
                            <i class="fas fa-trash"></i>
                          </button>`,
                    },
                ],
            });
        } else {
            toast("No se encontraron clientes registrados", "info");
        }
    } catch (error) {
        console.log(error);
    }
}

function Mcliente(e) {
    e.preventDefault();
    document.getElementById("id_cliente").value = "";
    document.getElementById("tituloCliente").innerHTML = "Nuevo cliente";
    document.getElementById("frmCliente").reset();

    $("#Modalcliente").modal("show");
    $("#Modalcliente").on("shown.bs.modal", () => {
        document.getElementById("run").focus();
    });
}

async function createCliente(e) {
    e.preventDefault();
    const id_cliente = document.getElementById("id_cliente").value;
    const run = document.getElementById("run").value;
    const nombre = document.getElementById("nombre_cl").value;
    const apellido = document.getElementById("apellido_cl").value;
    const telefono = document.getElementById("telefono_cl").value;

    if (!run) {
        return toast("El documento del cliente es obligatorio", "info");
    }
    if (!nombre) {
        return toast("El nombre del cliente es obligatorio", "info");
    }
    if (!apellido) {
        return toast("El apellido del cliente es obligatorio", "info");
    }
    if (!telefono) {
        return toast("El teléfono del cliente es obligatorio", "info");
    }

    try {
        const data = {
            run: run,
            nombre: nombre,
            apellido: apellido,
            telefono: telefono,
            id_cliente: id_cliente,
        };
        const url = `${BASE_URL}createCliente`;
        const resp = await axios.post(url, data, config);
        const result = resp.data;
        console.log(result);
        if (result.estado === "ok" && result.codigo === 201) {
            $("#Modalcliente").modal("hide");
            getClientes();
            return toast("Cliente registrado correctamente", "success");
        }
    } catch (error) {
        if (error.response) {
            const resultado = error.response.data;

            if (resultado.codigo === 409 && resultado.estado === "error") {
                return toast("El cliente ingresado ya existe", "info");
            }

            if (resultado.codigo === 500 && resultado.estado === "error") {
                return toast(
                    "Error al registrar el cliente, intente nuevamente",
                    "warning"
                );
            }
        } else {
            console.error(error);
            return toast("Error inesperado, por favor intente nuevamente", "error");
        }
    }
}

function enterKey() {
    const run = document.getElementById("run");
    const nombre = document.getElementById("nombre_cl");
    const apellido = document.getElementById("apellido_cl");
    const telefono = document.getElementById("telefono_cl");
    run.focus();
    run.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            if (run.value === "") {
                return toast("El documento del cliente es obligatorio", "info");
                run.focus();
                return;
            }
            nombre.setAttribute("placeholder", "");
            nombre.focus();
        }
    });

    nombre.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            if (nombre.value === "") {
                toast("El nombre del cliente es obligatorio", "info");
                nombre.focus();
                return;
            }
            nombre.value = capitalizarPalabras(nombre.value);
            apellido.focus();
        }
    });

    apellido.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            if (apellido.value === "") {
                toast("El apellido del cliente es obligatorio", "info");
                apellido.focus();
                return;
            }
            apellido.value = capitalizarPalabras(apellido.value);
            telefono.focus();
        }
    });

    telefono.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            if (telefono.value === "") {
                toast("El telefono del cliente es obligatorio", "info");
                telefono.focus();
                return;
            }
            createCliente(e);
        }
    });
}

async function getCliente(id) {
    const url = `${BASE_URL}getCliente/${id}`;
    try {
        const resp = await axios.get(url, config);
        const data = resp.data;
        if (data.estado === "ok" && data.codigo === 200) {
            document.getElementById("id_cliente").value = data.data.id_cliente;
            document.getElementById("run").value = data.data.run;
            document.getElementById("nombre_cl").value = data.data.nombre;
            document.getElementById("apellido_cl").value = data.data.apellido;
            document.getElementById("telefono_cl").value = data.data.telefono;
            document.getElementById("tituloCliente").innerHTML = "Editar cliente";
            $("#Modalcliente").modal("show");
        }
    } catch (error) {
        console.log(error);
    }
}

async function deleteCliente(id) {
    const result = await Swal.fire({
        title: "Las Muñecas de Ramón",
        text: "¿Está seguro de eliminar el cliente ?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        customClass: {
            confirmButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
            cancelButton: "btn btn-outline-dark btn-sm hover-scale rounded-pill",
            popup: "swal2-dark",
            title: "swal2-title",
            htmlContainer: "swal2-html-container"
          },
          buttonsStyling: false,
          confirmButtonColor: "#dc3545",
          background: "var(--bs-body-bg)",
          color: "var(--bs-body-color)",
    });
    if (result.isConfirmed) {
        const url = `${BASE_URL}deleteCliente/${id}`;
        try {
            const resp = await axios.get(url, config);
            const data = resp.data;
            if (data.estado === "ok" && data.codigo === 200) {
                toast("Cliente eliminado correctamente", "success");
                getClientes();
            }
        } catch (error) {
            resultado = error.response.data;
            if (resultado.codigo === 500 && resultado.estado === "error") {
                return toast(
                    "Error al eliminar el cliente, intente nuevamente",
                    "warning"
                );
            }
        }
    }
}
