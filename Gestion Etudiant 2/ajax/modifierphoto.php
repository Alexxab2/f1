<?php
const REP = "../photo/";

// vérification de la transmission des paramètres attendus id + file

if (!isset($_POST['id'])) {
    echo "L'identifiant de l'étudiant doit être transmis";
    exit;
}

// contrôle de l'existence des paramètres attendus
if (!isset($_FILES['fichier'])) {
    echo "La photo n'est pas transmise";
    exit;
}

// récupération des données transmises
$tmp = $_FILES['fichier']['tmp_name'];
$nomFichier = $_FILES['fichier']['name'];
$taille = $_FILES['fichier']['size'];
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
         Select photo
         FROM etudiant2
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

// vérification de la taille
$tailleMax = 300 * 1024;
if ($taille > $tailleMax) {
    echo "La taille du fichier ($taille) dépasse la taille autorisée ($tailleMax)";
    exit;
}

// vérification de l'extension
$lesExtensions = ["jpg", "png", "gif"];
$extension = pathinfo($nomFichier, PATHINFO_EXTENSION);
if (!in_array($extension, $lesExtensions)) {
    echo "Extension du fichier non acceptée : $extension";
    exit;
}

// contrôle du type mime du fichier
$lesTypes = ["image/pjpeg", "image/jpeg", "x-png", "image/gif", "image/png"];
$type = mime_content_type($tmp);
if (!in_array($type, $lesTypes)) {
    echo "Type de fichier non accepté : $type";
    exit;
}

// vérification des dimensions de l'image
$largeurMax = 150;
$hauteurMax = 150;
$lesDimensions = getimagesize($tmp);
if ($lesDimensions[0] > $largeurMax || $lesDimensions[1] > $hauteurMax) {
    echo "Les dimensions de l'image ($lesDimensions[0],$lesDimensions[1]) dépassent les dimensions autorisées ($largeurMax, $hauteurMax)";
    exit;
}



// il faut récupérer le nom de l'ancienne photo pour la supprimer si elle existe vraiment (possibilité d'incohérence entre la table et le répertoire)

$sql = <<<EOD
        SELECT photo
        FROM etudiant2
        where id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('id', $_POST['id']);
$curseur->execute();
$ligne = $curseur->fetch(PDO::FETCH_ASSOC);
$curseur->closeCursor();

$suppression  = file_exists(REP . $ligne['photo']);

// Ajout éventuel d'un suffixe sur le nom de la nouvelle photo en cas de doublon
$nom = pathinfo($nomFichier, PATHINFO_FILENAME);
$i = 1;
while (file_exists(REP . $nomFichier)) {
    $nomFichier = "$nom($i).$extension";
    $i++;
}

$sql = <<<EOD
    update etudiant2
        set photo = :photo
    where id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('id', $id);
$curseur->bindParam('photo', $nomFichier);
try {
    $curseur->execute();
    copy($tmp, REP . $nomFichier);
    // suppression de l'ancienne photo
    if($suppression) {
        @unlink(REP . $ligne['photo']);
    }
    echo 1;
} catch (Exception $e) {
    echo substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
}
