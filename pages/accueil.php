<?php
require_once("../inc/connect.php");
require_once("../inc/fonction.php");
$conn = dbconnect();
$objets = getObjetsAvecEmprunt($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../asset/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/syle.css">
</head>
<body>
<?php include("../inc/navbar.php"); ?>
    <div class="container mt-5">
        <h1>Liste des objets </h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Objet</th>
                    <th>Catégorie</th>
                    <th>Propriétaire</th>
                    <th>Date de retour</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objets as $objet): ?>
                    <tr>
                        <td><?= htmlspecialchars($objet['nom_objet']) ?></td>
                        <td><?= htmlspecialchars($objet['nom_categorie']) ?></td>
                        <td><?= htmlspecialchars($objet['proprietaire']) ?></td>
                        <td>
                            <?= $objet['date_retour'] ? htmlspecialchars($objet['date_retour']) : '-' ?>
                        </td>
                        <td>
                            <?php
                            $disponible = !$objet['date_retour'] || $objet['date_retour'] < date('Y-m-d');
                            if ($disponible): ?>
                                <button class="btn btn-reserver">Réserver</button>
                            <?php else: ?>
                                <span class="badge bg-secondary">Indisponible</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>