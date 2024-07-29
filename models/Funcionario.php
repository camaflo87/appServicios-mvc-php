<?php

namespace Model;

class Funcionario  extends ActiveRecord {
    protected static $tabla = 'funcionarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'disponible', 'email', 'usuario', 'password', 'turno', 'id_grado', 'id_grupo','estado'];

    public $id;
    public $nombre;
    public $apellido;
    public $disponible;
    public $email;
    public $usuario;
    public $password;
    public $passwordTwo;
    public $turno;
    public $id_grado;
    public $id_grupo;
    public $estado;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->disponible = $args['disponible'] ?? '1';
        $this->email = $args['email'] ?? '';
        $this->usuario = $args['usuario'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->passwordTwo = $args['passwordTwo'] ?? '';
        $this->turno = $args['turno'] ?? '';
        $this->id_grado = $args['id_grado'] ?? '';
        $this->id_grupo = $args['id_grupo'] ?? '';
        $this->estado = $args['estado'] ?? '1';
    }

    // Validar el Login de Usuarios
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        return self::$alertas;

    }

    // Validación para cuentas nuevas
    public function validar_cuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }        
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        if(strlen($this->password) < 7) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        
        if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{3,}$/", $this->password)){
            self::$alertas['error'][] = 'La contraseña debe al menos tener caracter especial, un número y una letra';
        }

        if(!$this->id_grado){
            self::$alertas['error'][] = 'Debes elegir un grado policial';
        }

        if(!$this->turno){
            self::$alertas['error'][] = 'Debes seleccionar un turno laboral';
        }

        if(!$this->id_grupo){
            self::$alertas['error'][] = 'Debes elegir el grupo al cual pertenece';
        }

        if($this->password !== $this->passwordTwo){
            self::$alertas['error'][] = 'Las Contraseñas no son iguales.';
        }

        return self::$alertas;
    }

    public function validar_actualizacion() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }        
        
        if(!$this->id_grado){
            self::$alertas['error'][] = 'Debes elegir un grado policial';
        }

        if(!$this->turno){
            self::$alertas['error'][] = 'Debes seleccionar un turno laboral';
        }

        if(!$this->id_grupo){
            self::$alertas['error'][] = 'Debes elegir el grupo al cual pertenece';
        }

        if($this->usuario === ''){
            self::$alertas['error'][] = 'Debes seleccionar un perfil de usuario';
        }

        return self::$alertas;
    }

    public function formateado():void {
        
        $this->nombre = strtoupper($this->nombre);
        $this->apellido = strtoupper($this->apellido);
        $this->email = strtolower($this->email);

    }

    // Valida un email
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        return self::$alertas;
    }

    // Valida el Password 
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{3,}$/", $this->password)){
            self::$alertas['error'][] = 'La contraseña debe al menos tener caracter especial, un número y una letra';
        }

        if($this->password !== $this->passwordTwo){
            self::$alertas['error'][] = 'Las Contraseñas no son iguales.';
        }

        return self::$alertas;
    }

    public function nuevo_password() : array {
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'El Password Actual no puede ir vacio';
        }
        if(!$this->password_nuevo) {
            self::$alertas['error'][] = 'El Password Nuevo no puede ir vacio';
        }
        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    // Comprobar el password
    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password );
    }

    // Hashea el password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un Token
    public function crearToken() : void {
        $this->token = uniqid();
    }
}