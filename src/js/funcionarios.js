(function () {
  const grupoSelect = document.querySelector("#id_grupoFuncionario");
  const funcionarioSelect = document.querySelector("#id_funcionario");
  let funcionariosTable;
  
  let id_grupo = "";
  let funcionarios = [];
  let servicios = [];

  if (grupoSelect) {
    grupoSelect.addEventListener("change", consultarFuncionario);
    funcionarioSelect.addEventListener("change", obtenerServicios);
    
    async function consultarFuncionario(e) {
      funcionariosTable = document.querySelector(".servicios__body");
      id_grupo = e.target.value;
      const datos = new FormData();
      datos.append("id", id_grupo);

      //Crea encabezado de tabla
      if(funcionariosTable){
        funcionariosTable.innerHTML = "";
      }
      let fila = document.createElement("TR");
      fila.classList.add("servicios__listado");
      let col = document.createElement("TD");
      col.setAttribute("colspan", 5);
      col.setAttribute("data-label", "---------------");
      col.textContent = "---------------";
      fila.appendChild(col);
      funcionariosTable.appendChild(fila);

      try {
        const url = "/api/funcionarios";

        const respuesta = await fetch(url, {
          method: "POST",
          body: datos,
        });

        const resultado = await respuesta.json();
        formatearFuncionarios(resultado);
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Error Conexión",
          text: "No se tiene conexion con la pagina solicitada",
          // footer: '<a href="#">Why do I have this issue?</a>'
        });
      }
    }
  } else {
    
    if (funcionarioSelect) {
      consultAlternativa();
      funcionarioSelect.addEventListener("change", obtenerServicios);
    }

    async function consultAlternativa() {
      funcionariosTable = document.querySelector(".servicios__body");
      id_grupo = document.querySelector("#userGrupo").value;
      const datos = new FormData();
      datos.append("id", id_grupo);
      if(funcionariosTable){
        funcionariosTable.innerHTML = "";
      }
      let fila = document.createElement("TR");
      fila.classList.add("servicios__listado");
      let col = document.createElement("TD");
      col.setAttribute("colspan", 5);
      col.setAttribute("data-label", "---------------");
      col.textContent = "---------------";
      fila.appendChild(col);
      if(funcionariosTable){
        funcionariosTable.appendChild(fila);
      }

      try {
        const url = "/api/funcionarios";

        const respuesta = await fetch(url, {
          method: "POST",
          body: datos,
        });
        const resultado = await respuesta.json();
        formatearFuncionarios(resultado);
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Error Conexión",
          text: "No se tiene conexion con la pagina solicitada",
          // footer: '<a href="#">Why do I have this issue?</a>'
        });
      }
    }
  }

  function formatearFuncionarios(arrayFuncionarios = []) {
    funcionarios = arrayFuncionarios.map((funcionario) => {
      return {
        funcionario: `${funcionario.id_grado.abreviacion} ${funcionario.nombre} ${funcionario.apellido}`,
        id: `${funcionario.id}`,
      };
    });

    cargarDatos();
  }

  function cargarDatos() {
    funcionarioSelect.innerHTML = "";
    let opc = document.createElement("OPTION");
    opc.setAttribute("disabled", "");
    opc.setAttribute("selected", "");
    opc.textContent = "<-Seleccione un funcionario->";
    funcionarioSelect.appendChild(opc);

    for (let index = 0; index < funcionarios.length; index++) {
      opc = document.createElement("OPTION");
      opc.textContent = funcionarios[index].funcionario;
      opc.value = funcionarios[index].id;
      funcionarioSelect.appendChild(opc);
    }
  }

  async function obtenerServicios(e) {
    const id = e.target.value;
    const datos = new FormData();
    datos.append("id", id);

    try {
      const url = "/api/servicios_funcionario";
      const respuesta = await fetch(url, {
        method: "POST",
        body: datos,
      });
      const resultado = await respuesta.json();
      
      formatearServicios(resultado);
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Error Conexión 1" + error,
        text: "No se tiene conexion con la pagina solicitada",
        // footer: '<a href="#">Why do I have this issue?</a>'
      });
    }
  }

  function formatearServicios(arrayServicios = []) {
    servicios = arrayServicios.map((servicio) => {
      return {
        servicio: `${servicio.id_servicio.servicio}`,
        fecha: `${servicio.fecha_servicio}`,
        lugar: `${servicio.lugar}`,
        observaciones: `${servicio.observaciones}`,
        id: `${servicio.id}`,
      };
    });

    filtrarServicios();
  }

  function calcularHoras(fecha = "") {
    let now = new Date();
    fecha = new Date(fecha);

    let resta = now.getTime() - fecha.getTime();

    return Math.round(resta / (1000 * 60 * 60 * 24));
  }

  function filtrarServicios() {
    
    if(funcionariosTable){
      funcionariosTable.innerHTML = "";
    }

    servicios.forEach((servicio) => {
      const filasHTML = document.createElement("TR");
      filasHTML.classList.add("servicios__listado");

      const colServicio = document.createElement("TD");
      colServicio.dataset.label = "Servicio";
      colServicio.textContent = servicio.servicio;

      const colFecha = document.createElement("TD");
      colFecha.dataset.label = "Fecha";
      colFecha.textContent = servicio.fecha;

      const colLugar = document.createElement("TD");
      colLugar.dataset.label = "Lugar";
      colLugar.textContent = servicio.lugar;

      const colObservaciones = document.createElement("TD");
      colObservaciones.dataset.label = "Observaciones";
      colObservaciones.textContent = servicio.observaciones;

      const colAcciones = document.createElement("TD");
      colAcciones.dataset.label = "Acciones";
      colAcciones.classList.add("servicios__acciones");
      const formEliminar = document.createElement('FORM');
      formEliminar.setAttribute('method','POST');
      formEliminar.setAttribute('action','/eliminar_servicio');
      const oculto = document.querySelector('INPUT');
      oculto.setAttribute('type','hidden');
      oculto.setAttribute('name','id');

      let linkMod = "";
      let linkElim = "";

      if (calcularHoras(servicio.fecha) > 2) {
        colAcciones.textContent = "No se puede modificar";
      } else {
        linkMod = document.createElement("A");
        linkMod.classList.add("servicios__modificar");
        linkMod.href = `/modificar_servicio?id=${servicio.id}`;
        linkMod.textContent = "Modificar";
        linkElim = document.createElement("A");
        linkElim.classList.add("servicios__eliminar");
        linkElim.href = `/eliminar_servicio?id=${servicio.id}`;
        linkElim.textContent = "Eliminar";
        colAcciones.appendChild(linkMod);
        colAcciones.appendChild(linkElim);
      }

      // Agrupar etiquetas
      filasHTML.appendChild(colServicio);
      filasHTML.appendChild(colFecha);
      filasHTML.appendChild(colLugar);
      filasHTML.appendChild(colObservaciones);
      filasHTML.appendChild(colAcciones);

      // Añadir al DOM
      funcionariosTable.appendChild(filasHTML);
    });
  }
})();
