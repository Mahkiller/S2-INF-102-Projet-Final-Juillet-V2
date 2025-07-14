<?php
require_once("../inc/connect.php");
$conn = dbconnect();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Membre non trouvé.");
}

$id_membre = (int)$_GET['id'];

$sqlMembre = "SELECT * FROM exam2_membre WHERE id_membre = $id_membre";
$resMembre = mysqli_query($conn, $sqlMembre);
$membre = mysqli_fetch_assoc($resMembre);

if (!$membre) {
    die("Membre non trouvé.");
}

$sqlCount = "SELECT COUNT(*) AS total_emprunt FROM exam2_emprunt WHERE id_membre = $id_membre";
$resCount = mysqli_query($conn, $sqlCount);
$total = mysqli_fetch_assoc($resCount)['total_emprunt'];

$sqlObjets = "
    SELECT o.nom_objet, e.date_retour
    FROM exam2_emprunt e
    JOIN exam2_objet o ON e.id_objet = o.id_objet
    WHERE e.id_membre = $id_membre
    ORDER BY e.date_retour ASC
";
$resObjets = mysqli_query($conn, $sqlObjets);
$objets = mysqli_fetch_all($resObjets, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail du membre</title>
    <link rel="stylesheet" href="../asset/bootstrap.min.css">
</head>
<body>
<?php include("../inc/navbar.php"); ?>
<div class="container mt-5">
    <h2>Détails sur : <?= htmlspecialchars($membre['nom']) ?></h2>
    <p><strong>Email :</strong> <?= htmlspecialchars($membre['email']) ?></p>
    <p><strong>Ville :</strong> <?= htmlspecialchars($membre['ville']) ?></p>
    <p><strong>Nombre total d'objets empruntés :</strong> <?= $total ?></p>

    <h4 class="mt-4">Objets empruntés :</h4>
    <?php if (count($objets) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom de l'objet</th>
                    <th>Date de retour</th>
                    <th>Action</th> 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objets as $o): ?>
                    <tr>
                        <td><?= htmlspecialchars($o['nom_objet']) ?></td>
                        <td><?= htmlspecialchars($o['date_retour']) ?></td>
                        <td>
                            <a href="etatobject.php?id_objet=<?= urlencode($o['id_objet']) ?>" class="btn btn-outline-primary btn-sm">
                                Voir l'état
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun objet emprunté.</p>
    <?php endif; ?>


    <a href="listemembre.php" class="btn btn-secondary mt-3">Retour à la liste des membres</a>
</div>
</body>
</html>
