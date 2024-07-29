<?php

namespace Controllers;

use Model\Asignacion;
use MVC\Router;
use Model\Grado;
use Model\Funcionario;
use Model\Servicios;

class PrincipalController
{
    public static function global(Router $router)
    {

        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['usuario'] !== '3') {
            header('Location: /');
        }

        $id = $_SESSION['id'];
        $usuario = Funcionario::find($id);

        $usuario->id_grado = Grado::find($usuario->id_grado);

        $router->render('menu/global', [
            'titulo' => 'ADMINISTRADOR GLOBAL',
            'usuario' => $usuario
        ]);
    }

    public static function local(Router $router)
    {

        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['usuario'] !== '2') {
            header('Location: /');
        }

        $id = $_SESSION['id'];
        $usuario = Funcionario::find($id);

        $usuario->id_grado = Grado::find($usuario->id_grado);

        $router->render('menu/local', [
            'titulo' => 'ADMINISTRADOR LOCAL',
            'usuario' => $usuario
        ]);
    }

    public static function usuario(Router $router)
    {

        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['usuario'] !== '1') {
            header('Location: /');
        }

        $id = $_SESSION['id'];
        $usuario = Funcionario::find($id);

        $usuario->id_grado = Grado::find($usuario->id_grado);

        $router->render('menu/usuario', [
            'titulo' => 'VENTANA USUARIO',
            'usuario' => $usuario
        ]);
    }

    public static function funcionario(Router $router)
    {

        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['usuario'] !== '0') {
            header('Location: /');
        }

        $id = $_SESSION['id'];
        $usuario = Funcionario::find($id);

        $usuario->id_grado = Grado::find($usuario->id_grado);

        $servicios = Asignacion::campoCondicionado('id_funcionario', $id);

        // debuguear($servicios);

        foreach ($servicios as $servicio) {
            $servicio->id_funcionario = Funcionario::find($servicio->id_funcionario);
            $servicio->id_funcionario->id_grado = Grado::find($servicio->id_funcionario->id_grado);
            $servicio->id_servicio = Servicios::find($servicio->id_servicio);
        }


        $count = 0;
        foreach ($servicios as $servicio) {
            $filtroServicio[$count]['funcionario'] = $servicio->id_funcionario->id_grado->abreviacion . " " . $servicio->id_funcionario->nombre . " " . $servicio->id_funcionario->apellido;
            $filtroServicio[$count]['servicio'] = $servicio->id_servicio->servicio;
            $filtroServicio[$count]['fecha'] = $servicio->fecha_servicio;
            $filtroServicio[$count]['lugar'] = $servicio->lugar;
            $filtroServicio[$count]['observaciones'] = $servicio->observaciones;
            $count++;
        }

        // Ordenar array por el grado
        if (!empty($filtroServicio)) {
            $ordenar = array_column($filtroServicio, 'fecha');

            array_multisort($ordenar, SORT_DESC, $filtroServicio);
        }

        // debuguear($filtroServicio);

        $router->render('menu/funcionario', [
            'titulo' => 'VENTANA FUNCIONARIO',
            'usuario' => $usuario,
            'servicios' => $filtroServicio
        ]);
    }
}
