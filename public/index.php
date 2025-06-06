<?php 
require_once __DIR__ . '/../includes/app.php';

use Controllers\ProductoController;
use MVC\Router;
use Controllers\AppController;
use Controllers\CategoriaController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class, 'index']);

$router->get('/producto', [ProductoController::class, 'renderizarPagina']);
$router->post('/productos/guardarAPI', [ProductoController::class, 'guardarAPI']);
$router->get('/productos/buscarAPI', [ProductoController::class, 'buscarAPI']);
$router->post('/productos/modificarAPI', [ProductoController::class, 'modificarAPI']);
$router->post('/productos/marcarCompradoAPI', [ProductoController::class, 'marcarCompradoAPI']);
$router->post('/productos/eliminarAPI', [ProductoController::class, 'eliminarAPI']); 
$router->post('/categorias/guardarAPI', [CategoriaController::class, 'guardarAPI']);

$router->comprobarRutas();