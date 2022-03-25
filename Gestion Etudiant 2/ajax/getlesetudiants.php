<?php
require '../class/class.database.php';
$db = Database::getInstance();

$sql = <<<EOD
    SELECT id, nom, prenom, photo  
    FROM etudiant2
    ORDER BY nom, prenom;
EOD;
$curseur = $db->query($sql);
$lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
$curseur->closeCursor();

// vérification de l'existence de l'image et affectation des photos par défaut
$rep = '../photo';
for($i = 0; $i < count($lesLignes) ; $i++) {
    if (is_null($lesLignes[$i]['photo'])) {
        $lesLignes[$i]['present'] = 0;
    } else {
        $nomFichier = $lesLignes[$i]['photo'];
        if(file_exists("$rep/$nomFichier"))
            $lesLignes[$i]['present'] = 1;
        else
            $lesLignes[$i]['present'] = 0;
    }
}
echo json_encode($lesLignes);
