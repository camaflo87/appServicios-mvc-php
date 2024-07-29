<?php

namespace Controllers;

use Model\Funcionario;
use Model\Grado;
use MVC\Router;
use Model\Grupo;
use Model\Novedades;
use Model\NovedadFuncionario;

class FormacionController
{
    public static function enlaces(Router $router)
    {

        if (!is_auth() || $_SESSION['usuario'] !== '3') {
            header('Location: /');
        }

        switch ($_SESSION['usuario']) {
            case 3:
                $link_volver = '/login-global';
                break;
            default:
                $_SESSION = [];
                header('Location: /');
                break;
        }

        $router->render('formacion/principal', [
            'titulo' => 'Seccional Investigación Criminal MEVAL',
            'link' => $link_volver
        ]);
    }

    public static function fomacionPersonal(Router $router)
    {
        if (!is_auth() || $_SESSION['usuario'] === '0') {
            header('Location: /');
        }

        $formaGeneral = [];
        $funcionarios = [];
        $novedades = '';
        $total = 0;
        $totalNumerico = [
            'directivo' => 0,
            'ejecutivo' => 0,
            'base' => 0
        ];

        switch ($_SESSION['usuario']) {
            case 0:
                $_SESSION = [];
                header('Location: /');
                break;
            case 1:
                $link_volver = '/login-usuario';
                break;
            case 2:
                $link_volver = '/login-local';
                break;
            case 3:
                $link_volver = '/enlaces-formacion';
                $formaGeneral = [];
                break;
        }

        if ($_SESSION['usuario'] !== '3') {
            $formaGeneral = NovedadFuncionario::todos('id_funcionario');
            $novedades = Novedades::all();

            foreach ($formaGeneral as $dato) {
                $dato->id_funcionario = Funcionario::find($dato->id_funcionario);
                $dato->id_funcionario->id_grado = Grado::find($dato->id_funcionario->id_grado);
                $dato->id_novedad = Novedades::find($dato->id_novedad);
                $dato->id_funcionario->id_grupo = Grupo::find($dato->id_funcionario->id_grupo);
            }

            $count = 0;
            foreach ($formaGeneral as $dato) {

                if ($_SESSION['id_grupo'] === $dato->id_funcionario->id_grupo->id && $dato->id_funcionario->estado === '1') {
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

            $total = count($funcionarios);

            // Ordenar array por el grado
            if (!empty($funcionarios)) {
                $ordenar = array_column($funcionarios, 'id_grado');

                array_multisort($ordenar, SORT_DESC, $funcionarios);
            }

            $count = 0;

            // Crea el parte numerico
            if ($_SESSION['usuario'] !== '3') {
                foreach ($novedades as $novedad) {
                    $base = 0;
                    $ejecutivo = 0;
                    $directivo = 0;
                    foreach ($funcionarios as $funcionario) {
                        if ($funcionario['id_novedad'] === $novedad->id) {
                            $count++;

                            if ($funcionario['categoria'] === 'Base') {
                                $base++;
                            }

                            if ($funcionario['categoria'] === 'Ejecutivo') {
                                $ejecutivo++;
                            }

                            if ($funcionario['categoria'] === 'Directivo') {
                                $directivo++;
                            }
                        }
                    }
                    $novedad->cant = (string)$count;
                    $novedad->numerico = $directivo . "-" . $ejecutivo . "-" . $base;

                    $totalNumerico['directivo'] = $totalNumerico['directivo'] + $directivo;
                    $totalNumerico['ejecutivo'] = $totalNumerico['ejecutivo'] + $ejecutivo;
                    $totalNumerico['base'] = $totalNumerico['base'] + $base;

                    $count = 0;
                }
            }
        }

        $grupos = Grupo::all();

        $router->render('formacion/grupo', [
            'titulo' => 'Parte Personal',
            'link' => $link_volver,
            'grupos' => $grupos,
            'funcionarios' => $funcionarios,
            'novedades' => $novedades,
            'total' => $total,
            'numerico' => $totalNumerico
        ]);
    }

    public static function formacionGeneral(Router $router)
    {
        if (!is_auth() || $_SESSION['usuario'] !== '3') {
            header('Location: /');
        }

        $router->render('formacion/general', [
            'titulo' => 'Formación Seccional',
            'link' => '/enlaces-formacion   '
        ]);
    }
}
