<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("../inc/connect.php");
$conn = dbconnect();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Objet non trouvé.");
}
$id_objet = (int)$_GET['id'];

// Requête pour récupérer les infos de l'objet
$sql = "SELECT o.id_objet, o.nom_objet, c.nom_categorie, m.nom AS proprietaire
        FROM exam2_objet o
        JOIN exam2_categorie_objet c ON o.id_categorie = c.id_categorie
        JOIN exam2_membre m ON o.id_membre = m.id_membre
        WHERE o.id_objet = $id_objet";

$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) == 0) {
    die("Objet non trouvé.");
}
$objet = mysqli_fetch_assoc($res);

// Récupérer les images liées
$sqlImages = "SELECT nom_image FROM exam2_images_objet WHERE id_objet = $id_objet";
$resImages = mysqli_query($conn, $sqlImages);
$images = [];
while ($row = mysqli_fetch_assoc($resImages)) {
    $images[] = $row['nom_image'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Détail de l'objet <?= htmlspecialchars($objet['nom_objet']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include("../inc/navbar.php"); ?>
<div class="container mt-4">
    <h1><?= htmlspecialchars($objet['nom_objet']) ?></h1>
    <p><strong>Catégorie :</strong> <?= htmlspecialchars($objet['nom_categorie']) ?></p>
    <p><strong>Propriétaire :</strong> <?= htmlspecialchars($objet['proprietaire']) ?></p>

    <h3 class="mt-4">Images / Vidéos :</h3>
    <?php if (count($images) > 0): ?>
        <div class="row">
            <?php foreach ($images as $img): 
                $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                $path = "../uploads/" . $img;
                ?>
                <div class="col-md-4 mb-3">
                    <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                        <img src="<?= htmlspecialchars($path) ?>" alt="Image" class="img-fluid rounded shadow-sm">
                    <?php elseif (in_array($ext, ['mp4', 'webm', 'avi', 'mov'])): ?>
                        <video controls class="img-fluid rounded shadow-sm">
                            <source src="<?= htmlspecialchars($path) ?>" type="video/<?= $ext ?>">
                            Votre navigateur ne supporte pas la vidéo.
                        </video>
                    <?php else: ?>
                        <div class="alert alert-warning">Fichier non pris en charge : <?= htmlspecialchars($img) ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Aucune image ou vidéo disponible pour cet objet.</div>
    <?php endif; ?>

    <a href="categorie.php" class="btn btn-secondary mt-3">Retour à la liste</a>
</div>
</body>
</html>
