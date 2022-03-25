<?php
// Vérification des paramètres attendus
if (!isset($_POST['id'])) {
    echo "Le numéro de l'étudiant n'est pas transmis";
    exit;
}

if (!isset($_POST['prenom'])) {
    echo "Le prénom de l'étudiant n'est pas transmis";
    exit;
}

// récupération des données
require '../class/class.controle.php';
$prenom = mb_strtolower(Controle::supprimerEspace($_POST['prenom']), 'utf8');
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


// contrôle du prénom
if (empty($prenom)) {
    echo "Le prénom doit être renseigné.";
    exit;
} elseif (!preg_match("/^[a-zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]([a-zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ '-]*[A-Za-zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])*$/", $prenom)) {
    echo "Le prénom n'est pas conforme";
    exit;
} elseif (mb_strlen($prenom) > 20) {
    echo "Le prénom ne doit pas dépasser 30 caractères.";
    exit;
}

// requête de mise à jour
$sql = <<<EOD
    update etudiant2
        set prenom = :prenom
    where id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('prenom', $prenom);
$curseur->bindParam('id', $id);
try {
    $curseur->execute();
    echo 1;
} catch (Exception $e) {
    echo substr($e->getMessage(),strrpos($e->getMessage(), '#') + 1);
}