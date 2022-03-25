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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css ">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
    <script src="http://guy.verghote.free.fr/composant/std.js"></script>

    <link rel="stylesheet" href="style.css">

    <script src="index.js"></script>
</head>
<body>
<div class="container">
    <div class="input-group p-1 border mt-2">
		<a class="btn btn-primary text-white" href="../../">Développement Web</a>
        <a class="btn btn-secondary text-white"  href="..">Upload</a>
		<button class="btn btn-danger" style="cursor:default">Gestion Document </button>
    </div>

    <div class="d-flex justify-content-between">
        <h2 class="masquer text-secondary">Gestion des documents</h2>
        <a href="ajout.php" class="btn btn-success  my-auto ">
            <i class="bi bi-plus-square"></i>
            Nouveau document
        </a>
    </div>
    <div class='table-responsive'>
        <table id="lesDocuments" class='table table-sm table-borderless'>
            <thead>
            <tr>
                <th style="width:100px">Action</th>
                <th>Titre</th>
                <th style="width:150px">Date émission</th>
            </tr>
            </thead>
            <tbody id="lesLignes"></tbody>
        </table>
    </div>
</div>
</body>
</html>

