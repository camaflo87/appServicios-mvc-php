<?php

namespace Model;

class Grupo extends ActiveRecord
{
    protected static $tabla = 'grupos';
    protected static $columnasDB = ['id', 'grupo'];

    public $id;
    public $grupo;
    public $novedad;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->grupo = $args['grupo'] ?? '';
    }
}
