var debounceTimer;

function sortTable(columnIndex) {
    var table, rows, changement, doitChanger, nombreChangement = 0, i;
    var ordreTri = "asc";
    table = document.getElementById("table_affichage");
    if (table.rows.tBodies[0].length > 1) {
        changement = true;

        // Tant qu'il reste des lignes à trier
        while (changement) {
            changement = false;
            rows = table.getElementsByTagName("TR");
    
            // On parcourt toutes les lignes pour voir s'il reste des lignes à trier
            for (i = 1; i < (rows.length - 1); i++) {
                doitChanger = false;
                // On compare les lignes adjacentes
                var ligneUne = rows[i].getElementsByTagName("TD")[columnIndex];
                var ligneDeux = rows[i + 1].getElementsByTagName("TD")[columnIndex];
    
                // On détermine si les lignes doivent être pérmutées en fonction de l'odre du tri
                if ((ordreTri == "asc" && ligneUne.innerHTML.toLowerCase() > ligneDeux.innerHTML.toLowerCase()) ||
                    (ordreTri == "desc" && ligneUne.innerHTML.toLowerCase() < ligneDeux.innerHTML.toLowerCase())) {
                    doitChanger = true;
                    break;
                }
            }
    
            if (doitChanger) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                changement = true;
                nombreChangement++;
            } else if (nombreChangement === 0) {
                ordreTri = ordreTri == "asc" ? "desc" : "asc";
                changement = true;
            }
        }
    
        // On met à jour l'icone
        updateSortIcons(columnIndex, ordreTri);
    }
}

function updateSortIcons(sortedColumnIndex, ordreTri) {
    // On retire l'icone du header qui a actuellement l'icone
    var headers = document.getElementById("table_affichage").getElementsByTagName("TH");
    for (var i = 0; i < headers.length; i++) {
        headers[i].innerHTML = headers[i].innerHTML.replace(" ▲", "").replace(" ▼", "");
    }

    // On ajoute l'icone au header cliqué
    var header = headers[sortedColumnIndex];
    if (ordreTri == "asc") {
        header.innerHTML += " ▲";
    } else {
        header.innerHTML += " ▼";
    }
}