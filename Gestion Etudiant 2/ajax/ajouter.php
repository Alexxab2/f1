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

// récupération des paramètres
require '../class/class.controle.php';
$nom = strtoupper(Controle::supprimerEspace($_POST['nom']));
$prenom = mb_strtolower(Controle::supprimerEspace($_POST['prenom']), 'utf8');

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

// contrôle de la photo si transmise et génération requête

if (isset($_FILES['fichier'])) {

    // détection d'une erreur lors de la transmission
    if ($_FILES['fichier']['error'] !== 0) {
        echo "La photo de l'étudiant n'a pas été reçue";
        exit;
    }

    // récupération des données transmises
    $tmp = $_FILES['fichier']['tmp_name'];
    $nomFichier = $_FILES['fichier']['name'];
    $taille = $_FILES['fichier']['size'];

    // vérification de la taille
    $tailleMax = 300 * 1024;
    if ($taille > $tailleMax) {
        echo "La taille du fichier ($taille) dépasse la taille autorisée ($tailleMax)";
        exit;
    }

    // vérification de l'extension
    $lesExtensions = ["jpg", "png"];
    $extension = pathinfo($nomFichier, PATHINFO_EXTENSION);
    if (!in_array($extension, $lesExtensions)) {
        echo "Extension du fichier non acceptée : $extension";
        exit;
    }

    // contrôle du type mime du fichier
    $lesTypes = ["image/pjpeg", "image/jpeg", "x-png", "image/png"];
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
    // la photo est valide


    // Ajout éventuel d'un suffixe sur le nom de la nouvelle photo en cas de doublon
    $name = pathinfo($nomFichier, PATHINFO_FILENAME);
    $i = 1;
    while (file_exists(REP . $nomFichier)) {
        $nomFichier = "$name($i).$extension";
        $i++;
    }

    // la copie sur le serveur sera réalisée si la requête d'ajout réussit
}

// génération de la requête

if (isset($_FILES['fichier'])) {
    $sql = <<<EOD
      insert into etudiant2(nom, prenom, photo) values (:nom, :prenom, :photo)
EOD;
    $curseur = $db->prepare($sql);
    $curseur->bindParam('nom', $nom);
    $curseur->bindParam('prenom', $prenom);
    $curseur->bindParam('photo', $nomFichier);
} else {
    $sql = <<<EOD
      insert into etudiant2(nom, prenom) values (:nom, :prenom)
EOD;
    $curseur = $db->prepare($sql);
    $curseur->bindParam('nom', $nom);
    $curseur->bindParam('prenom', $prenom);
}

// Exécution de la requête
try {
    $curseur->execute();
} catch (Exception $e) {
    echo substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
    exit;
}

// enregistrement de la photo si fournie
if (isset($_FILES['fichier'])) {
    if (@copy($tmp, REP . $nomFichier)) {
        echo 1;
    } else {
        echo "L'étudiant a été ajouté mais la copie de la photo sur le serveur a échoué";
    }
}