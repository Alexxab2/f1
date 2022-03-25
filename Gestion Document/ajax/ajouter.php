<?php


// contrôle de l'existence des paramètres attendus




// contrôle de l'existence du titre


// récupération des données transmises
$titre = trim($_POST["titre"]);

// contrôle des valeurs transmises

// contrôle sur le titre
if (empty($titre)) {
    echo "Titre nom renseigné";
    exit;
} else if (mb_strlen($titre) < 10) {
    echo "Titre pas assez long (au moins 10 caractères)";
    exit;
} else if (mb_strlen($titre) > 100) {
    echo "Titre trop long (100 caractères max.)";
    exit;
} // la vérification de l'unicité est réalisé par un trigger





// ajout dans la table document
require '../class/class.database.php';
$db = Database::getInstance();


$sql = <<<EOD
        insert into document(titre) values(:titre);
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('titre', $titre);
try {
    $curseur->execute();


} catch (Exception $e) {
    echo substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);

}
