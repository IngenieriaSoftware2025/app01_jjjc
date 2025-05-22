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
    ];

    public static $idTabla = 'id';
    public $id;
    public $nombre;
    public $cantidad;
    public $categoria_id;
    public $prioridad_id;
    public $comprado;


    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->cantidad = $args['cantidad'] ?? 1;
        $this->categoria_id = $args['categoria_id'] ?? null;
        $this->prioridad_id = $args['prioridad_id'] ?? null;
        $this->comprado = $args['comprado'] ?? 0;
    }

    public function existeProductoCategoria() {
    $query = "SELECT COUNT(*) as total FROM " . self::$tabla . 
            " WHERE nombre = " . self::$db->quote($this->nombre) . 
            " AND categoria_id = " . self::$db->quote($this->categoria_id) . 
            " AND comprado = 0";
    
    $resultado = self::$db->query($query);
    $row = $resultado->fetch(\PDO::FETCH_ASSOC);
    $total = isset($row['total']) ? $row['total'] : 0;
    
    return ($total > 0);
}
}