const hamburg = document.querySelector(".hamburger");
const navForm = document.querySelector(".formaciones__navegacion");
const selectGrupo = document.querySelector(".formaciones__grupo");
const selectOpc = document.querySelector(".formaciones__opc");
const buttonGeneral = document.querySelector("#btnGeneral");
const buttonTurnoA = document.querySelector("#btnTurnoA");
const buttonTurnoB = document.querySelector("#btnTurnoB");
const filtroParte = document.querySelector(".formaciones__sidebar");
const estado = document.querySelector("#estado");
const width = window.innerWidth;

let btnGeneral = true;
let btnTurnoA;
let btnTurnoB;

let subNovedades = document.querySelector(".formaciones__subtitulo");
let btnconsulta;
let grupo;
let resultado;
let listNov;

if (hamburg) {
  hamburg.addEventListener("click", function () {
    hamburg.classList.toggle("is-active");
    navForm.classList.toggle("formaciones__mostrar");
    selectOpc.classList.toggle("formaciones__mostrar");
    if (selectGrupo) {
      selectGrupo.classList.toggle("formaciones__mostrar");
    }
  });

  window.addEventListener("resize", function () {
    const width = window.innerWidth;
    if (width > 375) {
      navForm.classList.remove("formaciones__mostrar");
      selectOpc.classList.remove("formaciones__mostrar");
      if (selectGrupo) {
        selectGrupo.classList.remove("formaciones__mostrar");
      }
      hamburg.classList.remove("is-active");
    }
  });
}

// Formación general
if (buttonGeneral) {
  buttonGeneral.addEventListener("click", function () {
    btnGeneral = true;
    btnTurnoA = false;
    btnTurnoB = false;
    btnconsulta = "General";
    grupo = document.querySelector("#id_grupoSeguimiento");

    if (grupo) {
      // Usuario global
      if (grupo.value) {
        formacionGeneral(grupo.value);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error Selección",
          text: "Selecione un grupo, por favor...",
          // footer: '<a href="#">Why do I have this issue?</a>'
        });
      }
    } else {
      // Otros usuarios
      grupo = document.querySelector("#userGrupo");
      formacionGeneral(grupo.value);
    }
  });
}

// Formación Turno A
if (buttonTurnoA) {
  buttonTurnoA.addEventListener("click", function () {
    btnGeneral = false;
    btnTurnoA = true;
    btnTurnoB = false;
    btnconsulta = "TurnoA";
    grupo = document.querySelector("#id_grupoSeguimiento");

    if (grupo) {
      // Usuario global
      if (grupo.value) {
        formacionTurno(grupo.value, "A");
      } else {
        Swal.fire({
          icon: "error",
          title: "Error Selección",
          text: "Selecione un grupo, por favor...",
          // footer: '<a href="#">Why do I have this issue?</a>'
        });
      }
    } else {
      // Otros usuarios
      grupo = document.querySelector("#userGrupo");
      formacionTurno(grupo.value, "A");
    }
  });
}

// Formación Turno B
if (buttonTurnoB) {
  buttonTurnoB.addEventListener("click", function () {
    btnGeneral = false;
    btnTurnoA = false;
    btnTurnoB = true;
    btnconsulta = "TurnoB";
    grupo = document.querySelector("#id_grupoSeguimiento");

    if (grupo) {
      // Usuario global
      if (grupo.value) {
        formacionTurno(grupo.value, "B");
      } else {
        Swal.fire({
          icon: "error",
          title: "Error Selección",
          text: "Selecione un grupo, por favor...",
          // footer: '<a href="#">Why do I have this issue?</a>'
        });
      }
    } else {
      // Otros usuarios
      grupo = document.querySelector("#userGrupo");
      formacionTurno(grupo.value, "B");
    }
  });
}

async function formacionGeneral(idgrupo = "") {
  const datos = new FormData();
  datos.append("id", idgrupo);
  try {
    const url = "/api/formacion_general";

    const respuesta = await fetch(url, {
      method: "POST",
      body: datos,
    });

    resultado = await respuesta.json();
    await novedades();
    cargarDom();
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error Conexión",
      text: "No se tiene conexion con la pagina solicitada",
      // footer: '<a href="#">Why do I have this issue?</a>'
    });
  }
}

async function formacionTurno(idgrupo = "", turno = "") {
  const datos = new FormData();
  datos.append("id", idgrupo);
  datos.append("turno", turno);
  try {
    const url = "/api/formacion_turnos";

    const respuesta = await fetch(url, {
      method: "POST",
      body: datos,
    });

    resultado = await respuesta.json();

    await novedades();
    cargarDom();
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error Conexión",
      text: "No se tiene conexion con la pagina solicitada",
      // footer: '<a href="#">Why do I have this issue?</a>'
    });
  }
}

async function novedades() {
  try {
    const url = "/api/novedades";

    const respuesta = await fetch(url);
    listNov = await respuesta.json();
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error Conexión",
      text: "No se tiene conexion con la pagina solicitada",
      // footer: '<a href="#">Why do I have this issue?</a>'
    });
  }
}

