<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Upload</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="../formation.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
    <script src="http://guy.verghote.free.fr/composant/std.js"></script>

    <link rel="stylesheet" href="style.css">

    <script src="index.js"></script>
</head>
<body>
<div class="container">
    <div class="input-group p-1 border my-2">
        <a class="btn btn-primary text-white" href="../../">Développement Web</a>
        <a class="btn btn-secondary text-white" href="..">Upload</a>
        <button class="btn btn-danger" style="cursor:default">Gestion des étudiants</button>
    </div>
    <div id="zoneAffichage" class="border p-2">
        <div class="d-flex justify-content-between">
            <h2> Gestion des étudiants</h2>
            <i id="btnAjout" data-bs-toggle="tooltip" title="Nouvel étudiant"
               class="bi bi-plus-square text-danger fs-2"></i>
        </div>
        <div id='listeEtudiants' class="pt-3 text-center"></div>
    </div>

    <div id="zoneMaj" class="border p-2" style="display:none">
        <div class="d-flex justify-content-between mt-2 ">
            <h2 id="titreZoneMaj"></h2>
            <i id='retour' data-bs-toggle="tooltip" title="Retour à l'accueil"
               class="bi bi-box-arrow-left text-primary fs-2"></i>
        </div>
        <div class="row mt-1">
            <div class="col-sm-6">

                <label for="nom" class="col-form-label obligatoire">Nom</label>
                <input id="nom"
                       class="form-control ctrl"
                       pattern="^[A-Z]([A-Z' ]*[A-Z])*$"
                       required
                       maxlength="20"
                       autocomplete="off"/>
                <div class='messageErreur'></div>

                <label for="prenom" class="col-form-label obligatoire ">Prénom </label>
                <input id="prenom"
                       class="form-control ctrl"
                       required
                       maxlength="20"
                       pattern="^[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]([A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ '-]*[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])*$"
                       autocomplete="off"/>
                <div class='messageErreur'></div>

                <div class="text-center mt-3">
                    <button id="btnMaj" class="btn btn-danger"></button>
                </div>
            </div>
            <div class="col-sm-6">
                <input type="file" id="fichier" accept="image/*" style='display : none'>
                <div class="text-center">
                    <div id="cible" class="upload"
                         data-bs-trigger="hover"
                         data-bs-placement='bottom'
                         data-bs-html="true"
                         data-bs-title="<b>Règles à respecter<b>"
                         data-bs-content="Extensions acceptées : jpg, png et gif<br>Taille limitée à 30 Ko<br>Dimension maximale : 150 * 150">
                        <i class="bi bi-cloud-upload" style="font-size: 4rem; color: #8b8a8a;"></i>
                        <div>Cliquez ou déposer la photo ici</div>
                    </div>
                    <span id="messageCible" class="messageErreur"></span>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
