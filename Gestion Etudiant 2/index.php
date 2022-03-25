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

    <link rel="stylesheet" href="css/style.css">

    <script src="index.js"></script>
</head>
<body>
<div class="container">
    <div class="input-group p-1 border my-2">
        <a class="btn btn-primary text-white" href="../../">Développement Web</a>
        <a class="btn btn-secondary text-white" href="..">Upload</a>
        <button class="btn btn-danger" style="cursor:default">Gestion des étudiants Version 2</button>
    </div>
    <main class="border p-2">
        <h2>Nouvel étudiant</h2>
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

                <label for="prenom" class="col-form-label obligatoire">Prénom </label>
                <input id="prenom"
                       class="form-control ctrl"
                       required
                       maxlength="20"
                       pattern="^[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]([A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ '-]*[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])*$"
                       autocomplete="off"/>
                <div class='messageErreur'></div>
                <div class="text-center mt-3">
                    <button id="btnAjouter" class="btn btn-danger">Ajouter</button>
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
        <hr>
        <h2>
            Liste des étudiants
            <i class="float-end bi bi-info-circle text-primary "
               data-title="<b>Modification de la photo<b>"
               data-bs-toggle="popover"
               data-bs-trigger="hover"
               data-bs-html="true"
               data-bs-placement='bottom'
               data-bs-content="<div style='font-size:11px'>
                                           Faites glisser la nouvelle photo sur l'ancienne ou double cliquer sur la photo pour ne sélectionner une nouvelle
                                            </div>"
            ></i>
        </h2>
        <!-- champ file pour gérer la modification des photos -->
        <input type="file" id="photo" accept="image/*" style='display : none'>
        <div id='listeEtudiants' class="pt-3 text-center"></div>
    </main>
</div>
</body>
</html>
