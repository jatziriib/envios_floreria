const API_URL = "http://localhost/floreria/repartidor.php";

document.addEventListener("DOMContentLoaded", cargarRepartidores);

// Cargar lista
function cargarRepartidores() {
    fetch(`${API_URL}?accion=repartidor`)
        .then(res => res.json())
        .then(mostrarRepartidores)
        .catch(err => console.error("Error:", err));
}

// Mostrar en tabla
function mostrarRepartidores(data) {
    const tbody = document.getElementById("tablaRepartidores");
    tbody.innerHTML = "";

    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="6">No hay repartidores</td></tr>`;
        return;
    }

    data.forEach(r => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${r.id}</td>
            <td>${r.id_pedido}</td>
            <td>${r.nombre}</td>
            <td>${r.celular}</td>
            <td>${r.fecha}</td>
            <td>
                <button class="btn" onclick="editarRepartidor(${r.id}, ${r.id_pedido}, '${r.nombre}', '${r.celular}', '${r.fecha}')">Editar</button>
                <button class="btn btn-danger" onclick="eliminarRepartidor(${r.id})">Eliminar</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Buscar
function buscarRepartidor() {
    const nombre = document.getElementById("buscarNombre").value.trim();
    if (!nombre) {
        cargarRepartidores();
        return;
    }
    fetch(`${API_URL}?accion=buscar_repartidor&nombre=${encodeURIComponent(nombre)}`)
        .then(res => res.json())
        .then(mostrarRepartidores)
        .catch(err => console.error("Error:", err));
}

// Guardar (Agregar o Editar)
function guardarRepartidor() {
    const id = document.getElementById("repartidorId").value;
    const id_pedido = document.getElementById("id_pedido").value;
    const nombre = document.getElementById("nombre").value.trim();
    const celular = document.getElementById("celular").value.trim();
    const fecha = document.getElementById("fecha").value;

    if (!id_pedido || !nombre || !celular || !fecha) {
        alert("Todos los campos son obligatorios");
        return;
    }

    const datos = { id_pedido: parseInt(id_pedido), nombre, celular, fecha };

    if (id) {
        datos.id = parseInt(id);
        fetch(API_URL, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        })
        .then(res => res.json())
        .then(res => {
            alert(res.success ? "Repartidor actualizado" : res.error);
            cargarRepartidores();
            cancelarEdicion();
        });
    } else {
        fetch(API_URL, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        })
        .then(res => res.json())
        .then(res => {
            alert(res.success ? "Repartidor agregado" : res.error);
            cargarRepartidores();
            cancelarEdicion();
        });
    }
}

// Editar
function editarRepartidor(id, id_pedido, nombre, celular, fecha) {
    document.getElementById("repartidorId").value = id;
    document.getElementById("id_pedido").value = id_pedido;
    document.getElementById("nombre").value = nombre;
    document.getElementById("celular").value = celular;
    document.getElementById("fecha").value = fecha;
    document.getElementById("formTitle").innerText = "Editar Repartidor";
}

// Cancelar
function cancelarEdicion() {
    document.getElementById("repartidorId").value = "";
    document.getElementById("id_pedido").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("celular").value = "";
    document.getElementById("fecha").value = "";
    document.getElementById("formTitle").innerText = "Agregar Repartidor";
}

// Eliminar
function eliminarRepartidor(id) {
    if (confirm("¿Seguro que quieres eliminar este repartidor?")) {
        fetch(`${API_URL}?id=${id}`, { method: "DELETE" })
            .then(res => res.json())
            .then(res => {
                alert(res.success ? "Repartidor eliminado" : res.error);
                cargarRepartidores();
            });
    }
}
