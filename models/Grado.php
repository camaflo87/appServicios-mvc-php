<?php

namespace Model;

class Grado extends ActiveRecord{
    protected static $tabla = 'grados';
    protected static $columnasDB = ['id', 'grado','abreviacion','categoria'];

    public $id;
    public $grado;
    public $abreviacion;
    public $categoria;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->grado = $args['grado'] ?? '';
        $this->abreviacion = $args['abreviacion'] ?? '';
        $this->categoria = $args['categoria'] ?? '';
    }
}