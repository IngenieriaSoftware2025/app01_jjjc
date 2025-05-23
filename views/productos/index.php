<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            min-height: 100vh;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border: none;
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-warning {
            background: linear-gradient(45deg, #fdcb6e, #e17055);
            border: none;
            color: white;
        }

        .btn-danger {
            background: linear-gradient(45deg, #fd79a8, #e84393);
            border: none;
        }

        .btn-secondary {
            background: linear-gradient(45deg, #636e72, #2d3436);
            border: none;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #ddd;
        }

        .form-control:focus, .form-select:focus {
            border-color: #74b9ff;
            box-shadow: 0 0 0 3px rgba(116, 185, 255, 0.25);
        }

        .table thead {
            background: linear-gradient(45deg, #74b9ff, #0984e3);
            color: white;
        }

        .text-primary {
            color: #2d3436 !important;
        }
   
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            height: 55px;
            padding: 0 20px;
            border-radius: 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .floating-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
            color: white;
        }

        .floating-btn i {
            font-size: 1.2rem;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        #formCategoria .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }

        #formCategoria .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        }

        #formCategoria .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
        }

        #formCategoria .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
        }

        /* Responsivo para dispositivos móviles */
        @media (max-width: 768px) {
            .floating-btn {
                bottom: 20px;
                right: 20px;
                height: 50px;
                padding: 0 15px;
                font-size: 12px;
            }
            
            .floating-btn i {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="row justify-content-center p-3">
            <div class="col-lg-10">
                <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
                    <div class="card-body p-3">
                        <div class="row mb-3">
                            <h5 class="text-center mb-2">¡Bienvenido a tu Organizador de Compras!</h5>
                            <h4 class="text-center mb-2 text-primary">GESTIÓN DE PRODUCTOS</h4>
                        </div>

                        <div class="row justify-content-center p-5 shadow-lg">
                            <form id="FormProductos">
                                <input type="hidden" id="id" name="id">

                                <div class="row mb-3 justify-content-center">
                                    <div class="col-lg-6">
                                        <label for="nombre" class="form-label">NOMBRE DEL PRODUCTO</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del producto">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="cantidad" class="form-label">CANTIDAD</label>
                                        <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" value="1" placeholder="Cantidad">
                                    </div>
                                </div>

                                <div class="row mb-3 justify-content-center">
                                    <div class="col-lg-6">
                                        <label for="categoria_id" class="form-label">CATEGORÍA</label>
                                        <select name="categoria_id" class="form-select" id="categoria_id">
                                            <option value="" class="text-center"> -- SELECCIONE CATEGORÍA -- </option>
                                            <?php foreach($categorias as $c): ?>
                                                <option value="<?= $c->id ?>"><?= $c->nombre ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="prioridad_id" class="form-label">PRIORIDAD</label>
                                        <select name="prioridad_id" class="form-select" id="prioridad_id">
                                            <option value="" class="text-center"> -- SELECCIONE PRIORIDAD -- </option>
                                            <?php foreach($prioridades as $p): ?>
                                                <option value="<?= $p->id ?>"><?= $p->nombre ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-5">
                                    <div class="col-auto">
                                        <button class="btn btn-warning" type="submit" id="BtnGuardar">
                                            Guardar
                                        </button>
                                    </div>

                                    <div class="col-auto ">
                                        <button class="btn btn-warning d-none" type="button" id="BtnModificar">
                                            Modificar
                                        </button>
                                    </div>

                                    <div class="col-auto">
                                        <button class="btn btn-danger d-none" type="button" id="BtnEliminar">
                                            <i class="bi bi-trash me-1"></i>Eliminar
                                        </button>
                                    </div>

                                    <div class="col-auto">
                                        <button class="btn btn-secondary" type="reset" id="BtnLimpiar">
                                            Limpiar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center p-3">
            <div class="col-lg-10">
                <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
                    <div class="card-body p-3">
                        <h3 class="text-center">PRODUCTOS PENDIENTES</h3>

                        <div class="table-responsive p-2">
                            <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableProductosPendientes">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center p-3">
            <div class="col-lg-10">
                <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
                    <div class="card-body p-3">
                        <h3 class="text-center">PRODUCTOS COMPRADOS</h3>

                        <div class="table-responsive p-2">
                            <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableProductosComprados">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <button class="floating-btn" id="btnAgregarCategoria" data-bs-toggle="modal" data-bs-target="#modalCategoria">
        <i class="bi bi-plus"></i>
        Agregar Categoría
    </button>

    <div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-grid me-2"></i>Agregar Nueva Categoría
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formCategoria">
                        <div class="mb-3">
                            <label for="nombreCategoria" class="form-label fw-bold">
                                <i class="bi bi-tag me-2"></i>Nombre de la Categoría
                            </label>
                            <input type="text" class="form-control" id="nombreCategoria" name="nombre" 
                                   placeholder="Escribe el nombre de la categoría" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary" id="btnGuardarCategoria">
                                <i class="bi bi-check-circle me-2"></i>Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= asset('build/js/productos/index.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>