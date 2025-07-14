<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../inc/connect.php");
$conn = dbconnect();

$sql = "SELECT * FROM exam2_membre";
$res = mysqli_query($conn, $sql);
$membres = mysqli_fetch_all($res, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des propriétaires</title>
    <link rel="stylesheet" href="../asset/bootstrap.min.css">
</head>
<body>
<?php include("../inc/navbar.php"); ?>
<div class="container mt-5">
    <h2>Liste des membres</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Ville</th>
                <th>Voir informations</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($membres as $membre): ?>
                <tr>
                    <td><?= htmlspecialchars($membre['nom']) ?></td>
                    <td><?= htmlspecialchars($membre['email']) ?></td>
                    <td><?= htmlspecialchars($membre['ville']) ?></td>
                    <td>
                        <a href="detail_membre.php?id=<?= $membre['id_membre'] ?>" class="btn btn-info btn-sm">
                            Voir informations
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
