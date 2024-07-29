(function(){
    
    const filtro = document.querySelector('#filtro');

    
    if(filtro){
        let urlpag = location.href;
        let usuario = document.querySelector("#usuario");
        let funcionarios = [];
        let funcionariosFiltrados = [];
        let cant = 0;

        const body = document.querySelector('.usuarios__registrados');        
        const caption = document.querySelector('.usuarios__caption');
        
        obtenerFuncionarios();

        filtro.addEventListener('input', filtrarFuncionarios);

        async function obtenerFuncionarios(){
            let url;
            if(urlpag.includes('/usuarios')){
                url = '/api/usuarios';
            }

            if(urlpag.includes('/consulta-adm')){
                url = '/api/adm';
            }

            if(urlpag.includes('/consulta-inactivos')){
                url = '/api/inactivos';
            }

            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            
            formatearFuncionarios(resultado);
        }

        function formatearFuncionarios(arrayFuncionarios = []){
            funcionarios = arrayFuncionarios.map(funcionario =>{
                return {
                    nombre: `${funcionario.id_grado.abreviacion} ${funcionario.nombre} ${funcionario.apellido}`,
                    id: funcionario.id,
                    email: funcionario.email,
                    disponible: funcionario.disponible,
                    usuario: funcionario.usuario,
                    grupo: funcionario.id_grupo.grupo,
                    estado: funcionario.estado,
                    turno: funcionario.turno
                }
            });
        }      

        function filtrarFuncionarios(e){
            const busqueda = e.target.value;

            if(busqueda.length>5){
                const expresion = new RegExp(busqueda,"i");
                funcionariosFiltrados = funcionarios.filter(funcionario=>{
                    if(funcionario.nombre.toLowerCase().search(expresion) != -1){
                        return funcionario;
                    }
                });                
                mostrarFuncionarios();
            }

            if(busqueda.length === 0){
                location.reload();
            }
        }

        function mostrarFuncionarios(){
            if(body){
                body.innerHTML = '';
            }

            funcionariosFiltrados.forEach(funcionario=>{
                const filasHTML = document.createElement('TR'); 
                filasHTML.classList.add('usuarios__listado');

                const colfuncionario = document.createElement('TD');
                colfuncionario.dataset.label = "Nombre";
                colfuncionario.textContent = funcionario.nombre;
                
                const colEmail = document.createElement('TD');
                colEmail.dataset.label = "Email";
                colEmail.textContent = funcionario.email;

                const colGrupo = document.createElement('TD');
                colGrupo.dataset.label = "Grupo";
                colGrupo.textContent = funcionario.grupo;

                const colDisponible = document.createElement('TD');
                colDisponible.dataset.label = "Disponible";
                if(funcionario.disponible === '1'){
                    colDisponible.textContent = "SI";
                }else{
                    colDisponible.textContent = "NO";
                }

                const colPerfil = document.createElement('TD');
                colPerfil.dataset.label = "Perfil";
                if (funcionario.usuario === '3') {
                    colPerfil.textContent = "Adm. Global";
                }

                if (funcionario.usuario === '2') {
                    colPerfil.textContent = "Adm. Local";
                }

                if (funcionario.usuario === '1') {
                    colPerfil.textContent = "Usuario";
                }

                if (funcionario.usuario === '0') {
                    colPerfil.textContent = "Funcionario";
                }
                
                const colTurno = document.createElement('TD');
                colTurno.dataset.label = "Turno";
                colTurno.textContent = funcionario.turno;


                const colAcciones = document.createElement('TD');
                colAcciones.dataset.label = "Acciones";
                let linkMod = "";
    
                if (usuario.value === '3') { 
                    linkMod = document.createElement('A');
                    linkMod.classList.add('usuarios__modificar');
                    linkMod.href = `/modificar?id=${funcionario.id}`;
                    linkMod.textContent = "Modificar";
                } else if (funcionario.usuario !== '3') {                    
                    linkMod = document.createElement('A');
                    linkMod.classList.add('usuarios__modificar');
                    linkMod.href = `/modificar?id=${funcionario.id}`;
                    linkMod.textContent = "Modificar";
                } else { 
                    linkMod = document.createElement('P');
                    linkMod.textContent = "---------"
                }
                colAcciones.appendChild(linkMod);

                // Agrupar etiquetas
                filasHTML.appendChild(colfuncionario);
                filasHTML.appendChild(colEmail);
                filasHTML.appendChild(colGrupo);
                filasHTML.appendChild(colDisponible);
                filasHTML.appendChild(colPerfil);
                filasHTML.appendChild(colTurno);
                filasHTML.appendChild(colAcciones);

                // Añadir al DOM
                body.appendChild(filasHTML);

            });

            cant = funcionariosFiltrados.length;
            caption.textContent = `Usuarios Registrados (Cant: ${cant})`;
        }
    }
})();