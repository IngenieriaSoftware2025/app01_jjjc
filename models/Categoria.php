<?php

namespace Model;

class Categoria extends ActiveRecord {

    public static $tabla = 'categorias';
    public static $columnasDB = [
        'id',
        'nombre'
    ];

    public static $idTabla = 'id';
    public $id;
    public $nombre;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }
}