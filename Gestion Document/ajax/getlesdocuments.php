<?php
require '../class/class.database.php';
$db = Database::getInstance();

$sql = <<<EOD
        Select id, titre, date_format(dateEmission, '%d/%m/%Y') as date
        from document 
        order by dateEmission desc, titre;
EOD;
$curseur = $db->query($sql);
$lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
$curseur->closeCursor();

// réponse  en cas d'absence de document


// ajout d'une colonne 'present' permettant de savoir si le document associé existe

