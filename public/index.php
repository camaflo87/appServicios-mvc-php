<?php

require_once __DIR__ . '/../includes/app.php';

use Controllers\ApiController;
use MVC\Router;
use Controllers\AuthController;
use Controllers\FormacionController;
use Controllers\PrincipalController;
use Controllers\ServiciosController;

$router = new Router();

// Login
$router->get('/', [AuthController::class, 'login']);
$router->post('/', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/crear', [AuthController::class, 'crear']);
$router->post('/crear', [AuthController::class, 'crear']);
$router->get('/modificar', [AuthController::class, 'modificar']);
$router->post('/modificar', [AuthController::class, 'modificar']);
$router->get('/usuarios', [AuthController::class, 'funcionarios']);
$router->get('/consulta-adm', [AuthController::class, 'administradores']);
$router->get('/consulta-inactivos', [AuthController::class, 'inactivos']);

// Menu 
$router->get('/login-global', [PrincipalController::class, 'global']);
$router->get('/login-local', [PrincipalController::class, 'local']);
$router->get('/login-usuario', [PrincipalController::class, 'usuario']);
$router->get('/login-funcionario', [PrincipalController::class, 'funcionario']);

// Api
$router->get('/api/usuarios', [ApiController::class, 'usuarios']);
$router->get('/api/adm', [ApiController::class, 'administradores']);
$router->get('/api/inactivos', [ApiController::class, 'inactivos']);
$router->get('/api/servicios', [ApiController::class, 'servicios']);
$router->post('/api/funcionarios', [ApiController::class, 'funcionarios']);
$router->post('/api/servicios_funcionario', [ApiController::class, 'serviciosFuncionario']);
$router->post('/api/servicios_historico', [ApiController::class, 'consultaGlobal']);
$router->post('/api/formacion_general', [ApiController::class, 'formGeneral']);
$router->get('/api/novedades', [ApiController::class, 'novedades']);
$router->post('/api/formacion_turnos', [ApiController::class, 'formacionTurnos']);
$router->post('/modificarNovedades', [ApiController::class, 'editarNovedades']);
$router->post('/consultarFuncionario', [ApiController::class, 'consultaFuncionario']);
$router->post('/modificarNovedad', [ApiController::class, 'actualizarNovedad']);
$router->get('/api/formacion_seccional', [ApiController::class, 'parteGeneral']);

// Servicios
$router->get('/seguimiento', [ServiciosController::class, 'seguimiento']);
$router->get('/asignar_servicio', [ServiciosController::class, 'asignar']);
$router->post('/asignar_servicio', [ServiciosController::class, 'asignar']);
$router->get('/servicios-funcionario', [ServiciosController::class, 'serviciosFuncionario']);
$router->get('/servicios-historico', [ServiciosController::class, 'historico']);
$router->post('/eliminar_servicio', [ServiciosController::class, 'eliminar']);
$router->get('/modificar_servicio', [ServiciosController::class, 'editar']);
$router->post('/modificar_servicio', [ServiciosController::class, 'editar']);

// Formación
$router->get('/enlaces-formacion', [FormacionController::class, 'enlaces']);
$router->get('/parte-grupo', [FormacionController::class, 'fomacionPersonal']);
$router->get('/parte-seccional', [FormacionController::class, 'formacionGeneral']);


$router->comprobarRutas();
