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

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

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
    <div class="input-group p-1 border my-2">
        <a class="btn btn-primary text-white" href="../../">Développement Web</a>
        <a class="btn btn-secondary text-white" href="..">Upload</a>
        <button class="btn btn-danger" style="cursor:default">Upload Document</button>
    </div>
    <main class="border p-2">
        <div class="d-flex justify-content-between text-danger m-2">
            <h2 class="marquer text-danger">
                Nouveau document
            </h2>
            <a href="index.php" class="my-auto">
                <i class="bi bi-box-arrow-left text-primary fs-2"></i>
            </a>
        </div>

        <input type="file" id="fichier" accept=".pdf" style='display: none '>
        <div id="cible" class="upload text-center"
             data-bs-trigger="hover"
             data-bs-html="true"
             data-bs-title="<b>Règles à respecter<b>"
             data-bs-content="<strong>Pdf uniquement<strong><br>Taille limitée à 1 Mo">
            <i class="bi bi-cloud-upload" style="font-size: 4rem; color: #8b8a8a;"></i>
            <div>Cliquez ou déposer le document PDF ici</div>
        </div>
        Fichier téléchargé : <span id='messageCible' class=""></span>
        <div class="text-center mt-1">
            <button id="btnAjouter" class="btn btn-danger w-100">Ajouter</button>
        </div>
    </main>
</div>
</body>
</html>
