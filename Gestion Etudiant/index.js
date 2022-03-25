"use strict";

// idEtudiant de l'étudiant en cours de modification
let idEtudiant;

// fichier téléversé
let leFichier = null;

// mode en cours : consultation, ajout ou modification
let mode = "consultation";

window.onload = init

function init() {
    // charger les étudiants
    getLesEtudiants();

    // activer les infobulles
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(element => new bootstrap.Tooltip(element));

    // effacer le messages d'erreur à la réception du focus
    for (const input of document.getElementsByClassName('ctrl'))
        input.onfocus = () => {
            input.nextElementSibling.innerText = '';
            input.classList.remove("erreur");
        }

    // traitements associés au champ nom
    nom.onkeypress = (e) =>/[A-Za-z ]/.test(e.key);
    nom.onkeyup =  (e) => nom.value = nom.value.toUpperCase();
    nom.focus();

    // traitements associés au champ prenom
    prenom.onkeypress = (e) => /[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ '-]/.test(e.key);

    // traitement associé à la photo
    cible.onclick = () => fichier.click();
    cible.ondragover =  (e) => e.preventDefault();
    cible.ondrop =  (e) => {
        e.preventDefault();
        controlerFichier(e.dataTransfer.files[0]);
    }
    // activation de la popover de la zone cible
    new bootstrap.Popover(cible);

    fichier.onchange = function () { if (this.files.length > 0) controlerFichier(this.files[0]); };

    btnMaj.onclick = maj;
    retour.onclick = () => setMode('consultation');
    btnAjout.onclick = () => setMode('ajout');
}


function setMode(leMode) {
    mode = leMode
    if (mode === 'consultation') {
        zoneMaj.style.display = 'none';
        zoneAffichage.style.display = 'block';
    } else {
        zoneMaj.style.display = 'block';
        zoneAffichage.style.display = 'none';
        cible.innerHTML = `
            <i class="bi bi-cloud-upload" style="font-size: 4rem; color: #8b8a8a;"></i>
            <div>Cliquez ou déposer la photo ici</div>`
        if (mode === "modification") {
            btnMaj.innerText = "Modifier";
            titreZoneMaj.innerText = "Modifier la fiche  de l'étudiant";

        }  else {
            btnMaj.innerText = "Ajouter";
            titreZoneMaj.innerText = "Nouvel étudiant";
            nom.value = "";
            prenom.value = "";
            leFichier = null;
        }
    }
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
 * Afficher les données des étudiants en utilisant une mise en forme de type cadre
 * @param data {object} données sous la forme d'un objet json
 */
function afficher(data) {
    listeEtudiants.innerHTML = "";
    let row = document.createElement('div');
    row.classList.add("row");
    for (const etudiant of data) {

        let col = document.createElement('td');
        col.classList.add("col-xl-3", "col-lg-4", "col-md-4", "col-sm-6", "col-12");
        let carte = document.createElement('div');
        carte.id = idEtudiant;
        carte.classList.add("card", "mb-3");

        let entete = document.createElement('div');
        entete.classList.add("card-header", "d-flex" , "justify-content-between");

        // génération de l'icône de modification avec un alignement à droite
        let i = document.createElement('i');
        i.classList.add("bi", "bi-pencil", "text-danger");
        i.title = 'Modifier la fiche de l\'étudiant'
        // i.onclick = () => document.location.href = "modification.php?idEtudiant=" + idEtudiant;
        i.onclick = () => {
            setMode('modification');
            // alimentation de la zone de modification
            idEtudiant = etudiant.id;
            nom.value = etudiant.nom
            prenom.value =  etudiant.prenom;


        }
        new bootstrap.Tooltip(i, {placement: 'bottom'});
        entete.appendChild(i);

        let nomPrenom = document.createTextNode(etudiant.nom + ' ' + etudiant.prenom);
        entete.appendChild(nomPrenom)

        // génération de l'icône de suppression avec un alignement à droite
        i = document.createElement('i');
        i.classList.add("bi", "bi-x", "text-danger");
        i.title = "Supprimer la fiche de l'étudiant"
        new bootstrap.Tooltip(i, {placement: 'bottom'});
        i.onclick = () => Std.confirmer(() => supprimer(etudiant.id));
        entete.appendChild(i);

        carte.appendChild(entete);

        let corps = document.createElement('div');
        corps.classList.add("card-body");
        corps.style.height = '175px';
        if (etudiant.present === 1) {
            let img = new Image();
            img.src = "photo/" + etudiant.idEtudiant + '.jpg';
            img.alt = "";

            corps.appendChild(img);
        }
        carte.appendChild(corps);
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
    });
}


/**
 * Controle de la photo téléversée
 * @param file
 */
function controlerFichier(file) {
    leFichier = null;
    cible.innerHTML = "";
    messageCible.innerText = '';
    let controle = {
        file: file,
        taille: 50 * 1024,
        lesExtensions: ["jpg"],
    }
    if (!Std.fichierValide(file, controle)) {
        messageCible.innerText = controle.reponse;
        return;
    }
    // contrôle des dimensions
    let largeurMax = 150;
    let hauteurMax = 150;
    let img = new Image();
    img.src = window.URL.createObjectURL(file);
    img.onload = function () {
        window.URL.revokeObjectURL(this.src);
        if (img.width > largeurMax || img.height > hauteurMax)
            messageCible.innerText = `Les dimensions de l'image (${img.width} * ${img.height} dépassent les dimensions autorisées (${largeurMax} * ${hauteurMax}).`;
        else {
            leFichier = file;
            cible.appendChild(img);
        }
    }
    img.onerror = () => Std.afficherErreur("Il ne s'agit pas d'un fichier image.");
}


// ----------------------------------------------------------------------------
// traitement ajout ou modification
// ----------------------------------------------------------------------------

function maj() {
    // contrôle des champs de saisie
    let erreur = false
    for (const input of document.getElementsByClassName('ctrl'))
        if (!Std.controler(input)) erreur = true;

    // contrôle sur la photo
    if (leFichier == null) {
        messageCible.innerText = "Vous n'avez pas sélectionné de fichier";
        erreur = true
    }

    // si une erreur est détectée on quitte la fonction
    if (erreur) return;


    let monFormulaire = new FormData();
    monFormulaire.append('nom', nom.value);
    monFormulaire.append('prenom', prenom.value);
    monFormulaire.append('fichier', leFichier);
    if (mode === 'modification') {
        monFormulaire.append('id', idEtudiant);
    }
    $.ajax({
        url: 'ajax/maj.php',
        type: 'POST',
        data: monFormulaire,
        processData: false,
        contentType: false,
        dataType: "json",
        error: reponse => Std.afficherErreur(reponse.responseText),
        success: function() {

            getLesEtudiants();
            setMode('consultation');
        }
    })
}