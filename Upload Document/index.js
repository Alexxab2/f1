"use strict";

window.onload = init;

function init() {
    // chargement des documents
    $.ajax({
        url: 'ajax/getlesdocuments.php',
        type: 'POST',
        dataType: "json",
        error: response => console.error(response.responseText),
        success: afficher
    });
}


/**
 * Affichage des fichiers du répertoire
 * @param data {object} json contenant le nom des fichiers trouvés dans le répertoire document
 */
function afficher(data) {
    lesLignes.innerHTML = "";
    for (const nomFichier of data) {
        let tr = lesLignes.insertRow();
        tr.id = nomFichier;

        let td = tr.insertCell(0)
        // ajout dans la première colonne d'une icône déclenchant la demande de suppression du fichier
        td.style.display = 'table-cell';
        td.style.verticalAlign = 'middle';
        td.style.textAlign = 'center';
        let i = document.createElement('i');
        i.classList.add("bi", "bi-x", "text-danger");
        i.style.fontSize = '1.5rem';
        i.title = 'Supprimer le fichier'
        new bootstrap.Tooltip(i, {placement: 'bottom'});
        i.onclick = () => Std.confirmer(() => supprimer(nomFichier))
        td.appendChild(i);

        td = tr.insertCell(1);
        td.style.display = 'table-cell';
        td.style.verticalAlign = 'middle';
        let a = document.createElement("a");
        a.href = "document/" + nomFichier;
        a.target = 'doc';
        a.innerText = nomFichier;
        td.appendChild(a);
    }

    // initialisation composant datatable
    $("#leTableau").DataTable({
        language: {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/fr_fr.json"},
        aoColumns : [{"bSortable": false}, null],
        paging : false,  // pas de pagination
        bInfo : false, // pas de zone d'information
        retrieve: true,
    });
}


/**
 * Demande du suppression d'un fichier
 * @param nomFichier {string} Nom du fichier à supprimer
 */
function supprimer(nomFichier) {
    $.ajax({
        url: 'ajax/supprimer.php',
        type: 'POST',
        data: {
            nomFichier: nomFichier,
        },
        dataType: "json",
        error: (reponse) => Std.afficherErreur(reponse),
        success:  () => {
            // suppression de la ligne sur l'interface
            //let tr = document.getElementById(nomFichier);
            // tr.parentNode.removeChild(tr);
           // lesLignes.removeChild(tr);
            init();
        }
    });
}