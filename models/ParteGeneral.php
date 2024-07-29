<?php

namespace Model;

class ParteGeneral extends ActiveRecord
{

    public $turno;
    public $estado;
    public $grupo;
    public $categoria;
    public $novedad;

    public function __construct($args = [])
    {
        $this->turno = $args['turno'] ?? null;
        $this->estado = $args['estado'] ?? '';
        $this->grupo = $args['grupo'] ?? '';
        $this->categoria = $args['categoria'] ?? '';
        $this->novedad = $args['novedad'] ?? '';
    }
}
