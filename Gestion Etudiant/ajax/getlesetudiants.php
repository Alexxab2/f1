<?php
require '../class/class.database.php';
$db = Database::getInstance();

$sql = <<<EOD
    SELECT id, nom, prenom 
    FROM etudiant
    ORDER BY nom, prenom;
EOD;
$curseur = $db->query($sql);
$lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
$curseur->closeCursor();

// vérification de l'existence de l'image
$rep = '../photo';
for ($i = 0; $i < count($lesLignes); $i++) {
    $nomFichier = $lesLignes[$i]['id'] . ".jpg";
    $lesLignes[$i]['present'] = file_exists("$rep/$nomFichier") ? 1 : 0;
}
echo json_encode($lesLignes);