function cargarDom() {
  if (filtroParte) {
    filtroParte.innerHTML = "";
  }

  // Bloque funcionarios
  const divFuncionario = document.createElement("DIV");
  divFuncionario.classList.add("formaciones__funcionario");

  const subtituloFuncionario = document.createElement("H2");
  subtituloFuncionario.classList.add("formaciones__subtitulo");
  subtituloFuncionario.textContent = "Personal";

  divFuncionario.appendChild(subtituloFuncionario);

  resultado.forEach((dato) => {
    const divRegistro = document.createElement("DIV");
    divRegistro.classList.add("formaciones__registro");

    const nombre = document.createElement("P");
    nombre.classList.add("formaciones__nombre");
    nombre.textContent = `${dato.grado} ${dato.nombre}`;

    const novedad = document.createElement("P");
    novedad.classList.add("formaciones__novedad");
    novedad.setAttribute("value", dato.id_funcionario);
    novedad.textContent = `${dato.novedad}`;

    const idFuncionario = document.createElement("INPUT");
    idFuncionario.setAttribute("type", "hidden");
    idFuncionario.setAttribute("id", "id_funcionario");
    idFuncionario.setAttribute("value", dato.id_funcionario);

    const idNov = document.createElement("INPUT");
    idNov.setAttribute("type", "hidden");
    idNov.setAttribute("id", "id_novedad");
    idNov.setAttribute("value", dato.id_novedad);

    divRegistro.appendChild(nombre);
    divRegistro.appendChild(novedad);
    divRegistro.appendChild(idFuncionario);
    divRegistro.appendChild(idNov);

    divFuncionario.appendChild(divRegistro);
  });

  // Bloque novedades
  const divNovedades = document.createElement("DIV");
  divNovedades.classList.add("formaciones__novedades");

  const subtituloNovedades = document.createElement("H2");
  subtituloNovedades.classList.add("formaciones__subtitulo");
  if (btnconsulta === "General") {
    subtituloNovedades.textContent = "Novedades Grupo";
  }

  if (btnconsulta === "TurnoA") {
    subtituloNovedades.textContent = "Novedades Turno A";
  }

  if (btnconsulta === "TurnoB") {
    subtituloNovedades.textContent = "Novedades Turno B";
  }

  divNovedades.appendChild(subtituloNovedades);

  let count = resultado.length;

  const total = document.createElement("P");
  total.textContent = "Total Personal: ";
  const totalSpan = document.createElement("SPAN");
  totalSpan.textContent = count;
  total.appendChild(totalSpan);
  divNovedades.appendChild(total);

  const numerico = document.createElement("P");
  numerico.textContent = "Parte Numerico: ";
  const numSpan = document.createElement("SPAN");

  let base = resultado.filter((valor) => valor.categoria === "Base").length;
  let ejecutivo = resultado.filter(
    (valor) => valor.categoria === "Ejecutivo"
  ).length;
  let directivo = resultado.filter(
    (valor) => valor.categoria === "Directivo"
  ).length;

  numSpan.textContent = `${directivo}-${ejecutivo}-${base}`;
  numerico.appendChild(numSpan);
  divNovedades.appendChild(numerico);

  listNov.forEach((novedad) => {
    const titleNoveda = document.createElement("DIV");
    titleNoveda.classList.add("formaciones__bloque");

    const label = document.createElement("LABEL");
    label.classList.add("formaciones__label");
    label.textContent = novedad.novedad;

    const inputCant = document.createElement("INPUT");
    inputCant.classList.add("formaciones__cant");
    inputCant.setAttribute("type", "text");

    const inputNumerico = document.createElement("INPUT");
    inputNumerico.classList.add("formaciones__numerico");
    inputNumerico.setAttribute("type", "text");

    base = resultado.filter(
      (valor) => valor.categoria === "Base" && valor.novedad === novedad.novedad
    ).length;
    ejecutivo = resultado.filter(
      (valor) =>
        valor.categoria === "Ejecutivo" && valor.novedad === novedad.novedad
    ).length;
    directivo = resultado.filter(
      (valor) =>
        valor.categoria === "Directivo" && valor.novedad === novedad.novedad
    ).length;

    inputCant.value = resultado.filter(
      (cant) => cant.novedad === novedad.novedad
    ).length;
    inputNumerico.value = `${directivo}-${ejecutivo}-${base}`;

    titleNoveda.appendChild(label);
    titleNoveda.appendChild(inputCant);
    titleNoveda.appendChild(inputNumerico);
    divNovedades.appendChild(titleNoveda);
  });

  filtroParte.appendChild(divFuncionario);
  filtroParte.appendChild(divNovedades);

  const modal = document.querySelectorAll(".formaciones__novedad");

  modal.forEach((mod) => {
    mod.addEventListener("click", (e) => {
      let id = e.target.attributes.value.value;
      generarModal(id);
    });
  });
}

