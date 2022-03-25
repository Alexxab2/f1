<?php
// Vérification des paramètres attendus
if (!isset($_POST['id'])) {
    echo "Le numéro de l'étudiant n'est pas transmis";
    exit;
}

if (!isset($_POST['nom'])) {
    echo "Le nouveau nom de l'étudiant n'est pas transmis";
    exit;
}

// récupération des données
require '../class/class.controle.php';
$nom = strtoupper(Controle::supprimerEspace($_POST['nom']));
$id = trim($_POST["id"]);


// Contrôle des données

require '../class/class.database.php';
$db = Database::getInstance();


// contrôle de l'identifiant de l'étudiant
if (empty($id)) {
    echo "\nLe numéro de l'étudiant doit être renseigné.";
    $erreur = true;
} elseif (!preg_match("/^[0-9]{1,2}$/", $id)) {
    echo "\nLe numéro de l'étudiant n'est pas conforme.";
    $erreur = true;
}  else {
    // Vérification de l'existence de l'identifiant
    $sql = <<<EOD
        SELECT 1
        FROM etudiant2
        where id = :id;
EOD;
    $curseur = $db->prepare($sql);
    $curseur->bindParam('id', $id);
    $curseur->execute();
    $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
    $curseur->closeCursor();
    if (!$ligne) {
        echo "Cet étudiant n'existe pas";
        $erreur = true;
    }
}


// contrôle du nom
if ($nom === '') {
    echo "Le nom doit être renseigné.";
    exit;
} elseif (!preg_match("/^[A-Z]( ?[A-Z])*$/", $nom)) {
    echo "Le nom ne doit comporter que des lettres majuscules non accentuées et des espaces.";
    exit;
} elseif (mb_strlen($nom) > 20) {
    echo "Le nom ne doit pas dépasser 30 caractères.";
    exit;
}

// requête de mise à jour
$sql = <<<EOD
    update etudiant2
        set nom = :nom
    where id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('nom', $nom);
$curseur->bindParam('id', $id);
try {
    $curseur->execute();
    echo 1;
} catch (Exception $e) {
    echo substr($e->getMessage(),strrpos($e->getMessage(), '#') + 1);
}