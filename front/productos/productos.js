// URL base de la API
const API_URL = "http://localhost/floreria/apiproducto.php";

//  lista todos los productos
document.addEventListener("DOMContentLoaded", cargarProductos);

// Cargar todos los productos
function cargarProductos() {
    fetch(`${API_URL}?accion=producto`)
        .then(r => {
            if (!r.ok) throw new Error("Error al obtener productos");
            return r.json();
        })
        .then(mostrarProductos)
        .catch(err => console.error("Error al cargar productos:", err));
}

// Buscar un producto por nombre
function buscarProducto() {
    const nombre = document.getElementById("buscarNombre").value.trim();
    if(nombre) {
        fetch(`${API_URL}?accion=buscar_producto&nombre=${encodeURIComponent(nombre)}`)
            .then(r => {
                if (!r.ok) throw new Error("Error al buscar producto");
                return r.json();
            })
            .then(mostrarProductos)
            .catch(err => console.error("Error al buscar producto:", err));
    } else {
        alert("Ingresa un nombre para buscar.");
    }
}

// Mostrar la lista de productos en la tabla
function mostrarProductos(productos) {
    const tbody = document.querySelector("#tablaProductos tbody");
    tbody.innerHTML = "";

    if (!Array.isArray(productos) || productos.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6">No hay productos para mostrar</td></tr>`;
        return;
    }

    productos.forEach(prod => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${prod.id}</td>
            <td>${prod.nombre}</td>
            <td>$${prod.precio}</td>
            <td>${prod.descripcion}</td>
            <td>${prod.categoria}</td>
            <td>
                <button class="btn" onclick="editarProducto(${prod.id}, '${prod.nombre}', ${prod.precio}, '${prod.descripcion}', '${prod.categoria}')">Editar</button>
                <button class="btn btn-danger" onclick="eliminarProducto(${prod.id})">Eliminar</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}


// Guardar (agregar o editar) producto
function guardarProducto() {
    const id = document.getElementById("productoId").value;
    const nombre = document.getElementById("nombre").value.trim();
    const precio = document.getElementById("precio").value;
    const descripcion = document.getElementById("descripcion").value.trim();

    if(!nombre || !precio || !descripcion) {
        alert("Todos los campos son obligatorios.");
        return;
    }

    const datos = { 
    nombre, 
    precio: parseFloat(precio), 
    descripcion, 
    categoria: document.getElementById("categoria").value.trim()
};


    if(id) { 
        // Editar
        datos.id = parseInt(id);
        fetch(API_URL, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        })
        .then(r => r.json())
        .then(res => {
            alert(res.mensaje || res.error);
            cargarProductos();
            cancelarEdicion();
        })
        .catch(err => console.error("Error al actualizar producto:", err));
    } else {
    // Agregar
    fetch(API_URL, {
        method: 'POST',
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos) // datos incluye categoria
    })
    .then(res => res.json())
    .then(data => {
        alert(data.mensaje || data.error);
        cargarProductos();
        cancelarEdicion();
    })
    .catch(err => console.error("Error al agregar producto:", err));
}

    }


// Llenar formulario para edición
function editarProducto(id, nombre, precio, descripcion) {
    document.getElementById("productoId").value = id;
    document.getElementById("nombre").value = nombre;
    document.getElementById("precio").value = precio;
    document.getElementById("descripcion").value = descripcion;
    document.getElementById("formTitle").innerText = "Editar Producto";
}

// Cancelar edición y limpiar formulario
function cancelarEdicion() {
    document.getElementById("productoId").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("precio").value = "";
    document.getElementById("descripcion").value = "";
    document.getElementById("formTitle").innerText = "Agregar Producto";
}

// Eliminar producto
function eliminarProducto(id) {
    if(confirm("¿Seguro que deseas eliminar este producto?")) {
        fetch(API_URL, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(r => r.json())
        .then(res => {
            alert(res.mensaje || res.error);
            cargarProductos();
        })
        .catch(err => console.error("Error al eliminar producto:", err));
    }
}
