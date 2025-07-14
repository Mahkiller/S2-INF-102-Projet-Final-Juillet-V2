<?php
require_once("../inc/connect.php");
$conn = dbconnect();

$sql = "SELECT 
          CASE 
            WHEN etat = 'Bon' THEN 'OK' 
            ELSE 'Abîmé' 
          END AS etat_simple, 
          COUNT(*) AS total 
        FROM exam2_etatobject
        GROUP BY etat_simple";

$res = mysqli_query($conn, $sql);

$counts = [
    'OK' => 0,
    'Abîmé' => 0
];

while ($row = mysqli_fetch_assoc($res)) {
    $counts[$row['etat_simple']] = (int)$row['total'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résumé des états des objets</title>
    <link rel="stylesheet" href="../asset/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Résumé des états des objets</h2>
    <table class="table table-bordered w-25">
        <thead>
            <tr>
                <th>État</th>
                <th>Nombre d'objets</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>OK</td><td><?= $counts['OK'] ?></td></tr>
            <tr><td>Abîmé</td><td><?= $counts['Abîmé'] ?></td></tr>
        </tbody>
    </table>
</div>
</body>
</html>
