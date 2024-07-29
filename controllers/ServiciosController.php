<?php

namespace Controllers;

use Model\ActiveRecord;
use Model\Grado;
use Model\Grupo;
use MVC\Router;
use Model\Servicios;
use Model\Asignacion;
use Model\Funcionario;

class ServiciosController
{
    public static function seguimiento(Router $router)
    {
        if (!is_auth()) {
            header('Location: /');
        }

        $alertas = [];

        if ($_SESSION['usuario'] === '3') {
            $grupos = Grupo::all();
        } else {
            $grupos = Grupo::where('id', $_SESSION['id_grupo']);
        }

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
                $link_volver = '/login-global';
                break;
        }

        $boton = "seguimiento";

        $router->render('servicios/seguimiento', [
            'titulo' => 'Seguimiento Servicios',
            'link' => $link_volver,
            'boton' => $boton,
            'grupos' => $grupos
        ]);
    }

    public static function asignar(Router $router)
    {
        if (!is_auth()) {
            header('Location: /');
        }

        $asignacion = new Asignacion;


        if ($_SESSION['usuario'] === '3') {
            $grupos = Grupo::all();
        } else {
            $grupos = Grupo::where('id', $_SESSION['id_grupo']);
        }

        $servicios = Servicios::all();

        $alertas = [];

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
                $link_volver = '/login-global';
                break;
        }

        $boton = "asignar";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $asignacion->sincronizar($_POST);


            $alertas = $asignacion->validar_asignacion();

            if (empty($alertas)) {

                $arrayDatos = [
                    'fecha_servicio' => "'" . $asignacion->fecha_servicio . "'",
                    'id_funcionario' => $asignacion->id_funcionario,
                    'id_servicio' => $asignacion->id_servicio
                ];

                $consulta = Asignacion::whereArray($arrayDatos);

                if ($consulta) {
                    Asignacion::setAlerta('error', 'El funcionario ya registra con este servicio, en la fecha solicitada.');
                } else {

                    $respuesta = $asignacion->guardar();

                    if ($respuesta) {
                        header('Location: /seguimiento?msg="Servicio registrado con exito"');
                    }
                }
            }
        }

        $alertas = Asignacion::getAlertas();

        $router->render('servicios/asignar-servicios', [
            'titulo' => 'Asignar Servicios',
            'link' => $link_volver,
            'alertas' => $alertas,
            'boton' => $boton,
            'grupos' => $grupos,
            'servicios' => $servicios,
            'asignacion' => $asignacion,
            'alertas' => $alertas
        ]);
    }

    public static function serviciosFuncionario(Router $router)
    {

        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['usuario'] === '3') {
            $grupos = Grupo::all();
        } else {
            $grupos = Grupo::where('id', $_SESSION['id_grupo']);
        }

        $boton = "funcionario";

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
                $link_volver = '/login-global';
                break;
        }

        $router->render('servicios/funcionarios', [
            'titulo' => 'Servicios por Funcionario',
            'link' => $link_volver,
            'boton' => $boton,
            'grupos' => $grupos
        ]);
    }

    public static function historico(Router $router)
    {

        if (!is_auth()) {
            header('Location: /');
        }

        $servicios = Servicios::all();

        if ($_SESSION['usuario'] === '3') {
            $grupos = Grupo::all();
        } else {
            $grupos = Grupo::where('id', $_SESSION['id_grupo']);
        }

        $boton = "historico";

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
                $link_volver = '/login-global';
                break;
        }

        $router->render('servicios/historicos', [
            'titulo' => 'Historico por Servicios',
            'link' => $link_volver,
            'boton' => $boton,
            'grupos' => $grupos,
            'servicios' => $servicios
        ]);
    }

    public static function eliminar()
    {

        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['usuario'] !== '3' && $_SESSION['usuario'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];

            $servicio = Asignacion::find($id);

            if (!$servicio) {
                header('Location: /seguimiento?msg="Servicio a eliminar no encontrado"');
            }

            $respuesta = $servicio->eliminar();

            if ($respuesta) {
                header('Location: /seguimiento?msg="Servicio eliminado con exito"');
            }
        }
    }

    public static function editar(Router $router)
    {

        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['usuario'] !== '3' && $_SESSION['usuario'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        $alertas = [];

        $id = $_GET['id'];

        $funcionarios = Funcionario::all();

        foreach ($funcionarios as $funcionario) {
            $funcionario->id_grado = Grado::find($funcionario->id_grado);
        }

        $servicios = Asignacion::find($id);

        $servicios->id_servicio = Servicios::find($servicios->id_servicio);

        $router->render('/servicios/modificar', [
            'titulo' => 'Modificar Servicio',
            'alertas' => $alertas,
            'funcionarios' => $funcionarios,
            'servicios' => $servicios
        ]);
    }
}
