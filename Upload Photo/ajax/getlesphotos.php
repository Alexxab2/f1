<?php
$lesExtensions = ["png", "jpg"];
$lesFichiers = [];

//parcours des fichiers du répertoire transmis
$repertoire = opendir('../photo/');
$fichier = readdir($repertoire);
while ($fichier !== false) {
    $extension = pathinfo($fichier, PATHINFO_EXTENSION);
    if ($fichier != "." && $fichier != ".." && (in_array(strtolower($extension), $lesExtensions))) {
        $lesFichiers[] = $fichier;
    }
    $fichier = readdir($repertoire);
}
closedir($repertoire);
echo json_encode($lesFichiers);

