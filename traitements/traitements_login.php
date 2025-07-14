<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("../inc/fonction.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $mdp = $_POST["mdp"];
    $conn = dbconnect();
    //echo $mdp;
    $user_mdp = verifierConnexion($email, $mdp, $conn);
    //var_dump($user_mdp);
    if ($user_mdp) {
        $_SESSION["id_membre"] = $user["id_membre"];
        $_SESSION["nom"] = $user["nom"];
        header("Location: ../pages/categorie.php");
        exit;
    } else {
        header("Location: ../pages/login.php?erreur=1");
        exit;
    }
} else {
    header("Location: ../pages/login.php");
    exit;
}
?>