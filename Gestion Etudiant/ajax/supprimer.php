<?php

const REP = "../photo";

// vérification de la transmission du paramètre id attendu
if (!isset($_POST['id'])) {
    echo "L'identifiant de l'étudiant doit être transmis";
    exit;
}

// récupération du paramètre
$id = trim($_POST['id']);

// contrôle des données

// contrôle de l'identifiant de l'étudiant : renseigné, conforme, existant
if (empty($id)) {
    echo "L'identifiant du document doit être renseigné.";
    exit;
} elseif (!preg_match("/^[0-9]{1,2}$/", $id)) {
    echo "L'identifiant n'est pas conforme.";
    exit;
} else {
// vérifier l'existence de l'étudiant
    require '../class/class.database.php';
    $db = Database::getInstance();
    $sql = <<<EOD
         Select 1
         FROM etudiant
         WHERE id = :id;
EOD;
    $curseur = $db->prepare($sql);
    $curseur->bindParam('id', $id);
    $curseur->execute();
    $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
    $curseur->closeCursor();

    if (!$ligne) {
        echo "Etudiant inexistant";
        exit;
    }
}
// suppression dans la base
$sql = <<<EOD
   DELETE FROM etudiant
   WHERE id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('id', $id);
try {
    $curseur->execute();
    // suppression de la photo si elle existe
    @unlink(REP . "/$id.jpg");
    echo 1;
} catch (Exception $e) {
    echo $e->getMessage();
}

