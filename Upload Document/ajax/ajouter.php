<?php
const REP = "../document/";

// contrôle de l'existence des paramètres attendus
if (!isset($_FILES['fichier']) ) {
    echo "Il faut transmettre un fichier";
    exit;
}

// détection d'une erreur lors de la transmission
if ($_FILES['fichier']['error'] !== 0) {
    echo "Aucun fichier reçu";
    exit;
}

// récupération des données transmises
$tmp = $_FILES['fichier']['tmp_name'];
$nomFichier = $_FILES['fichier']['name'];
$taille = $_FILES['fichier']['size'];

// Vérification du fichier

// vérification de la taille
$tailleMax = 1 * 1024 * 1024;
if ($taille > $tailleMax) {
    echo "La taille du fichier ($taille) dépasse la taille autorisée ($tailleMax)";
    exit;
}

// vérification de l'extension
$lesExtensions = ["pdf"];
$extension = pathinfo($nomFichier, PATHINFO_EXTENSION);
if (!in_array($extension, $lesExtensions)) {
    echo "Extension du fichier non acceptée : $extension";
    exit;
}

// contrôle du type mime du fichier
$lesTypes = ["application/force-download", "application/pdf"];
$type = mime_content_type($tmp);
if (!in_array($type, $lesTypes)) {
    echo "Type de fichier non accepté : $type";
    exit;
}

// récupération du nom du fichier sans son extension
$nom = pathinfo($nomFichier, PATHINFO_FILENAME);

// suppression des accents et autres caractères non alphanumériques et mise en minuscule
require '../class/class.controle.php';
$nom = strtolower(trim(Controle::remplacerCaractere($nom)));

// Ajout éventuel d'un suffixe sur le nom en cas de doublon
$nomFichier = "$nom.$extension";
$i = 1;
while(file_exists(REP . $nomFichier)) {
    $nomFichier =  "$nom($i).$extension";
    $i++;
}

// copie sur le serveur
if(copy($tmp, REP . $nomFichier))
    echo 1;
else
    echo "La copie du fichier sur le serveur a échoué";

