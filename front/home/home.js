let currentDate = new Date();
let pedidos = [];   // Guardamos todos los pedidos de la API
let markedDays = []; // Guardamos los días del mes actual que tienen pedidos

// 🔹 Cargar pedidos desde la API
async function cargarFechas() {
    try {
        const response = await fetch("http://localhost/floreria/api_pedidos_simple.php?accion=todos");
        pedidos = await response.json();

        actualizarDiasMarcados();
        renderCalendar();
    } catch (error) {
        console.error("Error cargando fechas:", error);
    }
}

// 🔹 Actualizar los días marcados según el mes actual
function actualizarDiasMarcados() {
    let year = currentDate.getFullYear();
    let month = currentDate.getMonth() + 1; // Mes 1-12

    markedDays = pedidos
        .map(p => p.fecha)  // YA VIENE COMO YYYY-MM-DD
        .filter(fecha => {
            let [y, m] = fecha.split("-");
            return parseInt(y) === year && parseInt(m) === month;
        })
        .map(fecha => parseInt(fecha.split("-")[2])); // Día
}


// 🔹 Renderizar calendario
function renderCalendar() {
    const calendarBody = document.querySelector("#calendar tbody");
    const monthYear = document.getElementById("monthYear");
    const monthNames = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    let year = currentDate.getFullYear();
    let month = currentDate.getMonth();

    monthYear.textContent = `${monthNames[month]} ${year}`;
    calendarBody.innerHTML = "";

    let firstDay = new Date(year, month, 1).getDay();
    let lastDate = new Date(year, month + 1, 0).getDate();

    let row = document.createElement("tr");

    // Espacios vacíos antes del primer día
    for (let i = 0; i < firstDay; i++) {
        row.appendChild(document.createElement("td"));
    }

    // Días del mes
    for (let day = 1; day <= lastDate; day++) {
        let cell = document.createElement("td");
        cell.textContent = day;

        if (markedDays.includes(day)) {
            cell.classList.add("marked");

            // Evento clic -> mostrar pedidos de ese día
            cell.addEventListener("click", () => mostrarPedidosDelDia(year, month, day));
        }

        row.appendChild(cell);

        if ((firstDay + day) % 7 === 0) {
            calendarBody.appendChild(row);
            row = document.createElement("tr");
        }
    }

    // Rellenar última fila si quedó incompleta
    if (row.children.length > 0) {
        calendarBody.appendChild(row);
    }
}

// 🔹 Mostrar pedidos de un día
function mostrarPedidosDelDia(year, month, day) {
    const enviosSection = document.querySelector(".envios");
    enviosSection.innerHTML = ""; // limpiar lista

    // Formato YYYY-MM-DD
    let fechaSeleccionada = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

    // Tomamos solo la parte YYYY-MM-DD de cada fecha en la API
  let pedidosDelDia = pedidos.filter(p => p.fecha.startsWith(fechaSeleccionada));



    if (pedidosDelDia.length === 0) {
        enviosSection.innerHTML = "<p>No hay envíos este día.</p>";
        return;
    }

    pedidosDelDia.forEach(p => {
    let div = document.createElement("div");
    div.classList.add("envio");
    div.innerHTML = `
        <span><b>Fecha:</b> ${p.fecha}</span>
        <span><b>Recibe:</b> ${p.recibe}</span>
        <span><b>Lugar:</b> ${p.lugar}</span>
        <span><b>Descripción:</b> ${p.descripcion}</span>
        <span><b>Productos:</b> ${p.productos}</span>
        <span><b>Celular:</b> ${p.celular}</span>
    `;
    enviosSection.appendChild(div);
});

}


// 🔹 Cambiar de mes
function prevMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    actualizarDiasMarcados();
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    actualizarDiasMarcados();
    renderCalendar();
}

// 🔹 Iniciar
document.addEventListener("DOMContentLoaded", cargarFechas);
