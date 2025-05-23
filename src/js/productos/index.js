import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario, Toast } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormProductos = document.getElementById('FormProductos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnEliminar = document.getElementById('BtnEliminar');


const GuardarProducto = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormProductos, ['id'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormProductos);

    const url = '/app01_jjjc/productos/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarProductos();
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error)
    }
    BtnGuardar.disabled = false;
}
const BuscarProductos = async () => {
    const url = '/app01_jjjc/productos/buscarAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            const productosPendientes = data.filter(producto => producto.comprado == 0);
            const productosComprados = data.filter(producto => producto.comprado == 1);

            datatablePendientes.clear().draw();
            datatablePendientes.rows.add(productosPendientes).draw();

            datatableComprados.clear().draw();
            datatableComprados.rows.add(productosComprados).draw();
            
            Toast.fire({
                icon: 'success',
                title: mensaje
            });
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error)
    }
}

const datatablePendientes = new DataTable('#TableProductosPendientes', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    order: [[3, 'asc'], [4, 'asc']], 
    drawCallback: function(settings) {
        const api = this.api();
        const rows = api.rows({page: 'current'}).nodes();
        let lastCategory = null;
        let lastPriority = null;

        api.rows({page: 'current'}).every(function(rowIdx, tableLoop, rowLoop) {
            const data = this.data();
            const currentCategory = data.categoria_nombre;
            
            let currentPriority = data.prioridad_nombre;
            if (typeof currentPriority === 'string' && currentPriority.includes('span')) {
                const tempElement = document.createElement('div');
                tempElement.innerHTML = currentPriority;
                currentPriority = tempElement.textContent || tempElement.innerText;
            }

            const currentRow = rows[rowLoop];

            if (lastCategory !== currentCategory) {
                const categorySeparator = document.createElement('tr');
                categorySeparator.className = 'category-separator';
                categorySeparator.innerHTML = `
                    <td colspan="6" style="
                        background-color: #f8f9fa;
                        border-top: 3px solid #007bff;
                        border-bottom: 1px solid #dee2e6;
                        padding: 12px 15px;
                        text-align: left;
                        font-weight: bold;
                        font-size: 1.1em;
                        color: #007bff;
                        text-transform: uppercase;
                    ">
                        ${currentCategory}
                    </td>
                `;
                
                currentRow.parentNode.insertBefore(categorySeparator, currentRow);
                lastCategory = currentCategory;
                lastPriority = null; 
            }

            if (lastPriority !== currentPriority) {
                const prioritySeparator = document.createElement('tr');
                prioritySeparator.className = 'priority-separator';
                prioritySeparator.innerHTML = `
                    <td colspan="6" style="
                        background-color: #f1f3f4;
                        border-left: 3px solid #6c757d;
                        padding: 8px 25px;
                        text-align: left;
                        font-weight: 600;
                        font-size: 0.9em;
                        color: #495057;
                        font-style: italic;
                    ">
                        → ${currentPriority}
                    </td>
                `;
                
                currentRow.parentNode.insertBefore(prioritySeparator, currentRow);
                lastPriority = currentPriority;
            }
        });
    },
    columns: [
        {
            title: 'No.',
            data: 'id',
            width: '5%',
            orderable: false,
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Producto', data: 'nombre', width: '20%' },
        { title: 'Cantidad', data: 'cantidad', width: '8%' },
        { title: 'Categoría', data: 'categoria_nombre', width: '15%' },
        { 
            title: 'Prioridad', 
            data: 'prioridad_nombre', 
            width: '12%',
            render: (data, type, row) => {
                if (type === 'sort') {
                    return data === 'Alta' ? '1' : data === 'Media' ? '2' : '3';
                }
                let badge = 'badge bg-secondary';
                if (data === 'Alta') {
                    badge = 'badge bg-danger';
                } else if (data === 'Media') {
                    badge = 'badge bg-warning text-dark';
                } else if (data === 'Baja') {
                    badge = 'badge bg-success';
                }
                return `<span class="${badge}">${data}</span>`;
            }
        },
        {
            title: 'Acciones',
            data: 'id',
            width: '40%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center flex-wrap'>
                    <button class='btn btn-warning btn-sm modificar mx-1 my-1' 
                        data-id="${data}" 
                        data-nombre="${row.nombre}"  
                        data-cantidad="${row.cantidad}"  
                        data-categoria="${row.categoria_id}"  
                        data-prioridad="${row.prioridad_id}">
                        <i class='bi bi-pencil-square me-1'></i> Editar
                    </button>
                    <button class='btn btn-success btn-sm marcarComprado mx-1 my-1' 
                        data-id="${data}">
                        <i class="bi bi-check-circle me-1"></i>Comprado
                    </button>
                    <button class='btn btn-danger btn-sm eliminar mx-1 my-1' 
                        data-id="${data}"
                        data-nombre="${row.nombre}">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                </div>`;
            }
        }
    ]
});

const datatableComprados = new DataTable('#TableProductosComprados', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    order: [[3, 'asc']], 
    drawCallback: function(settings) {
        const api = this.api();
        const rows = api.rows({page: 'current'}).nodes();
        let lastCategory = null;

        api.rows({page: 'current'}).every(function(rowIdx, tableLoop, rowLoop) {
            const data = this.data();
            const currentCategory = data.categoria_nombre;
            const currentRow = rows[rowLoop];

            if (lastCategory !== currentCategory) {
                const categorySeparator = document.createElement('tr');
                categorySeparator.className = 'category-separator-comprados';
                categorySeparator.innerHTML = `
                    <td colspan="5" style="
                        background-color: #f8f9fa;
                        border-top: 3px solid #28a745;
                        border-bottom: 1px solid #dee2e6;
                        padding: 12px 15px;
                        text-align: left;
                        font-weight: bold;
                        font-size: 1.1em;
                        color: #28a745;
                        text-transform: uppercase;
                    ">
                        ${currentCategory}
                    </td>
                `;
                
                currentRow.parentNode.insertBefore(categorySeparator, currentRow);
                lastCategory = currentCategory;
            }
        });
    },
    columns: [
        {
            title: 'No.',
            data: 'id',
            width: '5%',
            orderable: false,
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Producto', 
            data: 'nombre', 
            width: '35%',
            render: (data) => `<span style="text-decoration: line-through;">${data}</span>` 
        },
        { title: 'Cantidad', data: 'cantidad', width: '10%' },
        { title: 'Categoría', data: 'categoria_nombre', width: '25%' },
        {
            title: 'Acciones',
            data: 'id',
            width: '25%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center flex-wrap'>
                    <button class='btn btn-info btn-sm desmarcarComprado mx-1 my-1' 
                        data-id="${data}">
                        <i class="bi bi-x-circle me-1"></i>No Comprado
                    </button>
                    <button class='btn btn-danger btn-sm eliminar mx-1 my-1' 
                        data-id="${data}"
                        data-nombre="${row.nombre}">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                </div>`;
            }
        }
    ]
});


