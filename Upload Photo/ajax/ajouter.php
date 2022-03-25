<?php

const REP = "../photo";

// contrôle de l'existence des paramètres attendus
if (!isset($_FILES['fichier']) ) {
    echo "\nIl faut transmettre un fichier";
    exit;
}

// détection d'une erreur lors de la transmission
if ($_FILES['fichier']['error'] !== 0) {
    echo "\nAucun fichier reçu";
    exit;
}

// récupération des données transmises
$tmp = $_FILES['fichier']['tmp_name'];
$nomFichier = $_FILES['fichier']['name'];
$taille = $_FILES['fichier']['size'];

// Vérification sur le fichier

// vérification de la taille
$tailleMax = 300 * 1024;
if ($taille > $tailleMax) {
    echo "La taille du fichier ($taille) dépasse la taille autorisée ($tailleMax)";
    exit;
}

// vérification de l'extension
$lesExtensions = ["jpg", "png"];
$extension = pathinfo($nomFichier, PATHINFO_EXTENSION);
if (!in_array($extension, $lesExtensions)) {
    echo "Extension du fichier non acceptée : $extension";
    exit;
}

// contrôle du type mime du fichier
$lesTypes = ["image/pjpeg", "image/jpeg", "x-png", "image/png"];
$type = mime_content_type($tmp);
if (!in_array($type, $lesTypes)) {
    echo "Type de fichier non accepté : $type";
    exit;
}


// vérification des dimensions de l'image : seulement en absennce d'erreur
$largeurMax = 150;
$hauteurMax = 150;
$lesDimensions = getimagesize($tmp);
if ($lesDimensions[0] > $largeurMax || $lesDimensions[1] > $hauteurMax) {
    echo "Les dimensions de l'image ({$lesDimensions[0]},{ $lesDimensions[1]} dépassent les dimensions autorisées ($largeurMax, $hauteurMax)";
    exit;
}

// Ajout éventuel d'un suffixe sur le nom de la nouvelle photo en cas de doublon
$nom = pathinfo($nomFichier, PATHINFO_FILENAME);
$i = 1;
while (file_exists(REP . "/$nomFichier")) {
    $nomFichier = "$nom($i).$extension";
    $i++;
}

// copie sur le serveur
if (copy($tmp, REP . "/$nomFichier"))
    echo 1;
else
    echo "La copie du fichier sur le serveur a échoué";



