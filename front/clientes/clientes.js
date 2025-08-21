const API_URL = "http://localhost/floreria/api.php";

// Cargar clientes al inicio
document.addEventListener("DOMContentLoaded", obtenerClientes);

async function obtenerClientes() {
    try {
        const res = await fetch(`${API_URL}?accion=usuario`);
        const data = await res.json();

        const tabla = document.getElementById("clientesTabla");
        tabla.innerHTML = "";

        data.forEach(cliente => {
            tabla.innerHTML += `
                <tr>
                    <td>${cliente.id}</td>
                    <td>${cliente.nombre}</td>
                    <td>${cliente.celular}</td>
                    <td>
                        <button class="btn-editar" onclick="editarCliente(${cliente.id}, '${cliente.nombre}', '${cliente.celular}')">Editar</button>
                        <button class="btn-eliminar" onclick="eliminarCliente(${cliente.id})">Eliminar</button>
                    </td>
                </tr>
            `;
        });
    } catch (error) {
        console.error("Error al obtener clientes:", error);
    }
}

// Registrar cliente
document.getElementById("clienteForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const nombre = document.getElementById("nombre").value.trim();
    const celular = document.getElementById("celular").value.trim();
    const mensaje = document.getElementById("mensaje");

    try {
        const res = await fetch(API_URL, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ nombre, celular })
        });
        const data = await res.json();

        mensaje.textContent = data.mensaje;
        mensaje.style.color = data.mensaje.includes("registrado") ? "green" : "red";

        obtenerClientes();
        document.getElementById("clienteForm").reset();
    } catch (error) {
        mensaje.textContent = "Error al registrar cliente";
        mensaje.style.color = "red";
    }
});

// Editar cliente
function editarCliente(id, nombre, celular) {
    const nuevoNombre = prompt("Nuevo nombre:", nombre);
    const nuevoCelular = prompt("Nuevo celular:", celular);

    if (nuevoNombre && nuevoCelular) {
        fetch(API_URL, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id, nombre: nuevoNombre, celular: nuevoCelular })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.mensaje);
            obtenerClientes();
        });
    }
}

// Eliminar cliente
function eliminarCliente(id) {
    if (confirm("¿Seguro que quieres eliminar este cliente?")) {
        fetch(API_URL, {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.mensaje);
            obtenerClientes();
        });
    }
}
// Buscar cliente en tiempo real
document.getElementById("buscador").addEventListener("input", async function () {
    const nombre = this.value.trim();

    if (nombre === "") {
        obtenerClientes(); // Si está vacío, recarga todos
        return;
    }

    try {
        const res = await fetch(`http://localhost/floreria/api.php?accion=buscar_usuario&nombre=${encodeURIComponent(nombre)}`);
        const data = await res.json();

        const tabla = document.getElementById("clientesTabla");
        tabla.innerHTML = "";

        if (data.length > 0) {
            data.forEach(cliente => {
                tabla.innerHTML += `
                    <tr>
                        <td>${cliente.id}</td>
                        <td>${cliente.nombre}</td>
                        <td>${cliente.celular}</td>
                        <td>
                            <button class="btn-editar" onclick="editarCliente(${cliente.id}, '${cliente.nombre}', '${cliente.celular}')">Editar</button>
                            <button class="btn-eliminar" onclick="eliminarCliente(${cliente.id})">Eliminar</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            tabla.innerHTML = `<tr><td colspan="4">No se encontraron resultados</td></tr>`;
        }
    } catch (error) {
        console.error("Error al buscar cliente:", error);
    }
});
