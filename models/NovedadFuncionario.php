<?php

namespace Model;

class NovedadFuncionario extends ActiveRecord{
    protected static $tabla = 'novedades_funcionarios';
    protected static $columnasDB = ['id_funcionario','id_novedad','observacion'];

    public $id_funcionario;
    public $id_novedad;
    public $observacion;
    
    public function __construct($args = [])
    {
        $this->id_funcionario = $args['id_funcionario'] ?? '';
        $this->id_novedad = $args['id_novedad'] ?? '';
        $this->observacion = $args['observacion'] ?? 'Ninguna';
    }
} 