function cargarModal() {
  const modal = document.querySelector(".modal");

  if (modal) {
    modal.classList.add("modal--show");

    let funcionario = document.querySelector("#funcionario");
    let novedades = document.querySelector("#situacion");
    novedades.innerHTML = "";
    let observacion = document.querySelector("#observacion");

    funcionario.value = `${resultado[0].grado} ${resultado[0].nombre}`;
    funcionario.setAttribute(
      "data-id-funcionario",
      resultado[0].id_funcionario
    );

    listNov.forEach((novedad) => {
      const option = document.createElement("OPTION");
      option.textContent = novedad.novedad;
      option.value = novedad.id;

      if (resultado[0].id_novedad === novedad.id) {
        option.setAttribute("selected", "");
      }

      novedades.appendChild(option);
    });

    observacion.value = resultado[0].observacion;
  }
}

// Bloque codigo cambio de estado
if (estado) {
  estado.addEventListener("change", function (e) {
    grupo = document.querySelector("#id_grupoSeguimiento");

    if (grupo) {
      // Usuario global
      if (grupo.value) {
        cambioEstado(grupo.value, e.target.value);
      } else {
        document.getElementById("estado").selectedIndex = 0;
        Swal.fire({
          icon: "error",
          title: "Error Selección",
          text: "Selecione un grupo, por favor...",
          // footer: '<a href="#">Why do I have this issue?</a>'
        });
      }
    } else {
      // Otros usuarios
      grupo = document.querySelector("#userGrupo");
      cambioEstado(grupo.value, e.target.value);
    }
  });
}

async function cambioEstado(grupo, estado) {
  const datos = new FormData();
  datos.append("id", grupo);
  datos.append("opc", estado);
  if (estado === "1") {
    btnconsulta = "General";
  }

  if (estado === "2") {
    btnconsulta = "TurnoA";
  }

  if (estado === "3") {
    btnconsulta = "TurnoB";
  }
  try {
    const url = "/modificarNovedades";

    const respuesta = await fetch(url, {
      method: "POST",
      body: datos,
    });

    resultado = await respuesta.json();

    await novedades();
    cargarDom();
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error Conexión",
      text: "No se tiene conexion con la pagina solicitada",
      // footer: '<a href="#">Why do I have this issue?</a>'
    });
  }
}

const modal = document.querySelectorAll(".formaciones__novedad");

if (modal) {
  modal.forEach((opc) => {
    opc.addEventListener("click", (e) => {
      let id = e.target.attributes.value.value;
      generarModal(id);
    });
  });
}

async function generarModal(id) {
  const datos = new FormData();
  datos.append("id", id);

  try {
    const url = "/consultarFuncionario";

    const respuesta = await fetch(url, {
      method: "POST",
      body: datos,
    });

    resultado = await respuesta.json();
    await novedades();

    cargarModal();
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error Conexión",
      text: "No se ha podido generar la consulta",
      // footer: '<a href="#">Why do I have this issue?</a>'
    });
  }
}

// Boton cancelar modal
const cancelModal = document.querySelector(".modal__cancelar");

if (cancelModal) {
  cancelModal.addEventListener("click", (e) => {
    e.preventDefault();
    const show = document.querySelector(".modal--show");

    if (show) {
      show.classList.remove("modal--show");
    }
  });
}

// Boton actualizar datos
const actualizar = document.querySelector(".modal__actualizar");

if (actualizar) {
  let idFuncionario = document.querySelector("#funcionario");
  let idNovedad = document.querySelector("#situacion");
  let observacion = document.querySelector("#observacion");

  let idUser;
  let idNov;
  let obs;

  actualizar.addEventListener("click", () => {
    idUser = idFuncionario.getAttribute("data-id-funcionario");
    idNov = idNovedad.options[idNovedad.selectedIndex].value;
    obs = observacion.value;

    actualizarDatos(idUser, idNov, obs);
  });
}

async function actualizarDatos(idUser, idNov, obs) {
  const datos = new FormData();
  datos.append("idUser", idUser);
  datos.append("idNov", idNov);
  datos.append("obs", obs);
  try {
    const url = "/modificarNovedad";

    const respuesta = await fetch(url, {
      method: "POST",
      body: datos,
    });

    resultado = await respuesta.json();

    const show = document.querySelector(".modal--show");

    if (show) {
      show.classList.remove("modal--show");
    }

    if (resultado) {
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Actualización Exitosa",
        showConfirmButton: false,
        timer: 1500,
      });
    }

    let eventoClic = new Event("click");

    if (btnGeneral) {
      document.querySelector("#btnGeneral").dispatchEvent(eventoClic);
      return;
    }

    if (btnTurnoA) {
      document.querySelector("#btnTurnoA").dispatchEvent(eventoClic);
      return;
    }

    if (btnTurnoB) {
      document.querySelector("#btnTurnoB").dispatchEvent(eventoClic);
      return;
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error Conexión",
      text: "No se ha podido generar la actualización.",
      // footer: '<a href="#">Why do I have this issue?</a>'
    });
  }
}