const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('id').value = datos.id;
    document.getElementById('nombre').value = datos.nombre;
    document.getElementById('cantidad').value = datos.cantidad;
    document.getElementById('categoria_id').value = datos.categoria;
    document.getElementById('prioridad_id').value = datos.prioridad;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    FormProductos.scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });

}

const limpiarTodo = () => {
    FormProductos.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarProducto = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormProductos, [''])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormProductos);

    const url = '/app01_jjjc/productos/modificarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarProductos();
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error);
    }
    BtnModificar.disabled = false;
}

const marcarComprado = async (event) => {
    const id = event.currentTarget.dataset.id;
    
    const body = new FormData();
    body.append('id', id);
    body.append('comprado', 1);

    const url = '/app01_jjjc/productos/marcarCompradoAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            Toast.fire({
                icon: 'success',
                title: mensaje
            });
            BuscarProductos();
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error);
    }
}

const desmarcarComprado = async (event) => {
    const id = event.currentTarget.dataset.id;
    
    const body = new FormData();
    body.append('id', id);
    body.append('comprado', 0);

    const url = '/app01_jjjc/productos/marcarCompradoAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            Toast.fire({
                icon: 'success',
                title: mensaje
            });
            BuscarProductos();
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error);
    }
}

const eliminarProducto = async (event) => {
    const id = event.currentTarget.dataset.id;
    const nombre = event.currentTarget.dataset.nombre;
    
    const resultado = await Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Quieres eliminar el producto "${nombre}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (!resultado.isConfirmed) {
        return;
    }
    
    const body = new FormData();
    body.append('id', id);

    const url = '/app01_jjjc/productos/eliminarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Eliminado",
                text: mensaje,
                showConfirmButton: true,
            });
            BuscarProductos();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Error de conexión al eliminar el producto",
            showConfirmButton: true,
        });
    }
}

const manejarCategoria = () => {
    const formCategoria = document.getElementById('formCategoria');
    if (!formCategoria) return;

    formCategoria.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        const btnGuardar = document.getElementById('btnGuardarCategoria');
        const nombreCategoria = document.getElementById('nombreCategoria').value.trim();
        
        if (!nombreCategoria) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo requerido',
                text: 'Debe ingresar el nombre de la categoría'
            });
            return;
        }
        
        const textoOriginal = btnGuardar.innerHTML;
        btnGuardar.innerHTML = 'Guardando...';
        btnGuardar.disabled = true;
        
        const formData = new FormData();
        formData.append('nombre', nombreCategoria);
        
        try {
            const response = await fetch('/app01_jjjc/categorias/guardarAPI', {
                method: 'POST',
                body: formData
            });
            
            const datos = await response.json();
            
            if (datos.codigo === 1) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Categoría agregada correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                document.getElementById('formCategoria').reset();
                bootstrap.Modal.getInstance(document.getElementById('modalCategoria')).hide();
                
                setTimeout(() => location.reload(), 1000);
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: datos.mensaje
                });
            }
            
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor'
            });
        }
        
        btnGuardar.innerHTML = textoOriginal;
        btnGuardar.disabled = false;
    });
};

manejarCategoria();





BuscarProductos();
FormProductos.addEventListener('submit', GuardarProducto);
datatablePendientes.on('click', '.modificar', llenarFormulario);
datatablePendientes.on('click', '.marcarComprado', marcarComprado);
datatableComprados.on('click', '.desmarcarComprado', desmarcarComprado);
BtnModificar.addEventListener('click', ModificarProducto);
BtnLimpiar.addEventListener('click', limpiarTodo);
datatablePendientes.on('click', '.eliminar', eliminarProducto);
datatableComprados.on('click', '.eliminar', eliminarProducto);
