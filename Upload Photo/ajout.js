"use strict";

// fichier téléversé
let leFichier = null;

window.onload = init;

function init() {
    // paramétrage de la zone d'upload
    new bootstrap.Popover(cible);

    btnAjouter.onclick = ajouter;

    // Déclencher le clic sur le champ de type file lors d'un clic dans la zone cible
    cible.onclick = () => fichier.click();

    // Lancer la fonction controlerFichier si un fichier a été sélectionné dans l'explorateur
    fichier.onchange =  () => { if (fichier.files.length > 0) controlerFichier(fichier.files[0]) };

    // définition des gestionnaires d'événements pour déposer un fichier dans la cible
    cible.ondragover = (e) => e.preventDefault();
    cible.ondrop = (e) => {
        e.preventDefault();
        controlerFichier(e.dataTransfer.files[0]);
    }
}

/**
 * Contrôle le fichier sélectionné au niveau de son extension et de sa taille
 * @param file {object} fichier à ajouter
 */
function controlerFichier(file) {
    messageCible.innerText = "";
    messageCible.classList.remove("messageErreur");
    leFichier = null;
    cible.innerHTML = "";
    // contrôle par la méthode Std.fichierValide(file, controle)
    let controle = {
        taille : 30 * 1024,
        lesExtensions : ['png', 'jpg']
    }
    if (!Std.fichierValide(file, controle)) {
        Std.afficherErreur(controle.reponse);
        return false;
    }
    // contrôle des dimensions
    let largeurMax = 150;
    let hauteurMax = 150;
    let img = new Image();
    img.src = window.URL.createObjectURL(file);
    img.onload = () => {
        window.URL.revokeObjectURL(img.src);
        if (img.width > largeurMax || img.height > hauteurMax) {
            Std.afficherErreur(`Les dimensions de l'image (${img.width} * ${img.height}) dépassent les dimensions autorisées`)

        } else {
           leFichier =  file;
           cible.appendChild(img);
        }
    }
    img.onerror = () =>  Std.afficherErreur("Il ne s'agit pas d'un fichier image");
}

function ajouter() {
    if (leFichier == null) {
        Std.afficherErreur("Vous devez sélectionner une photo ou le faire glisser dans la zone");
        return;
    }
    let monFormulaire = new FormData();
    monFormulaire.append('fichier', leFichier);
    $.ajax({
        url: 'ajax/ajouter.php',
        type: 'POST',
        data: monFormulaire,
        processData: false,
        contentType: false,
        dataType: "json",
        error: (reponse) => console.error(reponse.responseText),
        success:  () => location.href="index.php"
    });
}




