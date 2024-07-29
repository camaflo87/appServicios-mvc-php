<?php

namespace Controllers;

use MVC\Router;
use Model\Grado;
use Model\Grupo;
use Model\Novedades;
use Model\Servicios;
use Model\Asignacion;
use Model\Funcionario;
use Model\NovedadFuncionario;
use Model\ParteGeneral;

class ApiController
{
    public static function usuarios()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['usuario'] !== '3' && $_SESSION['usuario'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        if ($_SESSION['usuario'] === '3') {
            $respuesta = Funcionario::all();
        } else {
            $respuesta = Funcionario::campoCondicionado('id_grupo', $_SESSION['id_grupo']);
        }

        foreach ($respuesta as $usuario) {
            if ($usuario->estado === '1') {
                $usuario->id_grado = Grado::find($usuario->id_grado);
                $usuario->id_grupo = Grupo::find($usuario->id_grupo);
                $usuarios[] = $usuario;
            }
        }

        echo json_encode($usuarios);
    }

    public static function administradores()
    {

        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['usuario'] !== '3' && $_SESSION['usuario'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        $usuarios = Funcionario::contengan('usuario', 2, 3);

        foreach ($usuarios as $usuario) {
            $usuario->id_grado = Grado::find($usuario->id_grado);
            $usuario->id_grupo = Grupo::find($usuario->id_grupo);
        }

        echo json_encode($usuarios);
    }

    public static function inactivos()
    {

        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['usuario'] !== '3' && $_SESSION['usuario'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        $usuarios = Funcionario::contengan('usuario', 2, 3);

        foreach ($usuarios as $usuario) {
            $usuario->id_grado = Grado::find($usuario->id_grado);
            $usuario->id_grupo = Grupo::find($usuario->id_grupo);
        }

        echo json_encode($usuarios);
    }

    public static function servicios()
    {
        $servicios = [];

        if (!is_auth()) {
            header('Location: /');
        }

        $funcionarios = Funcionario::all();

        foreach ($funcionarios as $funcionario) {
            if (Asignacion::consultaServicio($funcionario->id)) {
                // $funcionario->id = Asignacion::consultaServicio($funcionario->id);
                array_push($servicios, Asignacion::consultaServicio($funcionario->id));
                // $servicios = Asignacion::consultaServicio($funcionario->id);
            }
        }

        foreach ($servicios as $key) {
            foreach ($key as $servicio) {
                $servicio->id_funcionario = Funcionario::find($servicio->id_funcionario);
                $servicio->id_funcionario->id_grado = Grado::find($servicio->id_funcionario->id_grado);
                $servicio->id_servicio = Servicios::find($servicio->id_servicio);
            }
        }

        // debuguear($servicios);

        $count = 0;
        foreach ($servicios as $key) {
            foreach ($key as $servicio) {
                if ($servicio->id_funcionario->disponible !== '0' && $servicio->id_funcionario->estado !== '0') {
                    $servicioFiltro[$count]['funcionario'] = $servicio->id_funcionario->id_grado->abreviacion . " " . $servicio->id_funcionario->nombre . " " . $servicio->id_funcionario->nombre;
                    $servicioFiltro[$count]['servicio'] = $servicio->id_servicio->servicio;
                    $servicioFiltro[$count]['fecha'] = $servicio->fecha_servicio;
                    $servicioFiltro[$count]['lugar'] = $servicio->lugar;
                    $servicioFiltro[$count]['observaciones'] = $servicio->observaciones;
                    $servicioFiltro[$count]['id_grupo'] = $servicio->id_funcionario->id_grupo;
                    $servicioFiltro[$count]['categoria'] = $servicio->id_funcionario->id_grado->categoria;
                }
            }
            $count++;
        }

        // Ordenar array por el grado
        if (!empty($servicioFiltro)) {
            $ordenar = array_column($servicioFiltro, 'fecha');

            array_multisort($ordenar, SORT_ASC, $servicioFiltro);
        }

        // debuguear($servicioFiltro);

        echo json_encode($servicioFiltro);
    }

    public static function funcionarios()
    {

        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $grupo = $_POST['id'];

            $array = [
                'id_grupo' => $grupo,
                'estado' => '1',
                'disponible' => '1'
            ];

            $respuesta = Funcionario::whereArray($array);
            foreach ($respuesta as $usuario) {
                $usuario->id_grado = Grado::find($usuario->id_grado);
                $usuario->id_grupo = Grupo::find($usuario->id_grupo);
            }

            echo json_encode($respuesta);
        }
    }

    public static function serviciosFuncionario()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $servicios = Asignacion::busquedaServicios('id_funcionario', $id);

            foreach ($servicios as $servicio) {
                $servicio->id_servicio = Servicios::find($servicio->id_servicio);
            }
        }

