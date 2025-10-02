const API_URL = "http://localhost/floreria/api_pedidos_detalle.php";
const API_USUARIOS = "http://localhost/floreria/api.php?accion=usuario";
const API_PRODUCTOS = "http://localhost/floreria/apiproducto.php?accion=producto";

let pedidoEditando = null;

document.addEventListener("DOMContentLoaded", () => {
    cargarPedidos();
    cargarUsuarios();
    agregarProducto(); // al iniciar, deja un campo de producto vacío
});

// ==================== Cargar Pedidos ====================

function cargarPedidos() {
    fetch(`${API_URL}?accion=pedidos`)
        .then(r => r.json())
        .then(mostrarPedidos)
        .catch(err => console.error("Error al cargar pedidos:", err));
}

function buscarPedido() {
    const recibe = document.getElementById("buscarRecibe").value.trim();
    if (!recibe) {
        cargarPedidos();
        return;
    }
    fetch(`${API_URL}?accion=buscar_pedido&recibe=${encodeURIComponent(recibe)}`)
        .then(r => r.json())
        .then(mostrarPedidos)
        .catch(err => console.error("Error al buscar pedido:", err));
}

function mostrarPedidos(pedidos) {
    const tbody = document.querySelector("#tablaPedidos tbody");
    tbody.innerHTML = "";

    if (!Array.isArray(pedidos) || pedidos.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7">No hay pedidos</td></tr>`;
        return;
    }

    pedidos.forEach(p => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${p.id}</td>
            <td>${p.recibe}</td>
            <td>${p.fecha_envio}</td>
            <td>
                ${p.productos && p.productos.length 
                    ? p.productos.map(prod => `${prod.nombre} (x${prod.cantidad})`).join("<br>")
                    : "Sin productos"}
            </td>
            <td>${p.total_final || 0}</td>
            <td>${p.estado_pago}</td>
            <td>
                <button class="btn btn-warning" onclick="cargarParaEditar(${p.id})">Editar</button>
                <button class="btn btn-danger" onclick="eliminarPedido(${p.id})">Eliminar</button>
               <a href="pedido_pdf.php?id=${p.id}" target="_blank">
        <button class="btn btn-info">PDF</button>
    </a>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// ==================== Cargar Usuarios y Productos ====================

function cargarUsuarios() {
    fetch(API_USUARIOS)
        .then(r => r.json())
        .then(usuarios => {
            const select = document.getElementById("id_usuario");
            select.innerHTML = `<option value="">Seleccione un usuario</option>`;
            usuarios.forEach(u => {
                const option = document.createElement("option");
                option.value = u.id;
                option.textContent = u.nombre;
                select.appendChild(option);
            });
        })
        .catch(err => console.error("Error al cargar usuarios:", err));
}

function cargarProductos() {
    fetch(API_PRODUCTOS)
        .then(r => r.json())
        .then(productos => {
            document.querySelectorAll(".id_producto").forEach(select => {
                select.innerHTML = `<option value="">Seleccione un producto</option>`;
                productos.forEach(p => {
                    const option = document.createElement("option");
                    option.value = p.id;
                    option.textContent = p.nombre;
                    select.appendChild(option);
                });
            });
        })
        .catch(err => console.error("Error al cargar productos:", err));
}

// ==================== Agregar Producto dinámico ====================

function agregarProducto(id_producto = "", cantidad = "") {
    const div = document.createElement("div");
    div.classList.add("form-group", "producto-item", "mb-2");
    div.innerHTML = `
        <label>Producto:</label>
        <select class="id_producto form-control mb-1"></select>
        <label>Cantidad:</label>
        <input type="number" class="cantidad form-control" value="${cantidad}">
    `;
    document.getElementById("productosContainer").appendChild(div);
    cargarProductos();

    if (id_producto) {
        // Esperamos un poco a que cargue el select
        setTimeout(() => {
            div.querySelector(".id_producto").value = id_producto;
        }, 300);
    }
}

// ==================== Guardar Pedido ====================

function guardarPedido() {
    const id_usuario = parseInt(document.getElementById("id_usuario").value);
    const metodo_pago = document.getElementById("metodo_pago").value.trim();
    const estado_pago = document.getElementById("estado_pago").value.trim();
    const costo_envio = parseFloat(document.getElementById("costo_envio").value) || 0;
    const fecha_envio = document.getElementById("fecha_envio").value;
    const lugar = document.getElementById("lugar").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();
    const recibe = document.getElementById("recibe").value.trim();

    const productos = [];
    document.querySelectorAll("#productosContainer .producto-item").forEach(grupo => {
        const id_producto = parseInt(grupo.querySelector(".id_producto").value);
        const cantidad = parseInt(grupo.querySelector(".cantidad").value);
        if (id_producto && cantidad) {
            productos.push({ id_producto, cantidad });
        }
    });

    if (!id_usuario || !productos.length || !recibe) {
        alert("Complete todos los campos obligatorios.");
        return;
    }

    const datos = {
        id_usuario,
        metodo_pago,
        estado_pago,
        costo_envio,
        fecha_envio,
        lugar,
        descripcion,
        recibe,
        productos
    };

    let metodo = "POST";
    if (pedidoEditando) {
        metodo = "PUT";
        datos.id_pedido = pedidoEditando;
    }

    fetch(API_URL, {
        method: metodo,
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos)
    })
    .then(r => r.json())
    .then(res => {
        alert(res.mensaje || res.error || "Operación realizada");
        limpiarFormulario();
        cargarPedidos();
    })
    .catch(err => console.error("Error al guardar pedido:", err));
}

// ==================== Eliminar Pedido ====================

function eliminarPedido(id) {
    if (!confirm("¿Eliminar este pedido?")) return;
    fetch(API_URL, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    })
    .then(r => r.json())
    .then(res => {
        alert(res.mensaje || res.error || "Pedido eliminado");
        cargarPedidos();
    })
    .catch(err => console.error("Error al eliminar pedido:", err));
}

// Editar Pedido

function cargarParaEditar(id) {
    fetch(`${API_URL}?accion=pedidos`)
        .then(r => r.json())
        .then(pedidos => {
            const pedido = pedidos.find(p => p.id == id);
            if (!pedido) return alert("Pedido no encontrado");

            pedidoEditando = id;

            document.getElementById("id_usuario").value = pedido.id_usuario;
            document.getElementById("metodo_pago").value = pedido.metodo_pago;
            document.getElementById("estado_pago").value = pedido.estado_pago;
            document.getElementById("costo_envio").value = pedido.costo_envio;
            document.getElementById("fecha_envio").value = pedido.fecha_envio;
            document.getElementById("lugar").value = pedido.lugar;
            document.getElementById("descripcion").value = pedido.descripcion;
            document.getElementById("recibe").value = pedido.recibe;

            document.getElementById("productosContainer").innerHTML = "";
            if (pedido.productos) {
                pedido.productos.forEach(prod => {
                    agregarProducto(prod.id_producto, prod.cantidad);
                });
            }
        })
        .catch(err => console.error("Error al cargar pedido:", err));
}

// ==================== Limpiar Formulario ====================

function limpiarFormulario() {
    pedidoEditando = null;
    document.getElementById("formPedido").reset();
    document.getElementById("productosContainer").innerHTML = "";
    agregarProducto(); // deja un producto vacío
}
