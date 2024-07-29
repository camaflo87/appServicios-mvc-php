// (function () {
let resultado = [];
const btnGenerales = document.querySelector(".formaciones__allSeccional");

if (btnGenerales) {
  btnGenerales.addEventListener("click", cargarDatos);
}

async function cargarDatos(e) {
  try {
    const url = "/api/formacion_seccional";

    const respuesta = await fetch(url);
    resultado = await respuesta.json();

    generarSeccional();
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error Conexión",
      text: "No se tiene conexion con la pagina solicitada",
      // footer: '<a href="#">Why do I have this issue?</a>'
    });
  }
}

function generarSeccional() {
  const bloqueFormaciones = document.querySelector(".formaciones__generales");

  // if (bloqueFormaciones) {
  bloqueFormaciones.innerHTML = "";
  // }

  resultado.forEach((dato) => {
    //Inicio foreach
    if (dato.personal > 0) {
      // bloque contenido general
      const bloque = document.createElement("DIV");
      bloque.classList.add("formaciones__bloques");

      // sub-bloque Grupo ---------------------------
      const subloqueGrupo = document.createElement("DIV");
      subloqueGrupo.classList.add("formaciones__grupos");

      // añadir el nombre del grupo al "sub-bloque Grupo"
      const tituloGrupo = document.createElement("H2");
      tituloGrupo.classList.add("formaciones__tituloGrupo");
      tituloGrupo.textContent = dato.grupo;
      subloqueGrupo.appendChild(tituloGrupo);

      // sub-bloque Parte General ------------------------------
      let subloqueGeneral = document.createElement("DIV");
      subloqueGeneral.classList.add("formaciones__general");

      // sub-bloque Parte General - Subtitulo
      let parteGeneral = document.createElement("H3");
      parteGeneral.classList.add("formaciones__subtitulos");
      parteGeneral.textContent = "PARTE GENERAL";
      subloqueGeneral.appendChild(parteGeneral);

      // sub-bloque Parte General - bloque funcionarios
      let funcionariosGeneral = document.createElement("DIV");
      funcionariosGeneral.classList.add("formaciones__subBloques");

      let labFuncionarios = document.createElement("LABEL");
      labFuncionarios.textContent = "Funcionarios";

      let inputFuncionarios = document.createElement("INPUT");
      inputFuncionarios.value = `${dato.personal}`;

      funcionariosGeneral.appendChild(labFuncionarios);
      funcionariosGeneral.appendChild(inputFuncionarios);

      // sub-bloque Parte General - bloque numerico
      let numericoGeneral = document.createElement("DIV");
      numericoGeneral.classList.add("formaciones__subBloques");

      let labNumerico = document.createElement("LABEL");
      labNumerico.textContent = "Numerico";

      let inputNumerico = document.createElement("INPUT");
      inputNumerico.value = dato.numerico;

      numericoGeneral.appendChild(labNumerico);
      numericoGeneral.appendChild(inputNumerico);

      subloqueGeneral.appendChild(funcionariosGeneral);
      subloqueGeneral.appendChild(numericoGeneral);

      bloque.appendChild(subloqueGrupo);
      bloque.appendChild(subloqueGeneral);

      // // sub-bloque Parte Formación ------------------------------
      // subloqueGeneral = document.createElement("DIV");
      // subloqueGeneral.classList.add("formaciones__general");

      // // sub-bloque Parte General - Subtitulo
      // parteGeneral = document.createElement("H3");
      // parteGeneral.classList.add("formaciones__subtitulos");
      // parteGeneral.textContent = "FORMACIÓN";
      // subloqueGeneral.appendChild(parteGeneral);

      // // sub-bloque Parte General - bloque funcionarios
      // funcionariosGeneral = document.createElement("DIV");
      // funcionariosGeneral.classList.add("formaciones__subBloques");

      // labFuncionarios = document.createElement("LABEL");
      // labFuncionarios.textContent = "Funcionarios";

      // inputFuncionarios = document.createElement("INPUT");
      // inputFuncionarios.value = `${dato.novedad[0].personal}`;

      // funcionariosGeneral.appendChild(labFuncionarios);
      // funcionariosGeneral.appendChild(inputFuncionarios);

      // // sub-bloque Parte General - bloque numerico
      // numericoGeneral = document.createElement("DIV");
      // numericoGeneral.classList.add("formaciones__subBloques");

      // labNumerico = document.createElement("LABEL");
      // labNumerico.textContent = "Numerico";

      // inputNumerico = document.createElement("INPUT");
      // inputNumerico.value = `${dato.novedad[0].numerico}`;

      // numericoGeneral.appendChild(labNumerico);
      // numericoGeneral.appendChild(inputNumerico);

      // subloqueGeneral.appendChild(funcionariosGeneral);
      // subloqueGeneral.appendChild(numericoGeneral);

      // bloque.appendChild(subloqueGeneral);

      // sub-bloque Parte Novedades ------------------------------
      subloqueGeneral = document.createElement("DIV");
      subloqueGeneral.classList.add("formaciones__general");

      // sub-bloque Parte General - Subtitulo
      parteGeneral = document.createElement("H3");
      parteGeneral.classList.add("formaciones__subtitulos");
      parteGeneral.textContent = "NOVEDADES";
      subloqueGeneral.appendChild(parteGeneral);

      // sub-bloque Parte General - bloque novedades

      dato.novedad.forEach((nov) => {
        funcionariosGeneral = document.createElement("DIV");
        funcionariosGeneral.classList.add("formaciones__subBloques");

        labFuncionarios = document.createElement("LABEL");
        labFuncionarios.textContent = nov.novedad;

        inputFuncionarios = document.createElement("INPUT");
        inputFuncionarios.value = `${nov.personal} - ${nov.numerico} `;

        funcionariosGeneral.appendChild(labFuncionarios);
        funcionariosGeneral.appendChild(inputFuncionarios);

        subloqueGeneral.appendChild(funcionariosGeneral);
      });

      bloque.appendChild(subloqueGeneral);

      // Añadir subloques al bloque principal
      bloqueFormaciones.appendChild(bloque);
    }
  }); // Final foreach
}
// })();