        echo json_encode($servicios);
    }

    public static function consultaGlobal()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        $resultado = [];

        $fechaUno = $_POST['ini'];
        $fechaDos = $_POST['fin'];
        $servicio = $_POST['servicioId'];

        if ($_SESSION['usuario'] === '3') {
            $grupo = $_POST['grupoId'];
        } else {
            $grupo = $_POST['localId'];
        }

        $servicios = Asignacion::historico('fecha_servicio', $fechaUno, $fechaDos, $servicio);

        foreach ($servicios as $servicio) {
            $servicio->id_servicio = Servicios::find($servicio->id_servicio);
            $servicio->id_funcionario = Funcionario::find($servicio->id_funcionario);
            $servicio->id_funcionario->id_grado = Grado::find($servicio->id_funcionario->id_grado);
            if ($servicio->id_funcionario->id_grupo === $grupo) {
                $resultado[] = $servicio;
            }
        }

        // Crear nuevo array para ser enviado en el fecth
        $count = 0;
        foreach ($servicios as $servicio) {
            if ($servicio->id_funcionario->estado !== '0' && $servicio->id_funcionario->disponible !== '0') {
                $historico[$count]['funcionario'] = $servicio->id_funcionario->id_grado->abreviacion . " " . $servicio->id_funcionario->nombre . " " . $servicio->id_funcionario->apellido; // Id funcionario
                $historico[$count]['fecha'] = $servicio->fecha_servicio;
                $historico[$count]['lugar'] = $servicio->lugar;
                $historico[$count]['observaciones'] = $servicio->observaciones;
                $historico[$count]['semana'] = $servicio->semana;
            }
            $count++;
        }

        // Ordenar array por el grado
        if (!empty($historico)) {
            $ordenar = array_column($historico, 'fecha');

            array_multisort($ordenar, SORT_DESC, $historico);
        }

        echo json_encode($historico);
    }

    public static function formGeneral()
    {
        if (!is_auth() || $_SESSION['usuario'] === '0') {
            header('Location: /');
        }

        $formaGeneral = [];
        $funcionarios = [];

        $formaGeneral = NovedadFuncionario::todos('id_funcionario');


        foreach ($formaGeneral as $dato) {
            $dato->id_funcionario = Funcionario::find($dato->id_funcionario);
            $dato->id_funcionario->id_grado = Grado::find($dato->id_funcionario->id_grado);
            $dato->id_novedad = Novedades::find($dato->id_novedad);
            $dato->id_funcionario->id_grupo = Grupo::find($dato->id_funcionario->id_grupo);
        }

        // Crear nuevo array de acuerdo al grupo que se recibe de POST
        $count = 0;
        foreach ($formaGeneral as $dato) {

            if ($_POST['id'] === $dato->id_funcionario->id_grupo->id && $dato->id_funcionario->estado === '1') {
                $funcionarios[$count]['id_funcionario'] = $dato->id_funcionario->id; // Id funcionario
                $funcionarios[$count]['nombre'] = $dato->id_funcionario->nombre . " " . $dato->id_funcionario->apellido;
                $funcionarios[$count]['grado'] = $dato->id_funcionario->id_grado->abreviacion;
                $funcionarios[$count]['turno'] = $dato->id_funcionario->turno;
                $funcionarios[$count]['id_grado'] = $dato->id_funcionario->id_grado->id; // Id grado
                $funcionarios[$count]['categoria'] = $dato->id_funcionario->id_grado->categoria; // Categoria
                $funcionarios[$count]['novedad'] = $dato->id_novedad->novedad;
                $funcionarios[$count]['id_novedad'] = $dato->id_novedad->id; // Id novedad
            }
            $count++;
        }

        // Ordenar array por el grado
        if (!empty($funcionarios)) {
            $ordenar = array_column($funcionarios, 'id_grado');

            array_multisort($ordenar, SORT_DESC, $funcionarios);
        }

        echo json_encode($funcionarios);
    }

    public static function formacionTurnos()
    {
        if (!is_auth() || $_SESSION['usuario'] === '0') {
            header('Location: /');
        }

        $formaGeneral = [];
        $funcionarios = [];

        $formaGeneral = NovedadFuncionario::todos('id_funcionario');


        foreach ($formaGeneral as $dato) {
            $dato->id_funcionario = Funcionario::find($dato->id_funcionario);
            $dato->id_funcionario->id_grado = Grado::find($dato->id_funcionario->id_grado);
            $dato->id_novedad = Novedades::find($dato->id_novedad);
            $dato->id_funcionario->id_grupo = Grupo::find($dato->id_funcionario->id_grupo);
        }

        // Crear nuevo array de acuerdo al grupo que se recibe de POST
        $count = 0;
        foreach ($formaGeneral as $dato) {

            if ($_POST['id'] === $dato->id_funcionario->id_grupo->id && $dato->id_funcionario->estado === '1' && $dato->id_funcionario->turno === $_POST['turno']) {
                $funcionarios[$count]['id_funcionario'] = $dato->id_funcionario->id; // Id funcionario
                $funcionarios[$count]['nombre'] = $dato->id_funcionario->nombre . " " . $dato->id_funcionario->apellido;
                $funcionarios[$count]['grado'] = $dato->id_funcionario->id_grado->abreviacion;
                $funcionarios[$count]['turno'] = $dato->id_funcionario->turno;
                $funcionarios[$count]['id_grado'] = $dato->id_funcionario->id_grado->id; // Id grado
                $funcionarios[$count]['categoria'] = $dato->id_funcionario->id_grado->categoria; // Categoria
                $funcionarios[$count]['novedad'] = $dato->id_novedad->novedad;
                $funcionarios[$count]['id_novedad'] = $dato->id_novedad->id; // Id novedad
            }
            $count++;
        }

        // Ordenar array por el grado
        if (!empty($funcionarios)) {
            $ordenar = array_column($funcionarios, 'id_grado');

            array_multisort($ordenar, SORT_DESC, $funcionarios);
        }

        echo json_encode($funcionarios);
    }

    public static function novedades()
    {
        if (!is_auth() || $_SESSION['usuario'] === '0') {
            header('Location: /');
        }

        $novedades = Novedades::all();

        echo json_encode($novedades);
    }

    public static function editarNovedades()
    {
        if (!is_auth() || $_SESSION['usuario'] === '0') {
            header('Location: /');
        }

        $formaGeneral = [];
        $funcionarios = [];

        $formaGeneral = NovedadFuncionario::todos('id_funcionario');


        foreach ($formaGeneral as $dato) {
            $dato->id_funcionario = Funcionario::find($dato->id_funcionario);
            $dato->id_funcionario->id_grado = Grado::find($dato->id_funcionario->id_grado);
            $dato->id_novedad = Novedades::find($dato->id_novedad);
            $dato->id_funcionario->id_grupo = Grupo::find($dato->id_funcionario->id_grupo);
        }

        // Crear nuevo array de acuerdo al grupo que se recibe de POST
        $estado = '';

        switch ($_POST['opc']) {
            case 1:
                $count = 0;
                foreach ($formaGeneral as $dato) {

                    if ($_POST['id'] === $dato->id_funcionario->id_grupo->id && $dato->id_funcionario->estado === '1') {
                        $funcionarios[$count]['id_funcionario'] = $dato->id_funcionario->id; // Id funcionario
                        $funcionarios[$count]['nombre'] = $dato->id_funcionario->nombre . " " . $dato->id_funcionario->apellido;
                        $funcionarios[$count]['grado'] = $dato->id_funcionario->id_grado->abreviacion;
                        $funcionarios[$count]['turno'] = $dato->id_funcionario->turno;
                        $funcionarios[$count]['id_grado'] = $dato->id_funcionario->id_grado->id; // Id grado
                        $funcionarios[$count]['categoria'] = $dato->id_funcionario->id_grado->categoria; // Categoria
                        $funcionarios[$count]['novedad'] = $dato->id_novedad->novedad;
                        $funcionarios[$count]['id_novedad'] = $dato->id_novedad->id; // Id novedad
                    }
                    $count++;
                }
                break;
            case 2:
                $count = 0;
                foreach ($formaGeneral as $dato) {

                    if ($_POST['id'] === $dato->id_funcionario->id_grupo->id && $dato->id_funcionario->estado === '1' && $dato->id_funcionario->turno === 'A') {
                        $funcionarios[$count]['id_funcionario'] = $dato->id_funcionario->id; // Id funcionario
                        $funcionarios[$count]['nombre'] = $dato->id_funcionario->nombre . " " . $dato->id_funcionario->apellido;
                        $funcionarios[$count]['grado'] = $dato->id_funcionario->id_grado->abreviacion;
                        $funcionarios[$count]['turno'] = $dato->id_funcionario->turno;
                        $funcionarios[$count]['id_grado'] = $dato->id_funcionario->id_grado->id; // Id grado
                        $funcionarios[$count]['categoria'] = $dato->id_funcionario->id_grado->categoria; // Categoria
                        $funcionarios[$count]['novedad'] = $dato->id_novedad->novedad;
                        $funcionarios[$count]['id_novedad'] = $dato->id_novedad->id; // Id novedad
                    }
                    $count++;
                }
                break;
            case 3:
                $count = 0;
                foreach ($formaGeneral as $dato) {

                    if ($_POST['id'] === $dato->id_funcionario->id_grupo->id && $dato->id_funcionario->estado === '1' && $dato->id_funcionario->turno === 'B') {
                        $funcionarios[$count]['id_funcionario'] = $dato->id_funcionario->id; // Id funcionario
                        $funcionarios[$count]['nombre'] = $dato->id_funcionario->nombre . " " . $dato->id_funcionario->apellido;
                        $funcionarios[$count]['grado'] = $dato->id_funcionario->id_grado->abreviacion;
                        $funcionarios[$count]['turno'] = $dato->id_funcionario->turno;
                        $funcionarios[$count]['id_grado'] = $dato->id_funcionario->id_grado->id; // Id grado
                        $funcionarios[$count]['categoria'] = $dato->id_funcionario->id_grado->categoria; // Categoria
                        $funcionarios[$count]['novedad'] = $dato->id_novedad->novedad;
                        $funcionarios[$count]['id_novedad'] = $dato->id_novedad->id; // Id novedad
                    }
                    $count++;
                }
                break;
        }



        // debuguear($funcionarios);

        // cambiar estado
        if ($_POST['opc'] === '1') {
            $estado = '6';
            foreach ($funcionarios as $funcionario) {
                NovedadFuncionario::editarRegistros('id_novedad', $estado, 'Ninguna', $funcionario['id_funcionario']);
            }
        }

        if ($_POST['opc'] === '2') {
            $estado = '2';
            foreach ($funcionarios as $funcionario) {
                NovedadFuncionario::editarRegistros('id_novedad', $estado, 'Ninguna', $funcionario['id_funcionario']);
            }
        }

        if ($_POST['opc'] === '3') {
            $estado = '2';
            foreach ($funcionarios as $funcionario) {
                NovedadFuncionario::editarRegistros('id_novedad', $estado, 'Ninguna', $funcionario['id_funcionario']);
            }
        }

        $formaGeneral = NovedadFuncionario::todos('id_funcionario');


        foreach ($formaGeneral as $dato) {
            $dato->id_funcionario = Funcionario::find($dato->id_funcionario);
            $dato->id_funcionario->id_grado = Grado::find($dato->id_funcionario->id_grado);
            $dato->id_novedad = Novedades::find($dato->id_novedad);
            $dato->id_funcionario->id_grupo = Grupo::find($dato->id_funcionario->id_grupo);
        }

        // Crear nuevo array de acuerdo al grupo que se recibe de POST
        $estado = '';

        switch ($_POST['opc']) {
            case 1:
                $count = 0;
                foreach ($formaGeneral as $dato) {

                    if ($_POST['id'] === $dato->id_funcionario->id_grupo->id && $dato->id_funcionario->estado === '1') {
                        $funcionarios[$count]['id_funcionario'] = $dato->id_funcionario->id; // Id funcionario
                        $funcionarios[$count]['nombre'] = $dato->id_funcionario->nombre . " " . $dato->id_funcionario->apellido;
                        $funcionarios[$count]['grado'] = $dato->id_funcionario->id_grado->abreviacion;
                        $funcionarios[$count]['turno'] = $dato->id_funcionario->turno;
                        $funcionarios[$count]['id_grado'] = $dato->id_funcionario->id_grado->id; // Id grado
                        $funcionarios[$count]['categoria'] = $dato->id_funcionario->id_grado->categoria; // Categoria
                        $funcionarios[$count]['novedad'] = $dato->id_novedad->novedad;
                        $funcionarios[$count]['id_novedad'] = $dato->id_novedad->id; // Id novedad
                    }
                    $count++;
                }
                break;
            case 2:
                $count = 0;
                foreach ($formaGeneral as $dato) {

                    if ($_POST['id'] === $dato->id_funcionario->id_grupo->id && $dato->id_funcionario->estado === '1' && $dato->id_funcionario->turno === 'A') {
                        $funcionarios[$count]['id_funcionario'] = $dato->id_funcionario->id; // Id funcionario
                        $funcionarios[$count]['nombre'] = $dato->id_funcionario->nombre . " " . $dato->id_funcionario->apellido;
                        $funcionarios[$count]['grado'] = $dato->id_funcionario->id_grado->abreviacion;
                        $funcionarios[$count]['turno'] = $dato->id_funcionario->turno;
                        $funcionarios[$count]['id_grado'] = $dato->id_funcionario->id_grado->id; // Id grado
                        $funcionarios[$count]['categoria'] = $dato->id_funcionario->id_grado->categoria; // Categoria
                        $funcionarios[$count]['novedad'] = $dato->id_novedad->novedad;
                        $funcionarios[$count]['id_novedad'] = $dato->id_novedad->id; // Id novedad
                    }
                    $count++;
                }
                break;
            case 3:
                $count = 0;
                foreach ($formaGeneral as $dato) {

                    if ($_POST['id'] === $dato->id_funcionario->id_grupo->id && $dato->id_funcionario->estado === '1' && $dato->id_funcionario->turno === 'B') {
                        $funcionarios[$count]['id_funcionario'] = $dato->id_funcionario->id; // Id funcionario
                        $funcionarios[$count]['nombre'] = $dato->id_funcionario->nombre . " " . $dato->id_funcionario->apellido;
                        $funcionarios[$count]['grado'] = $dato->id_funcionario->id_grado->abreviacion;
                        $funcionarios[$count]['turno'] = $dato->id_funcionario->turno;
                        $funcionarios[$count]['id_grado'] = $dato->id_funcionario->id_grado->id; // Id grado
                        $funcionarios[$count]['categoria'] = $dato->id_funcionario->id_grado->categoria; // Categoria
                        $funcionarios[$count]['novedad'] = $dato->id_novedad->novedad;
                        $funcionarios[$count]['id_novedad'] = $dato->id_novedad->id; // Id novedad
                    }
                    $count++;
                }
                break;
        }

        // Ordenar array por el grado
        if (!empty($funcionarios)) {
            $ordenar = array_column($funcionarios, 'id_grado');

            array_multisort($ordenar, SORT_DESC, $funcionarios);
        }

        echo json_encode($funcionarios);
    }

    public static function consultaFuncionario()
    {
        if (!is_auth() || $_SESSION['usuario'] === '0') {
            header('Location: /');
        }

        $id = $_POST['id'];

        $formaGeneral = NovedadFuncionario::campoCondicionado('id_funcionario', $id);

        foreach ($formaGeneral as $dato) {
            $dato->id_funcionario = Funcionario::find($dato->id_funcionario);
            $dato->id_funcionario->id_grado = Grado::find($dato->id_funcionario->id_grado);
            $dato->id_novedad = Novedades::find($dato->id_novedad);
            $dato->id_funcionario->id_grupo = Grupo::find($dato->id_funcionario->id_grupo);
        }

        $count = 0;
        foreach ($formaGeneral as $dato) {
            $funcionarios[$count]['id_funcionario'] = $dato->id_funcionario->id; // Id funcionario
            $funcionarios[$count]['nombre'] = $dato->id_funcionario->nombre . " " . $dato->id_funcionario->apellido;
            $funcionarios[$count]['grado'] = $dato->id_funcionario->id_grado->abreviacion;
            $funcionarios[$count]['turno'] = $dato->id_funcionario->turno;
            $funcionarios[$count]['id_grado'] = $dato->id_funcionario->id_grado->id; // Id grado
            $funcionarios[$count]['categoria'] = $dato->id_funcionario->id_grado->categoria; // Categoria
            $funcionarios[$count]['novedad'] = $dato->id_novedad->novedad;
            $funcionarios[$count]['id_novedad'] = $dato->id_novedad->id; // Id novedad
            $funcionarios[$count]['observacion'] = $dato->observacion;
            $count++;
        }

        echo json_encode($funcionarios);
    }

    public static function actualizarNovedad()
    {
        if (!is_auth() || $_SESSION['usuario'] === '0') {
            header('Location: /');
        }

        $idNovedad = $_POST['idNov'];
        $observacion = $_POST['obs'];
        $usuario = $_POST['idUser'];


        $actualizar = NovedadFuncionario::actualizarNovedades('id_novedad', $idNovedad, $observacion, $usuario);

        echo json_encode($actualizar);
    }

    public static function parteGeneral()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['usuario'] !== '3') {
            $_SESSION = [];
            header('Location: /');
        }

        // $novedades = NovedadFuncionario::todos('DESC', 'id_funcionario');

        $grupos = Grupo::all();

        $novedades = Novedades::all();

        $novedadesFiltro = ParteGeneral::novedadesGenerales();

        function calcularPersonal($valorContar, $arrayConsulta = [])
        {
            $conteo = 0;
            foreach ($arrayConsulta as $valor) {

                if ($valor->grupo === $valorContar) {
                    $conteo++;
                }
            }
            return $conteo;
        }

        function calcularNovedades($valorContar, $valorNovedad, $arrayConsulta = [])
        {
            $conteo = 0;
            foreach ($arrayConsulta as $valor) {

                if ($valor->grupo === $valorContar) {
                    if ($valor->novedad === $valorNovedad) {
                        $conteo++;
                    }
                }
            }
            return $conteo;
        }

        function personalNumerico($valorContar, $arrayConsulta = [])
        {
            $base = 0;
            $ejecutivo = 0;
            $directivo = 0;

            foreach ($arrayConsulta as $valor) {

                if ($valor->grupo === $valorContar) {

                    if ($valor->categoria === 'Base') {
                        $base++;
                    }

                    if ($valor->categoria === 'Ejecutivo') {
                        $ejecutivo++;
                    }

                    if ($valor->categoria === 'Directivo') {
                        $directivo++;
                    }
                }
            }
            return "[ " . $directivo . "-" . $ejecutivo . "-" . $base . " ]";
        }

        function numericoNovedades($valorContar, $valorNovedad, $arrayConsulta = [])
        {
            $base = 0;
            $ejecutivo = 0;
            $directivo = 0;

            foreach ($arrayConsulta as $valor) {

                if ($valor->grupo === $valorContar) {

                    if ($valor->categoria === 'Base' && $valor->novedad === $valorNovedad) {
                        $base++;
                    }

                    if ($valor->categoria === 'Ejecutivo' && $valor->novedad === $valorNovedad) {
                        $ejecutivo++;
                    }

                    if ($valor->categoria === 'Directivo' && $valor->novedad === $valorNovedad) {
                        $directivo++;
                    }
                }
            }
            return "[ " . $directivo . "-" . $ejecutivo . "-" . $base . " ]";
        }

        $count = 0;
        foreach ($grupos as $grupo) {
            $filtroParte[$count]['grupo'] = $grupo->grupo;
            $filtroParte[$count]['personal'] = (int) calcularPersonal($grupo->grupo, $novedadesFiltro);
            $filtroParte[$count]['numerico'] = (string) personalNumerico($grupo->grupo, $novedadesFiltro);
            $contar = 0;
            foreach ($novedades as $novedad) {
                $filtroNovedad[$contar]['novedad'] = $novedad->novedad;
                $filtroNovedad[$contar]['personal'] = calcularNovedades($grupo->grupo, $novedad->novedad, $novedadesFiltro);
                $filtroNovedad[$contar]['numerico'] = numericoNovedades($grupo->grupo, $novedad->novedad, $novedadesFiltro);
                $contar++;
            }
            $filtroParte[$count]['novedad'] = $filtroNovedad;
            $count++;
        }

        // debuguear($filtroNovedad);


        echo json_encode($filtroParte);
    }
}
