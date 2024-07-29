<?php

namespace Controllers;

use MVC\Router;
use Model\Grado;
use Classes\Email;
use Model\Funcionario;
use Model\Grupo;
use Model\NovedadFuncionario;

class AuthController {

    public static function login(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $usuario = new Funcionario($_POST);

            $alertas = $usuario->validarLogin();
            
            if(empty($alertas)) {
                // Verificar que el usuario exista
                $usuario = Funcionario::where('email', $usuario->email);
                if(!$usuario) {
                    Funcionario::setAlerta('error', 'El Usuario No Existe');
                } else {
                    // El Usuario existe
                    if( password_verify($_POST['password'], $usuario->password) ) {
                                                
                        $usuario->id_grado = Grado::find($usuario->id_grado);                        

                        // Iniciar la sesión
                        session_start();    
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre ." ". $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['usuario'] = $usuario->usuario;
                        $_SESSION['id_grupo'] = $usuario->id_grupo;
                        $_SESSION['id_grado'] = $usuario->id_grado->abreviacion; 

                        if($_SESSION['usuario']=== '0'){
                            header('Location: /login-funcionario');
                        } 

                        if($_SESSION['usuario']=== '1'){
                            header('Location: /login-usuario');
                        }

                        if($_SESSION['usuario']=== '2'){
                            header('Location: /login-local');
                        }

                        if($_SESSION['usuario']=== '3'){
                            header('Location: /login-global');
                        }                        
                        
                    } else {
                        Funcionario::setAlerta('error', 'Password Incorrecto');
                    }
                }
            }
        }

        $alertas = Funcionario::getAlertas();
        
        // Render a la vista 
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function crear(Router $router){

        if(!is_auth()){
            header('Location: /');
        }
        
        if($_SESSION['usuario'] !== '3' && $_SESSION['usuario'] !== '2'){
            $_SESSION = [];
            header('Location: /');
        }

        $alertas = [];
        $grados = Grado::all('ASC');
        $grupos = Grupo::all('ASC');
        $usuario = new Funcionario;
        $novedades = new NovedadFuncionario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){            
                        
            $usuario->sincronizar($_POST); 

            $alertas = $usuario->validar_cuenta();

            if(empty($alertas)){
                // Que no exista otro usuario con ese correo
                $respuesta = Funcionario::where('email',$usuario->email);

                if($respuesta){
                    Funcionario::setAlerta('error','Ya existe un usuario con ese correo.');
                }else{
                    $usuario->hashPassword();
                    $usuario->formateado();
                   
                    $respuesta = $usuario->guardar();
                    
                    $usuario = Funcionario::where('email',$usuario->email);
                    $novedades->id_funcionario = $usuario->id;
                    $novedades->id_novedad = 6;
                    $novedades->observacion = 'NINGUNA';

                    $resultado = $novedades->guardar();

                    if($respuesta){
                        header('Location: /usuarios');
                    }
                }
            }
        }

        $alertas = Funcionario::getAlertas();              

        $router->render('auth/crear',[
            'titulo' => 'Crear Usuario',
            'alertas' => $alertas, 
            'grados' => $grados,
            'grupos' => $grupos,
            'usuarios' => $usuario
        ]);
    }

    public static function modificar(Router $router){

        if(!is_auth()){
            header('Location: /');
        }   
        
        if($_SESSION['usuario'] !== '3' && $_SESSION['usuario'] !== '2'){
            $_SESSION = [];
            header('Location: /');
        }

        $alertas = [];
        $grados = Grado::all('ASC');
        $grupos = Grupo::all('ASC');

        $usuario = $_GET['id'];
        
        $usuarios = Funcionario::find($usuario);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario = new Funcionario;
            $usuario->sincronizar($_POST);

            
            if(!$usuario->password){
                $usuario->password = $usuarios->password;
            }else{
                $usuario->validarPassword();
            }
            
            $alertas = $usuario->validar_actualizacion();
            
            
            if(empty($alertas)){
                $respuesta = password_get_info($usuario->password);
                
                if(!($respuesta['algoName'] === 'bcrypt')){
                    $usuario->hashPassword();
                }

                if($usuario->estado === '0'){
                    $usuario->disponible = 0;
                }
                
                $usuario->id = $usuarios->id;
                $usuario->formateado();
               
                $respuesta = $usuario->guardar();

                if($respuesta){
                    header('Location: /usuarios');
                }

            }
        }

        $router->render('auth/modificar',[
            'titulo' => 'Actualizar Funcionario',
            'alertas' => $alertas,
            'grados' => $grados,
            'grupos' => $grupos,
            'usuarios' => $usuarios
        ]);
    }

    public static function funcionarios(Router $router){

        $boton = "funcionarios";

        if(!is_auth()){
            header('Location: /');
        }  

        if($_SESSION['usuario'] !== '3' && $_SESSION['usuario'] !== '2'){
            $_SESSION = [];
            header('Location: /');
        }
        
        switch ($_SESSION['usuario'])
            {
                case 0:
                    $link_volver ='/login-funcionario';
                    break;
                case 1:
                    $link_volver ='/login-usuario';
                    break;
                case 2:
                    $link_volver ='/login-local';
                    break;
                case 3:
                    $link_volver ='/login-global';
                    break; 
            }
        
        if($_SESSION['usuario'] === '3'){
            $respuesta = Funcionario::all();
        }else{
            $respuesta = Funcionario::campoCondicionado('id_grupo',$_SESSION['id_grupo']);
        }

        foreach($respuesta as $usuario){            
            if($usuario->estado === '1'){
                $usuario->id_grado = Grado::find($usuario->id_grado);
                $usuario->id_grupo = Grupo::find($usuario->id_grupo);
                $usuarios[] = $usuario;
            }
        }

        $router->render('auth/usuarios',[
            'titulo' => 'Usuarios del Sistema',
            'link' => $link_volver,
            'boton' => $boton,
            'usuarios' => $usuarios
        ]);

    }

    public static function administradores(Router $router){

        $boton = "admin";

        if(!is_auth()){
            header('Location: /');
        }  

        if($_SESSION['usuario'] !== '3'){
            $_SESSION = [];
            header('Location: /');
        }
               
        $link_volver ='/login-global';                        
       
        $usuarios = Funcionario::contengan('usuario',2,3);
        
        foreach($usuarios as $usuario){
            $usuario->id_grado = Grado::find($usuario->id_grado);
            $usuario->id_grupo = Grupo::find($usuario->id_grupo);
        }

        $router->render('auth/administradores',[
            'titulo' => 'Administradores del Sistema',
            'link' => $link_volver,
            'boton' => $boton,
            'usuarios' => $usuarios
        ]);
    }

    public static function inactivos(Router $router){

        $boton = "inactivos";

        if(!is_auth()){
            header('Location: /');
        }  

        if($_SESSION['usuario'] !== '3'){
            $_SESSION = [];
            header('Location: /');
        }
               
        $link_volver ='/login-global';                        

        $usuarios = Funcionario::campoCondicionado('estado',0);
        
        foreach($usuarios as $usuario){
            $usuario->id_grado = Grado::find($usuario->id_grado);
            $usuario->id_grupo = Grupo::find($usuario->id_grupo);
        }

        $router->render('auth/inactivos',[
            'titulo' => 'Usuarios Inactivos',
            'link' => $link_volver,
            'boton' => $boton,
            'usuarios' => $usuarios
        ]);
    }


    public static function logout() {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();
            $_SESSION = [];
            header('Location: /');
        }
       
    }    
}