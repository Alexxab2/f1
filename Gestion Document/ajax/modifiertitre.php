<?php

const REP = "../document";

// vérification de la transmission des paramètres
$erreur = false;

// vérification de la transmission du paramètre id
if (!isset($_POST['id'])) {
    echo "L'identifiant du document doit être transmis";
    $erreur = true;
}

// vérification de la transmission du paramètre titre
if (!isset($_POST['titre'])) {
    echo "Il faut transmettre le nouveau titre du document";
    $erreur = true;
}

if ($erreur) exit;

// récupération des données
$titre = $_POST["titre"];
$id = $_POST["id"];

// vérification de la valeur des paramètres


// vérification de l'existence du document
require '../class/class.database.php';
$db = Database::getInstance();

$sql = <<<EOD
        select titre from document 
        where id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam(':id', $id);
$curseur->execute();
$ligne = $curseur->fetch(PDO::FETCH_ASSOC);

if (!$ligne) {
    echo "Document inexistant";
    exit();
}

// vérification de la modification du titre
if ($ligne["titre"] === $titre) {
    echo "Aucune modification constatée sur le titre";
    exit();
}

// vérification de la validité du nouveau titre : au moins 10 caractères
if (mb_strlen($titre) < 10) {
    echo "Il faut transmettre un titre plus explicite (au moins 10 caractères)";
    exit();
}

// vérification de l'unicité du nouveau titre
$sql = <<<EOD
    Select 1
    From document
    Where titre = :titre
    and id != :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('titre', $titre);
$curseur->bindParam('id', $id);
$curseur->execute();
$ligne = $curseur->fetch();
$curseur->closeCursor();
if ($ligne) {
    echo "Ce titre est déjà attribué à un autre document";
    exit();
}

// Aucun  erreur constatée : lancement de la mise à jour
$sql = <<<EOD
    update document
        set titre = :titre
    where id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('titre', $titre);
$curseur->bindParam('id', $id);
try {
    $curseur->execute();
    echo 1;
} catch (Exception $e) {
    echo substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
}