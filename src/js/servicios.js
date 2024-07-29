(function () {
  const listGrupo = document.querySelector("#id_grupoSeguimiento");
  const radiobtn = document.querySelectorAll('input[name="nivel"]');
  const btnServicios = document.querySelector("#seguimiento");
  const usuario = document.querySelector("#userGrupo");

  let id_grupo;
  let opcRadio;

  if (btnServicios) {
    let servicios = [];
    let serviciosFiltrados = [];

    btnServicios.addEventListener("click", () => {
      obtenerFuncionarios();
    });

    if (listGrupo) {
      listGrupo.addEventListener("change", obtenerValor);
    }

    radiobtn.forEach((radio) => {
      radio.addEventListener("change", obtenerRadio);
    });

    function obtenerValor(e) {
      id_grupo = e.target.value;
    }

    function obtenerRadio(e) {
      opcRadio = e.target.value;
    }

    async function obtenerFuncionarios(e) {
      let url;
      let newArray = [];

      url = "/api/servicios";

      const respuesta = await fetch(url);
      const resultado = await respuesta.json();

      formatearFuncionarios(resultado);
    }

    function formatearFuncionarios(arrayServicios = []) {
      servicios = arrayServicios.map((servicio) => {
        return {
          funcionario: `${servicio.funcionario}`,
          servicio: `${servicio.servicio}`,
          fecha: `${servicio.fecha}`,
          dias: calcularFecha(servicio.fecha),
          lugar: `${servicio.lugar}`.trim(),
          observaciones: `${servicio.observaciones}`,
          grupo: `${servicio.id_grupo}`,
          categoria: `${servicio.categoria}`,
        };
      });

      filtrarFuncionarios(servicios);
    }

    function calcularFecha(fecha = "") {
      let now = new Date();
      fecha = new Date(fecha);

      let resta = now.getTime() - fecha.getTime();
      return Math.round(resta / (1000 * 60 * 60 * 24));
    }

    function filtrarFuncionarios(servicios) {
      if (!opcRadio) {
        opcRadio = "Todos";
      }

      if (listGrupo && radiobtn) {
        if (id_grupo && opcRadio) {
          serviciosFiltrados = servicios.filter((servicio) => {
            if (
              servicio.grupo === id_grupo &&
              servicio.categoria === opcRadio
            ) {
              return servicio;
            }

            if (servicio.grupo === id_grupo && opcRadio === "Todos") {
              return servicio;
            }
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error Selección",
            text: "No ha seleccionado un grupo",
            // footer: '<a href="#">Why do I have this issue?</a>'
          });
        }
      } else {
        if (opcRadio) {
          serviciosFiltrados = servicios.filter((servicio) => {
            if (
              servicio.grupo === usuario.value &&
              servicio.categoria === opcRadio
            ) {
              return servicio;
            }

            if (servicio.grupo === usuario.value && opcRadio === "Todos") {
              return servicio;
            }
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error Selección",
            text: "No ha seleccionado una categoria",
            // footer: '<a href="#">Why do I have this issue?</a>'
          });
        }
      }

      mostrarFuncionarios();
    }

    function mostrarFuncionarios() {
      const body = document.querySelector(".servicios__body");
      if (body) {
        body.innerHTML = "";
      }

      serviciosFiltrados.forEach((servicios) => {
        const filasHTML = document.createElement("TR");
        filasHTML.classList.add("usuarios__listado");

        const colfuncionario = document.createElement("TD");
        colfuncionario.dataset.label = "Funcionario";
        colfuncionario.textContent = servicios.funcionario;

        const colServicio = document.createElement("TD");
        colServicio.dataset.label = "Servicio";
        colServicio.textContent = servicios.servicio;

        const colFecha = document.createElement("TD");
        colFecha.dataset.label = "Fecha";
        colFecha.textContent = servicios.fecha;

        const colDias = document.createElement("TD");
        colDias.dataset.label = "Dias";
        colDias.textContent = servicios.dias;

        const colLugar = document.createElement("TD");
        colLugar.dataset.label = "Lugar";
        colLugar.textContent = servicios.lugar;

        const colObservaciones = document.createElement("TD");
        colObservaciones.dataset.label = "Observaciones";
        colObservaciones.textContent = servicios.observaciones;

        // Agrupar etiquetas
        filasHTML.appendChild(colfuncionario);
        filasHTML.appendChild(colServicio);
        filasHTML.appendChild(colFecha);
        filasHTML.appendChild(colDias);
        filasHTML.appendChild(colLugar);
        filasHTML.appendChild(colObservaciones);

        // Añadir al DOM
        body.appendChild(filasHTML);
      });
    }
  }
})();
