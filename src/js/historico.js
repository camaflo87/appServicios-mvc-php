(function () {
  const btnHistorico = document.querySelector("#btnHistorico");
  const selectGrupo = document.querySelector("#selectgrupo");
  const selectServicio = document.querySelector("#selectservicio");
  const fechaIni = document.querySelector("#ini");
  const fechaFin = document.querySelector("#fin");
  const usuario = document.querySelector("#usuario");

  let servicios = [];
  let idServicio;
  let idGrupo;

  if (btnHistorico) {
    btnHistorico.addEventListener("click", validarDatos);
    if (selectGrupo) {
      selectGrupo.addEventListener("change", obtenerGrupo);
    }
    selectServicio.addEventListener("change", obtenerServicio);

    function obtenerGrupo(e) {
      idGrupo = e.target.value;
    }

    function obtenerServicio() {
      idServicio = selectServicio.value;
    }

    function validarDatos(e) {
      if (usuario.value === "3") {
        if (
          selectGrupo.value &&
          selectServicio.value &&
          fechaIni.value &&
          fechaFin.value
        ) {
          consultaGlobal();
        } else {
          Swal.fire({
            icon: "error",
            title: "Error Selección",
            text: "Seleccione los valores de consulta",
            // footer: '<a href="#">Why do I have this issue?</a>'
          });
        }
      } else {
        if (selectServicio.value && fechaIni.value && fechaFin.value) {
          consultaLocal();
        } else {
          Swal.fire({
            icon: "error",
            title: "Error Selección",
            text: "Seleccione los valores de consulta",
            // footer: '<a href="#">Why do I have this issue?</a>'
          });
        }
      }
    }

    async function consultaGlobal() {
      const datos = new FormData();
      datos.append("ini", fechaIni.value);
      datos.append("fin", fechaFin.value);
      datos.append("grupoId", idGrupo);
      datos.append("servicioId", idServicio);

      try {
        const url = "/api/servicios_historico";
        const respuesta = await fetch(url, {
          method: "POST",
          body: datos,
        });

        const resultado = await respuesta.json();
        formatearServicios(resultado);
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Error Conexión",
          text: "No se tiene conexion con la pagina solicitada",
          // footer: '<a href="#">Why do I have this issue?</a>'
        });
      }
    }

    async function consultaLocal() {
      let id_grupo = document.querySelector("#userGrupo").value;
      const datos = new FormData();
      datos.append("ini", fechaIni.value);
      datos.append("fin", fechaFin.value);
      datos.append("localId", id_grupo);
      datos.append("servicioId", idServicio);

      try {
        const url = "/api/servicios_historico";
        const respuesta = await fetch(url, {
          method: "POST",
          body: datos,
        });

        const resultado = await respuesta.json();

        formatearServicios(resultado);
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Error Conexión",
          text: "No se tiene conexion con la pagina solicitada",
          // footer: '<a href="#">Why do I have this issue?</a>'
        });
      }
    }

    function formatearServicios(arrayServicios = []) {
      servicios = arrayServicios.map((servicio) => {
        return {
          funcionario: `${servicio.funcionario}`,
          fecha: `${servicio.fecha}`,
          lugar: `${servicio.lugar}`,
          observaciones: `${servicio.observaciones}`,
          semana: `${servicio.semana}`,
        };
      });

      filtrarServicios();
    }

    function filtrarServicios() {
      const serviciosTable = document.querySelector(".servicios__body");
      if (serviciosTable) {
        serviciosTable.innerHTML = "";
      }

      servicios.forEach((servicio) => {
        const filasHTML = document.createElement("TR");
        filasHTML.classList.add("servicios__listado");

        const colFuncionario = document.createElement("TD");
        colFuncionario.dataset.label = "Funcionario";
        colFuncionario.textContent = servicio.funcionario;

        const colFecha = document.createElement("TD");
        colFecha.dataset.label = "Fecha";
        colFecha.textContent = servicio.fecha;

        const colSemana = document.createElement("TD");
        colSemana.dataset.label = "Semana";
        colSemana.textContent = servicio.semana;

        const colLugar = document.createElement("TD");
        colLugar.dataset.label = "Lugar";
        colLugar.textContent = servicio.lugar;

        const colObservaciones = document.createElement("TD");
        colObservaciones.dataset.label = "Observaciones";
        colObservaciones.textContent = servicio.observaciones;

        // Agrupar etiquetas
        filasHTML.appendChild(colFuncionario);
        filasHTML.appendChild(colFecha);
        filasHTML.appendChild(colSemana);
        filasHTML.appendChild(colLugar);
        filasHTML.appendChild(colObservaciones);

        // Añadir al DOM
        serviciosTable.appendChild(filasHTML);
      });
    }
  }
})();
