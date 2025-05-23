<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Categoria;

class CategoriaController extends ActiveRecord
{
    public static function guardarAPI()
    {
        header('Content-Type: application/json; charset=utf-8');

        if(empty($_POST['nombre'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la categoría es obligatorio'
            ]);
            return;
        }

        try {
            $nombreCategoria = $_POST['nombre'];
            $sql = "SELECT COUNT(*) as total FROM categorias WHERE LOWER(nombre) = LOWER('$nombreCategoria')";
            $resultado = self::fetchArray($sql);
            
            if ($resultado[0]['total'] > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe una categoría con el nombre "' . $nombreCategoria . '"'
                ]);
                return;
            }

            $categoria = new Categoria([
                'nombre' => $_POST['nombre']
            ]);

            $crear = $categoria->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Categoría agregada correctamente',
                'id' => $crear['id']
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la categoría',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}

