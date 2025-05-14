document.addEventListener("DOMContentLoaded", function () {
    const table = document.querySelector("#myTable");  // Usa l'ID o la classe corretta
    const headers = table.querySelectorAll("th");
    const tableBody = table.querySelector("tbody");
    const rows = Array.from(tableBody.querySelectorAll("tr"));

    // Funzione di confronto per l'ordinamento
    const compare = (index, ascending) => (rowA, rowB) => {
        const cellA = rowA.querySelectorAll("td")[index].innerText.toLowerCase();
        const cellB = rowB.querySelectorAll("td")[index].innerText.toLowerCase();

        if (!isNaN(cellA) && !isNaN(cellB)) {
            return ascending ? cellA - cellB : cellB - cellA;
        }

        if (cellA < cellB) {
            return ascending ? -1 : 1;
        }
        if (cellA > cellB) {
            return ascending ? 1 : -1;
        }
        return 0;
    };

    // Funzione per riordinare la tabella
    const sortTable = (index, ascending) => {
        const sortedRows = rows.sort(compare(index, ascending));
        
        // Riordina le righe
        tableBody.replaceChildren(...sortedRows);
    };

    // Aggiunge il listener agli header per rendere le colonne ordinabili
    headers.forEach((header, index) => {
        let ascending = true;
        header.style.cursor = "pointer";  // Aggiungi il cursore a puntatore

        header.addEventListener("click", () => {
            sortTable(index, ascending);
            ascending = !ascending;  // Alterna l'ordinamento
        });
    });
});
