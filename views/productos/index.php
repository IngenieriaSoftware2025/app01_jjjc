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
                                <button class="btn btn-success" type="submit" id="BtnGuardar">
                                    Guardar
                                </button>
                            </div>

                            <div class="col-auto ">
                                <button class="btn btn-warning d-none" type="button" id="BtnModificar">
                                    Modificar
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



<script src="<?= asset('build/js/productos/index.js') ?>"></script>.0