"use strict";

// idEtudiant de l'étudiant en cours de modification
let idEtudiant;

// fichier téléversé
let leFichier = null;

window.onload = init

function init() {
    // charger les étudiants
    getLesEtudiants();

    // activer les popovers
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(element => new bootstrap.Popover(element));

    // effacer le messages d'erreur à la réception du focus
    for (const input of document.getElementsByClassName('ctrl'))
        input.onfocus = () => {
            input.nextElementSibling.innerText = '';
            input.classList.remove("erreur");
        }

    // traitements associés au champ nom
    nom.onkeypress = (e) => /[A-Za-z ]/.test(e.key);
    nom.onkeyup = (e) => nom.value = nom.value.toUpperCase();
    nom.focus();

    // traitements associés au champ prenom
    prenom.onkeypress = (e) => /[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ '-]/.test(e.key);

    // traitement associé à la photo
    cible.onclick = () => fichier.click();
    cible.ondragover = (e) => e.preventDefault();
    cible.ondrop = (e) => {
        e.preventDefault();
        controlerFichier(e.dataTransfer.files[0]);
    }
    // activation de la popover de la zone cible
    new bootstrap.Popover(cible);

    fichier.onchange = function () {
        if (this.files.length > 0) controlerFichier(this.files[0]);
    };

    // traitement du champ file associé aux modifications de photos


    btnAjouter.onclick = ajouter;
}

/**
 * Charge les données sur les étudiants
 * fonction appelée au chargement de la page mais aussi après chaque demande de suppression
 */
function getLesEtudiants() {
    $.ajax({
        url: 'ajax/getlesetudiants.php',
        dataType: "json",
        error: response => console.error(response.responseText),
        success: afficher
    });
}

/**
 * Affichage des coordfoné&es des étudiants et de leur photo dans des cadres
 * Utilisation de balises input pour permettre la modification des coordonnées
 * @param data
 */
function afficher(data) {
    listeEtudiants.innerHTML = "";
    let row = document.createElement('div');
    row.classList.add("row");
    for (const etudiant of data) {
        let id = etudiant.id;
        let col = document.createElement('div');
        col.classList.add("col-xl-3", "col-lg-4", "col-md-4", "col-sm-6", "col-12");
        let carte = document.createElement('div');
        carte.id = id;
        carte.classList.add("card", "mb-3");


        // ajout d'une balise input pour la modification du nom
        let inputNom = document.createElement('input');
        inputNom.classList.add("form-control");
        inputNom.id = 'nom' + id;
        inputNom.type = 'text';
        inputNom.value = etudiant.nom;
        inputNom.dataset.old = etudiant.nom;
        inputNom.pattern = "^[A-Z]([A-Z' ]*[A-Z])*$";
        inputNom.maxlength = 20;
        inputNom.onkeypress = (e) => /[A-Za-z ']/.test(e.key);
        inputNom.onchange = function () {
            this.value = this.value.replace(/\s{2,}/, " ").trim().toUpperCase();
            if (!Std.verifier(this)) return;
            $.ajax({
                url: 'ajax/modifiernom.php',
                type: 'POST',
                data: {nom: this.value, id: etudiant.id},
                dataType: "json",
                success: function () {
                    inputNom.classList.remove('erreur');
                    inputNom.style.color = 'green';
                },
                error: function (reponse) {
                    inputNom.classList.add('erreur');
                    Std.afficherErreur(reponse.responseText)
                }
            })
        };
        carte.appendChild(inputNom)

        //ajout d'une balise input pour la modification du prénom
        let inputPrenom = document.createElement('input');
        inputPrenom.classList.add("form-control");
        inputPrenom.id = 'prenom' + id;
        inputPrenom.type = 'text';
        inputPrenom.value = etudiant.prenom;
        inputPrenom.dataset.old = etudiant.prenom;
        inputPrenom.pattern = "^[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]([A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ '-]*[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])*$";
        inputPrenom.maxlength = 20;
        inputPrenom.onkeypress = (e) => /[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ ']/.test(e.key);
        inputPrenom.onchange = function () {
            this.value = this.value.replace(/\s{2,}/, " ").trim();
            if (!Std.verifier(this)) return;
            $.ajax({
                url: 'ajax/modifierprenom.php',
                type: 'POST',
                data: {prenom: this.value, id: etudiant.id},
                dataType: "json",
                success: function () {
                    inputPrenom.classList.remove('erreur');
                    inputPrenom.style.color = 'green';
                },
                error: function (reponse) {
                    inputPrenom.classList.add('erreur');
                    Std.afficherErreur(reponse.responseText)
                }
            })
        };
        carte.appendChild(inputPrenom)

        // Ajout de la zone d'upload
        let div = document.createElement('div');
        div.id = 'photo' + id
        div.classList.add('upload');
        // Si la photo existe on la place dans la zone
        if (etudiant.present === 1) {
            let img = document.createElement('img');
            img.src = "photo/" + etudiant.photo;
            img.alt = "";
            img.style.maxWidth = '150px';
            img.style.maxHeight = '150px';
            div.appendChild(img);
        }
        // définition des événements pour gérer le téléversement et le glisser déposer


        carte.appendChild(div);

        div = document.createElement('div');
        div.classList.add("d-flex", "justify-content-around");

        // ajout de l'icône de suppression en haut à droite du cadre
        let i = document.createElement('i');
        i.classList.add("bi", "bi-x", "text-danger", "float-end");
        i.title = "Supprimer  l'étudiant"
        new bootstrap.Tooltip(i, {placement: 'bottom'});
        i.onclick = () => Std.confirmer(() => supprimer(etudiant.id));
        div.appendChild(i)

        // ajout de l'icône permettant de supprimer la photo
        // l'icône sera cachée s'il n'y a pas de photo
        // l'icône doit posséder un identifiant afin de pouvoir le masquer/afficher : 'poubelle' + id



        carte.appendChild(div);
        col.appendChild(carte);
        row.appendChild(col);
        listeEtudiants.appendChild(row);
    }
}


/**
 * Suppression de l'étudiant dans dans la table etudiant et suppression du fichier associé dans le répertoire photo
 * @param id  identifiant de l'étudiant à supprimer
 */
function supprimer(id) {
    $.ajax({
        url: 'ajax/supprimer.php',
        type: 'POST',
        data: {id: id},
        dataType: "json",
        error: (reponse) => Std.afficherErreur(reponse.responseText),
        success: getLesEtudiants
    })
}


/**
 * contrôle la photo sélectionnée et lance la demande de modification côté serveur
 * @param   {object} file objet de type file contenant l'image à contrôler
 */
function modifierPhoto(file) {

}

// ------------------------------------------------
// fonction de traitement concernant l'ajout
// ------------------------------------------------


/**
 * Contrôle le fichier sélectionné au niveau de son extension et de sa taille
 * @param file {object} fichier à ajouter
 */
function controlerFichier(file) {
    messageCible.innerText = "";
    leFichier = null;
    cible.innerHTML = "";
    let controle = {
        file: file,
        taille: 50 * 1024,
        lesExtensions: ["jpg", "png"],
    }
    if (!Std.fichierValide(file, controle)) {
        messageCible.innerText = "La taille du fichier dépasse la taille autorisée";
        return;
    }
    // contrôle des dimensions
    let largeurMax = 150;
    let hauteurMax = 150;
    let img = new Image();
    img.src = window.URL.createObjectURL(file);
    img.onload = function () {
        window.URL.revokeObjectURL(this.src);
        if (img.width > largeurMax || img.height > hauteurMax) {
            messageCible.innerText = `Les dimensions de l'image (${img.width} * ${img.height}) dépassent les dimensions autorisées (${largeurMax} * ${hauteurMax}).`;
        } else {
            leFichier = file;
            cible.appendChild(img);
        }
    }
    img.onerror = () => {
        messageCible.innerText = "Il ne s'agit pas d'un fichier image.";
    }
}

/**
 * contrôle et demande d'ajout d'un nouvel étudant
 * La photo est optionnelle
 */
function ajouter() {
    // contrôle des champs de saisie


    // contrôle sur la photo


    // si une erreur est détectée on quitte la fonction


    // appel ajax

}

// ------------------------------------------------
// fonction de traitement concernant l'effacement d'un champ
// ------------------------------------------------


/**
 * Demande d'effacement de la photo de l'étudiant
 * @param id : identifiant de l'étudiant
 */
function effacerPhoto(id) {

}

