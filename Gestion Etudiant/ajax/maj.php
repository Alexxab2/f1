<?php
const REP = "../photo/";

// Vérification des paramètres attendus
if (!isset($_POST['nom'])) {
    echo "Le nom de l'étudiant n'est pas transmis";
    exit;
}

if (!isset($_POST['prenom'])) {
    echo "Le prénom de l'étudiant n'est pas transmis";
    exit;
}

if (!isset($_FILES['fichier'])) {
    echo "La photo n'est pas transmise";
    exit;
}

// détection d'une erreur lors de la transmission
if ($_FILES['fichier']['error'] !== 0) {
    echo "La photo de l'étudiant n'a pas été reçue";
    exit;
}

// récupération des paramètres
require '../class/class.controle.php';
$nom = strtoupper(Controle::supprimerEspace($_POST['nom']));
$prenom = mb_strtolower(Controle::supprimerEspace($_POST['prenom']), 'utf8');
$tmp = $_FILES['fichier']['tmp_name'];
$nomFichier = $_FILES['fichier']['name'];
$taille = $_FILES['fichier']['size'];
// dans le cas d'une modification
if (isset($_POST['id'])) $id = trim($_POST['id']);

// Contrôle des données
require '../class/class.database.php';
$db = Database::getInstance();

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

// contrôle de l'id si transmis
if (isset($id)) {
    if (empty($id)) {
        echo "L'identifiant du document doit être renseigné.";
        exit;
    } elseif (!preg_match("/^[0-9]{1,2}$/", $id)) {
        echo "L'identifiant n'est pas conforme.";
        exit;
    } else {
// vérifier l'existence de l'étudiant
        $sql = <<<EOD
         Select nom, prenom
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
}

// contrôle de la photo

// vérification de la taille
$tailleMax = 300 * 1024;
if ($taille > $tailleMax) {
    echo "La taille du fichier ($taille) dépasse la taille autorisée ($tailleMax)";
    exit;
}

// vérification de l'extension
$lesExtensions = ["jpg"];
$extension = pathinfo($nomFichier, PATHINFO_EXTENSION);
if (!in_array($extension, $lesExtensions)) {
    echo "Extension du fichier non acceptée";
    exit;
}

// vérification du type MIME
$lesTypes = ["image/pjpeg", "image/jpeg"];
$type = mime_content_type($tmp);
if (!in_array($type, $lesTypes)) {
    echo "Type de fichier non accepté";
    exit;
}

// vérification des dimensions de l'image

$largeurMax = 150;
$hauteurMax = 150;
$lesDimensions = getimagesize($tmp);
if ($lesDimensions[0] > $largeurMax || $lesDimensions[1] > $hauteurMax) {
    echo "Les dimensions de l'image dépassent les dimensions autorisées";
    exit;
}


// s'agit il d'une demande de modification
if (isset($id)) {
    //  génération de la requête de mise à jour
    $sql = <<<EOD
        update etudiant
            set nom = :nom,
                prenom = :prenom
        where id = :id;
EOD;
    $curseur = $db->prepare($sql);
    $curseur->bindParam('id', $id);
    $curseur->bindParam('nom', $nom);
    $curseur->bindParam('prenom', $prenom);
    try {
        $curseur->execute();
    } catch (Exception $e) {
        echo substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
        exit;
    }
} else {
    // traitement de la demande d'ajout
    //  génération de la requête
    $sql = <<<EOD
        insert into etudiant(nom, prenom) values (:nom, :prenom)
EOD;
    $curseur = $db->prepare($sql);
    $curseur->bindParam('nom', $nom);
    $curseur->bindParam('prenom', $prenom);
    try {
        $curseur->execute();
        $id = $db->lastInsertId();
    } catch (Exception $e) {
        echo substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
        exit;
    }
}

// enregistrement de la photo
if (@copy($tmp, REP . "$id.jpg")) {
    echo 1;
} else {
    echo "Opération annulée, la copie de la photo sur le serveur a échoué";
}
