(function () {
  let serviciosSelect = document.querySelector(".servicios__seleccion");
  let grupoSelect;
  let registros = [];

  if (serviciosSelect) {
    const idGrupoSelect = document.querySelector("#idgrupo");
    if (idGrupoSelect) {
      idGrupoSelect.addEventListener("change", listarFuncionarios);
    } else {
      grupoSelect = document.querySelector("#userGrupo");
      if (grupoSelect) {
        listarFuncionarios();
      }
    }

    async function listarFuncionarios(e) {
      const datos = new FormData();

      if (idGrupoSelect) {
        datos.append("id", e.target.value);
      } else {
        datos.append("id", grupoSelect.value);
      }

      try {
        const url = "/api/funcionarios";
        const respuesta = await fetch(url, {
          method: "POST",
          body: datos,
        });
        const resultado = await respuesta.json();

        formatearRegistos(resultado);
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Error Conexión",
          text: "No se tiene conexion con la pagina solicitada",
          // footer: '<a href="#">Why do I have this issue?</a>'
        });
      }
      agregarLista();
    }
  }

  function formatearRegistos(arrayRegistros = []) {
    registros = arrayRegistros.map((registro) => {
      return {
        funcionario: `${registro.id_grado.abreviacion} ${registro.nombre} ${registro.apellido}`,
        id: `${registro.id}`,
      };
    });

    agregarLista();
  }

  function agregarLista() {
    const userLista = document.querySelector("#idFuncionario");
    const select = document.querySelector("#seleccion");

    if (userLista) {
      userLista.innerHTML = "";
      let opcHtml = document.createElement("OPTION");
      opcHtml.value = "";
      opcHtml.textContent = "<--Selecione Funcionario->";
      userLista.appendChild(opcHtml);
    }

    registros.forEach((registro) => {
      const opcHtml = document.createElement("OPTION");
      opcHtml.value = registro.id;
      opcHtml.textContent = registro.funcionario;

      // Añadir al DOM
      userLista.appendChild(opcHtml);
    });
  }
})();
