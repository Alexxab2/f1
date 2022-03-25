"use strict";

window.onload = init;

let leFichier = null; // contient le fichier uploadé

function init() {
    // chargement des documents
    $.ajax({
        url: 'ajax/getlesdocuments.php',
        dataType: "json",
        error: response => console.error(response.responseText),
        success: afficher
    });
}

/**
 * Alimentation tableau et affichage des données
 * @param {object} data
 */
function afficher(data) {
    for (const doc of data) {
        // création d'une ligne avec un attribut id afin de pouvoir la supprimer facilement
        let tr = lesLignes.insertRow();
        tr.id = doc.id;

        // création de la première cellule contenant les icônes d'action possible sur le document
        let td = tr.insertCell();
        td.style.display = 'table-cell';
        td.style.verticalAlign = 'middle';
        td.style.textAlign = 'center';

        if (doc.present === 1) {
            let i = document.createElement('i');
            i.classList.add("bi", "bi-file-earmark-pdf", "text-primary", "fs-2");
            new bootstrap.Popover(i, {placement: 'left', content : 'Voir le document', trigger : 'hover' });
            i.onclick = () => window.open('document/doc' + doc.id + '.pdf', 'pdf');
            td.appendChild(i);
        }

        // ajout dans la première colonne d'une icône déclenchant la demande de suppression du fichier
        let i = document.createElement('i');
        i.classList.add("bi", "bi-x", "text-danger", "fs-2");
        new bootstrap.Popover(i, {placement: 'bottom', content : 'Supprimer le fichier', trigger : 'hover' });
        i.onclick = () => Std.confirmer( () => supprimer(doc.id));
        td.appendChild(i);

        // création de la balise input permettant de modifier le titre
        let titre = document.createElement("input");
        titre.type = 'text';
        titre.classList.add('form-control');
        titre.maxLength = 150;
        titre.minLength = 10;
        titre.required = true;
        titre.value = doc.titre;
        titre.onchange = () => {
            if (!Std.verifier(titre)) return ;
            $.ajax({
                url: 'ajax/modifiertitre.php',
                type: 'POST',
                data: {titre: titre.value, id: doc.id},
                dataType: "json",
                success: function () {
                    titre.classList.remove('erreur');
                    Std.afficherSucces("Modification enregistrée");
                },
                error: function (request) {
                    titre.classList.add('erreur');
                    Std.afficherErreur(request.responseText)
                }
            })
        };
        // insertion d'une nouvelle cellule contenant la balise input
        tr.insertCell(1).appendChild(titre);

        // création de la colonne affichant la date de création
        td = tr.insertCell(2)
        td.style.display = 'table-cell';
        td.style.verticalAlign = 'middle';
        td.style.textAlign = 'center';
        td.innerText = doc.date;
    }
}

/**
 * Demande de suppression du document
 * @param id
 */
function supprimer(id) {
    $.ajax({
        url: 'ajax/supprimer.php',
        type: 'POST',
        data: {id: id},
        dataType: "json",
        error: (reponse) => Std.afficherErreur(request.responseText),
        success: function () {
            // mise à jour de l'interface
            let ligne = document.getElementById(id);
            ligne.parentNode.removeChild(ligne);
        }
    })
}

