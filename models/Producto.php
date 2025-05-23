<?php

namespace Model;

class Producto extends ActiveRecord {
    public static $tabla = 'productos';
    public static $columnasDB = [
        'id',
        'nombre',
        'cantidad',
        'categoria_id',
        'prioridad_id',
        'comprado',
        'situacion'  
    ];

    public static $idTabla = 'id';
    public $id;
    public $nombre;
    public $cantidad;
    public $categoria_id;
    public $prioridad_id;
    public $comprado;
    public $situacion;  

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->cantidad = $args['cantidad'] ?? 1;
        $this->categoria_id = $args['categoria_id'] ?? null;
        $this->prioridad_id = $args['prioridad_id'] ?? null;
        $this->comprado = $args['comprado'] ?? 0;
        $this->situacion = $args['situacion'] ?? '1'; 
    }

 
}