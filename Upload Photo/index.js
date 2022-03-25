"use strict";

window.onload = init;

function init() {
    $.ajax({
        url: 'ajax/getlesphotos.php',
        dataType: "json",
        error: response => console.error(response.responseText),
        success: afficher
    });
}

/**
 * Affichage des photos dans des conteneurq de type carte
 * L'entête affiche le nom de la photo et uu icône de suppression
 * Le corps affiche la photo
 * @param data
 */
function afficher(data) {
    lesPhotos.innerHTML = '';
    let row = document.createElement('div');
    row.classList.add("row");
    for (const nomFichier of data) {
        let col = document.createElement('div');
        col.classList.add("col-xl-3", "col-lg-4", "col-md-4", "col-sm-6", "col-12");

        let carte = document.createElement('div');
        carte.id = nomFichier
        carte.classList.add("card", "mb-3");

        let entete = document.createElement('div');
        entete.classList.add("card-header");

        // génération de l'icône de suppression avec un alignement à droite
        let i = document.createElement('i');
        i.classList.add("bi", "bi-x", "text-danger", "float-end");
        i.title = 'Supprimer la photo'
        new bootstrap.Tooltip(i, {placement: 'bottom'});
        i.onclick = () => Std.confirmer(() => supprimer(nomFichier));
        entete.appendChild(i);

        // intégration du nom du fichier dans l'entête avec un alignement à gauche
        let nom = document.createElement('div');
        nom.classList.add('float-start');
        nom.innerText = getNom(nomFichier);
        entete.appendChild(nom);

        // intégration de l'entête dans la carte
        carte.appendChild(entete);

        // génération du corps de la carte
        let corps = document.createElement('div');
        corps.classList.add("card-body");
        corps.style.height = '180px';

        // génération d'une balise img pour afficher la photo dans le corps de la carte
        let img = document.createElement('img');
        img.src = `photo/${nomFichier}`;
        img.alt = "";
        img.style.maxWidth = '150px';
        img.style.maxHeight = '150px';

        // intégration de l'image dans le corps
        corps.appendChild(img);

        // intégration du corps dans la carte
        carte.appendChild(corps);

        col.appendChild(carte);
        row.appendChild(col);
    }
    lesPhotos.appendChild(row);
}


/**
 * Lance la suppression côté serveur
 * @param {string} nomFichier  nom du fichier à supprimer
 */
function supprimer(nomFichier) {
    $.ajax({
        url: 'ajax/supprimer.php',
        type: 'POST',
        data: {nomFichier: nomFichier},
        dataType: "json",
        error: (reponse) => console.error(reponse.responseText),
        success: init
    });
}


/**
 *
 * @param filename nom avec  extension
 * @returns {string} nom sans l'extension
 */
function getNom(filename) {
    // recherche de la position du dernier point
    let position = filename.lastIndexOf('.');
    if (position <= 0) return filename;
    return filename.slice(0, position);
}


