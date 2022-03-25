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

    <script src="ajout.js"></script>
</head>
<body>
<div class="container">
    <div class="input-group p-1 border mt-2">
        <a class="btn btn-primary text-white" href="../../">Développement Web</a>
        <a class="btn btn-secondary text-white" href="..">Upload</a>
        <button class="btn btn-danger" style="cursor:default">Gestion Document</button>
    </div>
    <div class="d-flex justify-content-between mt-2">
        <h2>Ajouter un document</h2>
        <a href="index.php" class="my-auto">
            <i class="bi bi-box-arrow-left text-primary fs-2"></i>
        </a>
    </div>
    <input type="file" id="fichier" accept=".pdf" style='display: none '>

    <div class="border p-2">
        <label class='obligatoire col-form-label' for="titre">Titre</label>
        <input id='titre' type="text" class="form-control" required
               placeholder="Indiquer ici le titre à donner à votre document (au moins 10 caractères)"
               minlength="10" maxlength="100">
        <span id='messageTitre' class="messageErreur"></span>

        <div id="cible" class="upload text-center"
             data-bs-trigger="hover"
             data-bs-html="true"
             data-bs-title="<b>Règles à respecter<b>"
             data-bs-content="<strong>Pdf uniquement<strong><br>Taille limitée à 1 Mo">
            <i class="bi bi-cloud-upload" style="font-size: 4rem; color: #8b8a8a;"></i>
            <span class=" d-inline-block">Cliquez ou déposerle document PDF ici</span>
        </div>
        <span id='messageCible' class=""></span>
        <div class="text-center">
            <button id="btnAjouter" class="btn btn-danger">Ajouter</button>
        </div>
    </div>
</div>
</body>
</html>

