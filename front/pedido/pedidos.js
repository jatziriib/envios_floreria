const API_URL = "http://localhost/floreria/pedidos.php";

// Carga los pedidos
async function cargarPedidos() {
    try {
        const res = await fetch(`${API_URL}?accion=pedidos`);
        const data = await res.json();

        const tbody = document.querySelector("#tablaPedidos tbody");
        tbody.innerHTML = "";

        data.forEach(pedido => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${pedido.id}</td>
                <td>${pedido.id_usuario}</td>
                <td>${pedido.metodo_pago}</td>
                <td>${pedido.estado_pago}</td>
                <td>${pedido.total}</td>
                <td>${pedido.fecha_envio}</td>
                <td>${pedido.recibe}</td>
                
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        console.error("Error al obtener pedidos:", error);
    }
}

// Buscar pedido por nombre de quien recibe
async function buscarPedido() {
    const recibe = document.getElementById("buscarPedido").value.trim();
    if (!recibe) return cargarPedidos();

    try {
        const res = await fetch(`${API_URL}?accion=buscar_pedido&recibe=${encodeURIComponent(recibe)}`);
        const data = await res.json();

        const tbody = document.querySelector("#tablaPedidos tbody");
        tbody.innerHTML = "";

        data.forEach(pedido => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${pedido.id}</td>
                <td>${pedido.id_usuario}</td>
                <td>${pedido.metodo_pago}</td>
                <td>${pedido.estado_pago}</td>
                <td>${pedido.total}</td>
                <td>${pedido.fecha_envio}</td>
                <td>${pedido.recibe}</td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        console.error("Error al buscar pedido:", error);
    }
}

// Registrar pedido (POST)
document.getElementById("formPedido").addEventListener("submit", async (e) => {
    e.preventDefault();

    const pedido = {
        id_usuario: document.getElementById("id_usuario").value,
        metodo_pago: document.getElementById("metodo_pago").value,
        estado_pago: document.getElementById("estado_pago").value,
        costo_envio: document.getElementById("costo_envio").value,
        total: document.getElementById("total").value,
        fecha_envio: document.getElementById("fecha_envio").value,
        lugar: document.getElementById("lugar").value,
        descripcion: document.getElementById("descripcion").value,
        recibe: document.getElementById("recibe").value
    };

    try {
        const res = await fetch(`${API_URL}?accion=registrar_pedido`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(pedido)
        });

        const data = await res.json();
        alert(data.mensaje || "Pedido registrado");
        cargarPedidos();
        e.target.reset();
    } catch (error) {
        console.error("Error al registrar pedido:", error);
    }
});

// Cargar pedidos al iniciar
cargarPedidos();
