let currentDate = new Date();
let markedDays = [1, 4, 10, 15]; // Días con envíos

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

    // Espacios antes del primer día
    for (let i = 0; i < firstDay; i++) {
        row.appendChild(document.createElement("td"));
    }

    // Días del mes
    for (let day = 1; day <= lastDate; day++) {
        let cell = document.createElement("td");
        cell.textContent = day;

        if (markedDays.includes(day)) {
            cell.classList.add("marked");
        }

        row.appendChild(cell);

        if ((firstDay + day) % 7 === 0) {
            calendarBody.appendChild(row);
            row = document.createElement("tr");
        }
    }

    // Rellenar última fila
    if (row.children.length > 0) {
        calendarBody.appendChild(row);
    }
}

function prevMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

document.addEventListener("DOMContentLoaded", renderCalendar);
