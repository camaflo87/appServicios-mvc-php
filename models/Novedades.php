<?php

namespace Model;

class Novedades extends ActiveRecord
{
    protected static $tabla = 'novedades';
    protected static $columnasDB = ['id', 'novedad'];

    public $id;
    public $novedad;
    public $cant;
    public $numerico;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->novedad = $args['novedad'] ?? '';
    }
}
