"use strict";

// fichier téléversé
let leFichier = null;

window.onload = init;

function init() {
    // activation des popovers
    new bootstrap.Popover(cible);

    btnAjouter.onclick = ajouter;

    // Déclencher l'ouverture de l'explorateur de fichier lors d'un clic dans la zone cible
    cible.onclick = () => fichier.click();

    // Lancer la fonction controlerFichier si un fichier a été sélectionné dans l'explorateur
    fichier.onchange = () => { if (fichier.files.length > 0) controlerFichier(fichier.files[0])};

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
    // effacer le message de la zone clble et initialiser la variable leFichier
    messageCible.innerHTML = "";
    messageCible.classList.remove('messageErreur');
    leFichier = null;

    // contrôle de la taille
    let taille = 1 * 1024 * 1024;
    if(file.size > taille) {
        messageCible.innerText = "La taille du fichier dépasse la taille autorisée"
        messageCible.classList.add("messageErreur");
        return false;
    }

    // contrôle de l'extension

    let lesExtensions = ['pdf'];
    // récupération de l'extension : découpons au niveau du '.' et prenons le dernier élement
    let eltFichier = file.name.split('.');
    let extension = eltFichier[eltFichier.length - 1].toLowerCase();
    if(lesExtensions.indexOf(extension) === -1) {
        messageCible.innerText = "Cette extension de fichier n'est pas acceptée";
        messageCible.classList.add("messageErreur");
        return false;
    }

    // affichage du nom du fichier téléversé et mémorisation du fichier dans la variable leFichier
    messageCible.innerText = file.name;
    leFichier = file;

}


function ajouter() {
    // controle sur la variable leFichier
    if (leFichier == null) {
        Std.AfficherErreur("Vous n'avez pas sélectionné de fichier");
        return;
    }

    // transfert du fichier vers le serveur dans le répertoire sélectionné
    let monFormulaire = new FormData();
    monFormulaire.append('fichier', leFichier );
    $.ajax({
        url : 'ajax/ajouter.php',
        type : 'POST',
        data : monFormulaire,
        processData : false,
        contentType : false,
        dataType : 'json',
        error : (reponse) => Std.afficherErreur(reponse.responseText),
        success : () => location.href = "index.php"
    })
}
