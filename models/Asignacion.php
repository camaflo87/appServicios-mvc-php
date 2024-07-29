<?php

namespace Model;

class Asignacion extends ActiveRecord{
    protected static $tabla = 'asignacion_servicios';
    protected static $columnasDB = ['id','id_funcionario','id_servicio','fecha_servicio','lugar','semana','observaciones'];

    public $id;
    public $id_funcionario;
    public $id_servicio;
    public $fecha_servicio;
    public $lugar;
    public $semana; 
    public $observaciones;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->id_funcionario = $args['id_funcionario'] ?? '';
        $this->id_servicio = $args['id_servicio'] ?? '';
        $this->fecha_servicio = $args['fecha_servicio'] ?? '';
        $this->lugar = $args['lugar'] ?? '';
        $this->semana = $args['semana'] ?? date('W'); 
        $this->observaciones = $args['observaciones'] ?? '';
    } 

    // Validación asignación de servicios
    public function validar_asignacion() {
        if(!$this->id_funcionario) {
            self::$alertas['error'][] = 'Seleccione un funcionario';
        }

        if(!$this->id_servicio) {
            self::$alertas['error'][] = 'Seleccione un servicio';
        }

        
        if(!$this->fecha_servicio) {
            self::$alertas['error'][] = 'Falta la fecha de servicio';
        }
        
        if(!$this->lugar) {
            self::$alertas['error'][] = 'Falta el lugar del servicio';
        }

        if(!$this->observaciones){
            self::$alertas['error'][] = 'Agrega alguna observación sobre el servicio';
        }
        

        return self::$alertas;
    }

     // Obtener la asignacion de un servicio en en referencia a un funcionario
     public static function consultaServicio($valor = '') {
        //Campo1 (id_funcionario)
        $query = "SELECT * FROM " . static::$tabla ." WHERE id_funcionario=$valor ORDER BY fecha_servicio DESC LIMIT 1";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // // Identificar y unir los atributos de la BD
    // public function atributos() {
    //     $atributos = [];
    //     foreach(static::$columnasDB as $columna) {
    //         $atributos[$columna] = $this->$columna;
    //     }
       
    //     return $atributos;
    // }

    // // Sanitizar los datos antes de guardarlos en la BD
    // public function sanitizarAtributos() {
    //     $atributos = $this->atributos();
    //     $sanitizado = [];
    //     foreach($atributos as $key => $value ) {
    //         $sanitizado[$key] = self::$db->escape_string($value);
    //     }
    //     return $sanitizado;
    // }

     // Crear la asignacion de un servicio en referencia a un funcionario
     // crea un nuevo registro
    // public function crear() {
    //     // Sanitizar los datos
    //     $atributos = $this->sanitizarAtributos();

    //     // Insertar en la base de datos
    //     $query = " INSERT INTO " . static::$tabla . " ( ";
    //     $query .= join(', ', array_keys($atributos));
    //     $query .= " ) VALUES (' "; 
    //     $query .= join("', '", array_values($atributos));
    //     $query .= " ') ";

    //     // Resultado de la consulta
    //     $resultado = self::$db->query($query);
    //     return [
    //        'resultado' =>  $resultado
    //     ];
    // }

     


    
}