<?php
$handle = opendir(".");
$projectsListIgnore = array('.', '..', '.idea', '$RECYCLE.BIN', 'System Volume Information');
$projectContents = '';
while (($file = readdir($handle)) !== false) {
    if (is_dir($file) && !in_array($file, $projectsListIgnore)) {
        $projectContents .= "<a href='$file' class='btn btn-sm btn-outline-secondary mx-3 py-3 '>$file</a>";
    }
}
closedir($handle);
$dossier = getcwd();
$pageContents = <<< EOPAGE
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Exemple</title>
	<meta charset="UTF-8">
   <link rel="icon" type="image/png" href="formation.png">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css" >
</head>
<body>
<div class="container ">
       <div class="input-group p-1 border mt-2">
        <a class="btn btn-primary text-white" href="..">Développement Web</a>
        <button class="btn btn-danger" style="cursor:default">Upload</button>
    </div>
    <main class="cadre">
        <div class="menu">
           	${projectContents}
        </div>
    </main>
</div>
</body>
</html>
EOPAGE;
echo $pageContents;