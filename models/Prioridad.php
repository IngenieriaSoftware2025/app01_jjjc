<?php

namespace Model;

class Prioridad extends ActiveRecord {

    public static $tabla = 'prioridades';
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