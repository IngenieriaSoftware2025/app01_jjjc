<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Producto;
use Model\Categoria;
use Model\Prioridad;
use MVC\Router;

class ProductoController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        $categorias = Categoria::all();
        $prioridades = Prioridad::all();
        
        $router->render('productos/index', [
            'categorias' => $categorias,
            'prioridades' => $prioridades
        ]);
    }

    public static function guardarAPI()
    {
       header('Content-Type: application/json; charset=utf-8');

        if(empty($_POST['nombre'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del producto es obligatorio'
            ]);
            return;
        }

        $_POST['cantidad'] = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);
        if(!$_POST['cantidad'] || $_POST['cantidad'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad debe ser un número entero positivo'
            ]);
            return;
        }

        $_POST['categoria_id'] = filter_var($_POST['categoria_id'], FILTER_VALIDATE_INT);
        if(!$_POST['categoria_id']) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una categoría válida'
            ]);
            return;
        }

        $_POST['prioridad_id'] = filter_var($_POST['prioridad_id'], FILTER_VALIDATE_INT);
        if(!$_POST['prioridad_id']) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una prioridad válida'
            ]);
            return;
        }

        try {
            $producto = new Producto([
                'nombre' => $_POST['nombre'],
                'cantidad' => $_POST['cantidad'],
                'categoria_id' => $_POST['categoria_id'],
                'prioridad_id' => $_POST['prioridad_id'],
                'comprado' => 0
            ]);

            if($producto->existeProductoCategoria()) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un producto con este nombre en la categoría seleccionada'
                ]);
                return;
            }

            $crear = $producto->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El producto ha sido registrado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

}