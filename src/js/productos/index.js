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
    columns: [
        {
            title: 'No.',
            data: 'id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Producto', data: 'nombre', width: '25%' },
        { title: 'Cantidad', data: 'cantidad', width: '10%' },
        { title: 'Categoría', data: 'categoria_nombre', width: '20%' },
        { 
            title: 'Prioridad', 
            data: 'prioridad_nombre', 
            width: '15%',
            render: (data, type, row) => {
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
            width: '25%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center'>
                    <button class='btn btn-warning btn-sm modificar mx-1' 
                        data-id="${data}" 
                        data-nombre="${row.nombre}"  
                        data-cantidad="${row.cantidad}"  
                        data-categoria="${row.categoria_id}"  
                        data-prioridad="${row.prioridad_id}">
                        <i class='bi bi-pencil-square me-1'></i> Editar
                    </button>
                    <button class='btn btn-success btn-sm marcarComprado mx-1' 
                        data-id="${data}">
                        <i class="bi bi-check-circle me-1"></i>Comprado
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
    columns: [
        {
            title: 'No.',
            data: 'id',
            width: '5%',
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
                <div class='d-flex justify-content-center'>
                    <button class='btn btn-danger btn-sm desmarcarComprado mx-1' 
                        data-id="${data}">
                        <i class="bi bi-x-circle me-1"></i>No Comprado
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

// Función para eliminar producto desde las tablas
const eliminarProducto = async (event) => {
    const id = event.currentTarget.dataset.id;
    const nombre = event.currentTarget.dataset.nombre;
    
    // Confirmación antes de eliminar
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

// Función para eliminar producto desde el formulario
const eliminarProductoFormulario = async () => {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    
    if (!id) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "No hay producto seleccionado",
            text: "Debe seleccionar un producto para eliminar",
            showConfirmButton: true,
        });
        return;
    }
    
    // Confirmación antes de eliminar
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
    
    BtnEliminar.disabled = true;
    
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
            limpiarTodo();
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
    
    BtnEliminar.disabled = false;
}






BuscarProductos();
FormProductos.addEventListener('submit', GuardarProducto);
datatablePendientes.on('click', '.modificar', llenarFormulario);
//datatablePendientes.on('click', '.marcarComprado', marcarComprado);
//datatableComprados.on('click', '.desmarcarComprado', desmarcarComprado);
BtnModificar.addEventListener('click', ModificarProducto);
BtnLimpiar.addEventListener('click', limpiarTodo);