<?php



// vérification de la transmission du paramètre id attendu
if (!isset($_POST['id'])) {
    echo "L'identifiant du document doit être transmis";
    exit;
}

// récupération du paramètre
$id = trim($_POST['id']);

// contrôle des données
require '../class/class.database.php';
$db = Database::getInstance();

// contrôle du code de la catégorie : renseigné, conforme, existant et supprimable
if (empty($id)) {
    echo "L'identifiant du document doit être renseigné.";
    exit;
} elseif (!preg_match("/^[0-9]{1,2}$/", $id)) {
    echo "L'identifiant n'est pas conforme.";
    exit;
} else {
// vérification de l'existence du document
    $sql = <<<EOD
        select 1 from document 
        where id = :id;
EOD;
    $curseur = $db->prepare($sql);
    $curseur->bindParam(':id', $id);
    $curseur->execute();
    $ligne = $curseur->fetch(PDO::FETCH_ASSOC);

    if (!$ligne) {
        echo "Document inexistant";
        exit;
    }
}

// suppression dans la base de données
$sql = <<<EOD
        delete from document 
        where id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam(':id', $id);
try {
    $curseur->execute();
    // suppression du fichier dans le répertoire document
    $nomFichier = "doc$id.pdf";
    unlink(REP . "/$nomFichier");
    echo 1;
} catch (Exception $e) {
    echo substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
}


