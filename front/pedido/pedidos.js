const API_URL = "http://localhost/floreria/api_pedidos_detalle.php";

let pedidoEditando = null; 

document.addEventListener("DOMContentLoaded", cargarPedidos);

function cargarPedidos() {
    fetch(`${API_URL}?accion=pedidos`)
        .then(r => r.json())
        .then(mostrarPedidos)
        .catch(err => console.error(err));
}

function buscarPedido() {
    const recibe = document.getElementById("buscarRecibe").value.trim();
    fetch(`${API_URL}?accion=buscar_pedido&recibe=${encodeURIComponent(recibe)}`)
        .then(r => r.json())
        .then(mostrarPedidos)
        .catch(err => console.error(err));
}

function mostrarPedidos(pedidos) {
    const tbody = document.querySelector("#tablaPedidos tbody");
    tbody.innerHTML = "";
    if (!pedidos.length) {
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
            <td>${p.total_final}</td>
            <td>${p.estado_pago}</td>
            <td>
                <button class="btn btn-warning" onclick="cargarParaEditar(${p.id})">Editar</button>
                <button class="btn btn-danger" onclick="eliminarPedido(${p.id})">Eliminar</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}


function agregarProducto(id_producto = "", cantidad = "") {
    const div = document.createElement("div");
    div.classList.add("form-group");
    div.innerHTML = `
        <label>ID Producto:</label>
        <input type="number" class="id_producto" value="${id_producto}">
        <label>Cantidad:</label>
        <input type="number" class="cantidad" value="${cantidad}">
    `;
    document.getElementById("productosContainer").appendChild(div);
}

function guardarPedido() {
    const id_usuario = parseInt(document.getElementById("id_usuario").value);
    const metodo_pago = document.getElementById("metodo_pago").value.trim();
    const estado_pago = document.getElementById("estado_pago").value.trim();
    const costo_envio = parseFloat(document.getElementById("costo_envio").value);
    const fecha_envio = document.getElementById("fecha_envio").value;
    const lugar = document.getElementById("lugar").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();
    const recibe = document.getElementById("recibe").value.trim();

    const productos = [];
    document.querySelectorAll("#productosContainer .form-group").forEach(grupo => {
        const id_producto = parseInt(grupo.querySelector(".id_producto").value);
        const cantidad = parseInt(grupo.querySelector(".cantidad").value);
        if (id_producto && cantidad) {
            productos.push({ id_producto, cantidad });
        }
    });

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

    let metodo = 'POST';
    if (pedidoEditando) {
        metodo = 'PUT';
        datos.id_pedido = pedidoEditando;
    }

    fetch(API_URL, {
        method: metodo,
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos)
    })
    .then(r => r.json())
    .then(res => {
        alert(res.message || res.error);
        limpiarFormulario();
        cargarPedidos();
    })
    .catch(err => console.error(err));
}

function eliminarPedido(id) {
    if (!confirm("¿Eliminar este pedido?")) return;
    fetch(`${API_URL}?id=${id}`, { method: 'DELETE' })
        .then(r => r.json())
        .then(res => {
            alert(res.message || res.error);
            cargarPedidos();
        })
        .catch(err => console.error(err));
}

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
        .catch(err => console.error(err));
}

function limpiarFormulario() {
    pedidoEditando = null;
    document.getElementById("formPedido").reset();
    document.getElementById("productosContainer").innerHTML = "";
}
