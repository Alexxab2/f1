"use strict";

window.onload = init;


function init() {
    // mise en place des gestionnaires d'événement
    btnAjouter.onclick = ajouter;

    new bootstrap.Popover(cible);


}


function ajouter() {
    titre.nextElementSibling.innerText = titre.validationMessage
    if (!titre.checkValidity()) return;

    // demande d'ajout
    $.ajax({
        url: 'ajax/ajouter.php',
        type: 'POST',
        data: {titre : titre.value},
        dataType: "json",
        error: reponse => {
            Std.afficherErreur(reponse.responseText);
        },
        success: () => {
            Std.afficherSucces('Document ajouté');
            titre.value = '';
        }
    })
}
