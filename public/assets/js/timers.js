const TIMER_PREFIX = "globalTimer_";
const INFO_PREFIX = "timerInfo_";
const TIMER_INTERVAL = 1000;

async function updatePieza(id_pieza) {
  const pieza = `${BASE_URL}updatePieza/${id_pieza}`;
  try {
    const resp_pieza = await axios.get(pieza, config);
    const data = resp_pieza.data;

    if (data.estado === "ok" && data.codigo === 201) {
      if (getServicios()) {
        getServicios();
      }
    }
  } catch (e) {
    console.log(e);
  }
}
async function finalizarServicioGlobal(servicio) {
  const url = `${BASE_URL}updateServicio/${servicio.id_servicio}`;
  try {
    const resp = await axios.get(url, config);
    const data = resp.data;
    if (data.estado === "ok" && data.codigo === 201) {
      toast("Servicio finalizado correctamente", "success");
      const serviciosElement = document.getElementById("servicios");
      if (serviciosElement) {
        serviciosElement.innerHTML = "";
      }
      const modalCuenta = document.getElementById("ModalCuenta");
      if (modalCuenta) {
        $("#ModalCuenta").modal("hide");
      }
      if (servicio.id_pieza) {
        updatePieza(servicio.id_pieza);
      }
      if (typeof getServicios === "function") {
        getServicios();
      }
    }
  } catch (error) {
    console.error("Error al finalizar servicio:", error);
    toast("Error al finalizar el servicio", "error");
  }
}

class GlobalTimer {
  constructor() {
    this.timers = new Map();
    this.checkTimers = this.checkTimers.bind(this);
    this.restoreTimers();

    this.intervalId = setInterval(this.checkTimers, TIMER_INTERVAL);
  }

  restoreTimers() {
    const keys = Object.keys(localStorage);
    keys.forEach((key) => {
      if (key.startsWith(TIMER_PREFIX)) {
        const id = key.replace(TIMER_PREFIX, "");
        const endTime = parseInt(localStorage.getItem(key));
        const timerInfo = JSON.parse(
          localStorage.getItem(`${INFO_PREFIX}${id}`) || "{}"
        );

        if (Date.now() < endTime) {
          this.timers.set(id, {
            endTime,
            info: timerInfo,
            interval: setInterval(() => this.updateTimer(id), 1000),
          });
        } else {
          this.stopTimer(id, timerInfo);
        }
      }
    });
  }

  startTimer(id, minutes, info = {}) {
    if (this.timers.has(id)) {
      return;
    }
    const endTime = Date.now() + minutes * 60 * 1000;
    localStorage.setItem(`${TIMER_PREFIX}${id}`, endTime.toString());
    localStorage.setItem(`${INFO_PREFIX}${id}`, JSON.stringify(info));

    this.timers.set(id, {
      endTime,
      info,
      interval: setInterval(() => this.updateTimer(id), 1000),
    });
  }

  async stopTimer(id, info = null) {
    if (this.timers.has(id)) {
      clearInterval(this.timers.get(id).interval);
      this.timers.delete(id);
    }
    localStorage.removeItem(`${TIMER_PREFIX}${id}`);
    localStorage.removeItem(`${INFO_PREFIX}${id}`);

    if (info) {
      let mensajeAdicional = "";
      try {
        const cuenta = await getCuentaServicio(id);
        if (cuenta.servicio_id > 0) {
          mensajeAdicional = "\nEste servicio tiene una cuenta pendiente.";
        }
      } catch (error) {
        console.error("Error al verificar cuenta:", error);
      }

      await showTiempoTerminadoAlert(
        info.habitacion || "undefined",
        mensajeAdicional
      );
      try {
        await finalizarServicioGlobal({
          id_servicio: id,
          id_pieza: info.id_pieza,
          habitacion: info.habitacion,
        });
      } catch (error) {
        console.error("Error al finalizar servicio:", error);
      }
    }
  }

  updateTimer(id) {
    const endTime = parseInt(localStorage.getItem(`${TIMER_PREFIX}${id}`));
    if (!endTime) return;

    const remainingTime = Math.max(0, Math.ceil((endTime - Date.now()) / 1000));
    const timerInfo = JSON.parse(
      localStorage.getItem(`${INFO_PREFIX}${id}`) || "{}"
    );
    window.dispatchEvent(
      new CustomEvent("globalTimerUpdate", {
        detail: {
          id,
          remainingTime,
          info: timerInfo,
        },
      })
    );

    if (remainingTime <= 0) {
      this.stopTimer(id, timerInfo);
    }
  }

  getRemainingTime(id) {
    const endTime = localStorage.getItem(`${TIMER_PREFIX}${id}`);
    if (!endTime) return 0;
    return Math.max(0, Math.ceil((parseInt(endTime) - Date.now()) / 1000));
  }

  checkTimers() {
    const keys = Object.keys(localStorage);
    keys.forEach((key) => {
      if (key.startsWith(TIMER_PREFIX)) {
        const id = key.replace(TIMER_PREFIX, "");
        if (!this.timers.has(id)) {
          const endTime = parseInt(localStorage.getItem(key));
          const timerInfo = JSON.parse(
            localStorage.getItem(`${INFO_PREFIX}${id}`) || "{}"
          );
          if (Date.now() >= endTime) {
            this.stopTimer(id, timerInfo);
          } else {
            this.timers.set(id, {
              endTime,
              info: timerInfo,
              interval: setInterval(() => this.updateTimer(id), 1000),
            });
          }
        }
      }
    });
  }
}

// Crear instancia global
const globalTimer = new GlobalTimer();

// Funciones de utilidad para usar en otros archivos
function startGlobalTimer(id, minutes, info = {}) {
  globalTimer.startTimer(id, minutes, info);
}

function stopGlobalTimer(id) {
  globalTimer.stopTimer(id);
}

function getGlobalTimerRemaining(id) {
  return globalTimer.getRemainingTime(id);
}

async function getCuentaServicio(servicio_id) {
  const uri = `${BASE_URL}getCuentaServicio/${servicio_id}`;
  const respCuenta = await axios.get(uri, config);
  const dataCuenta = respCuenta.data;
  const cuenta = dataCuenta.data;
  if (dataCuenta.estado === "ok" && dataCuenta.codigo === 200) {
    console.log(cuenta);
    return cuenta;

  }
}
