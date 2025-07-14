<?php
require_once("../inc/connect.php");
$conn = dbconnect();

if (!isset($_GET['id_emprunt']) || !is_numeric($_GET['id_emprunt'])) {
    die("Identifiant invalide.");
}
$id_emprunt = (int)$_GET['id_emprunt'];

$sqlCheck = "
    SELECT o.nom_objet
    FROM exam2_emprunt e
    JOIN exam2_objet o ON e.id_objet = o.id_objet
    WHERE e.id_emprunt = $id_emprunt
";
$resCheck = mysqli_query($conn, $sqlCheck);
if (!$resCheck || mysqli_num_rows($resCheck) == 0) {
    die("Emprunt introuvable.");
}
$objet = mysqli_fetch_assoc($resCheck);

$sqlEtat = "SELECT etat FROM exam2_etatobject WHERE id_emprunt = $id_emprunt";
$resEtat = mysqli_query($conn, $sqlEtat);
$data = mysqli_fetch_assoc($resEtat);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>État de l'objet</title>
    <link rel="stylesheet" href="../asset/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>État de l'objet : <?= htmlspecialchars($objet['nom_objet']) ?></h2>
    <p><strong>État actuel :</strong>
        <?= $data ? htmlspecialchars($data['etat']) : "<span class='text-warning'>Aucun état défini</span>" ?>
    </p>
    <a href="javascript:history.back()" class="btn btn-secondary">Retour</a>
</div>
</body>
</html>
