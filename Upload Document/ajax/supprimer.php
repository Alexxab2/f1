<?php
const REP = "../document/";

// vérification de la transmission des paramètres
if (!isset($_POST['nomFichier'])) {
    echo "Le paramètre n'est pas transmis";
    exit;
}

// récupération de la valeur
$nomFichier = trim($_POST['nomFichier']);

// vérification de la valeur
if (strlen($nomFichier) === 0) {
    echo "Le paramètre n'est pas renseigné";
    exit;
}

$fichier = REP . $nomFichier;

// vérification de l'extension
$lesExtensions = ['pdf'];
$extension = pathinfo($fichier, PATHINFO_EXTENSION);
if(!in_array($extension, $lesExtensions)) {
    echo "Le fichier ne possède pas l'extension demandée";
    exit;
}

// vérification de l'existence du fichier
if(!file_exists($fichier)) {
    echo "Ce fichier n'existe pas";
    exit;
}

// suppression du fichier
if (unlink($fichier))
    echo 1;
else
    echo "La suppression a échoué";


