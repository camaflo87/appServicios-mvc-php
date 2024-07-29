<?php

namespace Model;

class Servicios extends ActiveRecord{
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'servicio'];

    public $id;
    public $servicio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->servicio = $args['servicio'] ?? '';
    }
}