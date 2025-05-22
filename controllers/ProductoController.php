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
        // VALIDACIÓN DE PRODUCTO DUPLICADO
        $nombreProducto = $_POST['nombre'];
        $sql = "SELECT COUNT(*) as total FROM productos WHERE LOWER(nombre) = LOWER('$nombreProducto') AND situacion = '1' AND comprado = 0";
        $resultado = self::fetchArray($sql);
        
        if ($resultado[0]['total'] > 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un producto con el nombre "' . $nombreProducto . '". Por favor ingresa un nombre diferente.'
            ]);
            return;
        }

        $producto = new Producto([
            'nombre' => $_POST['nombre'],
            'cantidad' => $_POST['cantidad'],
            'categoria_id' => $_POST['categoria_id'],
            'prioridad_id' => $_POST['prioridad_id'],
            'comprado' => 0,
            'situacion' => '1'
        ]);

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


        public static function buscarAPI()
    {
            header('Content-Type: application/json; charset=utf-8');

        try {
            $sql = "SELECT p.*, c.nombre as categoria_nombre, pr.nombre as prioridad_nombre 
                    FROM productos p
                    JOIN categorias c ON p.categoria_id = c.id
                    JOIN prioridades pr ON p.prioridad_id = pr.id
                    ORDER BY p.comprado ASC, c.nombre ASC, p.prioridad_id ASC";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los productos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }



    
    public static function modificarAPI()
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = $_POST['id'];

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
        $producto = Producto::find($id);
        
        if(!$producto) {
            http_response_code(404);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Producto no encontrado'
            ]);
            return;
        }

        $nombreProducto = $_POST['nombre'];
        $sql = "SELECT COUNT(*) as total FROM productos WHERE LOWER(nombre) = LOWER('$nombreProducto') AND situacion = '1' AND comprado = 0 AND id != $id";
        $resultado = self::fetchArray($sql);
        
        if ($resultado[0]['total'] > 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro producto con el nombre "' . $nombreProducto . '". Por favor ingresa un nombre diferente.'
            ]);
            return;
        }
        
        $producto->sincronizar([
            'nombre' => $_POST['nombre'],
            'cantidad' => $_POST['cantidad'],
            'categoria_id' => $_POST['categoria_id'],
            'prioridad_id' => $_POST['prioridad_id']
        ]);
        
        $producto->actualizar();

        http_response_code(200);
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'El producto ha sido modificado exitosamente'
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


  public static function marcarCompradoAPI()
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = $_POST['id'];
        $comprado = $_POST['comprado'];

        try {
            $producto = Producto::find($id);
            
            if(!$producto) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Producto no encontrado'
                ]);
                return;
            }
            
            $producto->sincronizar([
                'comprado' => $comprado
            ]);
            
            $producto->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estado del producto actualizado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function eliminarAPI()
{
    header('Content-Type: application/json; charset=utf-8');

    if(empty($_POST['id'])) {
        http_response_code(400);
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'ID del producto es obligatorio'
        ]);
        return;
    }

    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    if(!$id) {
        http_response_code(400);
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'ID del producto debe ser un número válido'
        ]);
        return;
    }

    try {
        $producto = Producto::find($id);
        
        if(!$producto) {
            http_response_code(404);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Producto no encontrado'
            ]);
            return;
        }
        
        $resultado = $producto->eliminar();
        
        if($resultado) {
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El producto ha sido eliminado correctamente'
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'No se pudo eliminar el producto'
            ]);
        }
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'Error al eliminar el producto',
            'detalle' => $e->getMessage(),
        ]);
    }
}

}

 
