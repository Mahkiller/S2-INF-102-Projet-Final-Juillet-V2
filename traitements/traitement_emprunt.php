<?php   
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("../inc/fonction.php");
$conn = dbconnect();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Objet non trouvé.");
}
$id_objet = (int)$_GET['id'];
$nbJ= (int)$_POST['nbJ'];

// update le table :

if(modifierDateRetour($id_objet, $nbJ, $conn)){
    header("Location: ../pages/categorie.php");
    exit;
} else {
    die("Erreur lors de la mise à jour de l'objet.");
}
?>