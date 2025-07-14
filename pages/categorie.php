<?php
require_once("../inc/connect.php");
require_once("../inc/fonction.php");
$conn = dbconnect();

$categories = [];
$res = mysqli_query($conn, "SELECT * FROM exam2_categorie_objet");
while ($row = mysqli_fetch_assoc($res)) {
    $categories[] = $row;
}

$id_categorie = isset($_GET['id_categorie']) ? intval($_GET['id_categorie']) : 0;
$statut = isset($_GET['statut']) ? $_GET['statut'] : 'tous';

$show_list = isset($_GET['statut']) || isset($_GET['id_categorie']);
$objets = $show_list ? getObjetsFiltres($conn, $id_categorie, $statut) : [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Filtrer par catégorie</title>
    <link rel="stylesheet" href="../asset/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/style.css">
</head>
<body>
<?php include("../inc/navbar.php"); ?>
<div class="container mt-5">
    <h2>Filtrer les objets</h2>
    <form method="get" action="../traitements/traitement_categorie.php" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="id_categorie" class="form-label">Catégorie</label>
            <select name="id_categorie" id="id_categorie" class="form-select">
                <option value="0">Toutes</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>" <?= $id_categorie == $cat['id_categorie'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nom_categorie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="statut" class="form-label">Statut</label>
            <select name="statut" id="statut" class="form-select">
                <option value="tous" <?= $statut == 'tous' ? 'selected' : '' ?>>Tous</option>
                <option value="emprunte" <?= $statut == 'emprunte' ? 'selected' : '' ?>>Empruntés</option>
                <option value="disponible" <?= $statut == 'disponible' ? 'selected' : '' ?>>Disponibles</option>
            </select>
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </form>

    <?php if ($show_list): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Objet</th>
                <th>Catégorie</th>
                <th>Propriétaire</th>
                <th>Date de retour</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($objets as $objet): ?>
                <tr>
                    <td><?= htmlspecialchars($objet['nom_objet']) ?></td>
                    <td><?= htmlspecialchars($objet['nom_categorie']) ?></td>
                    <td><?= htmlspecialchars($objet['proprietaire']) ?></td>
                    <td><?= $objet['date_retour'] ? htmlspecialchars($objet['date_retour']) : '-' ?></td>
                    <td>
                        <?php
                        $disponible = !$objet['date_retour'] || $objet['date_retour'] < date('Y-m-d');
                        if ($disponible): ?>
                            <span class="badge bg-success">Disponible</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Indisponible</span>
                        <?php endif; ?>
                    </td>
                     <td>
                        <a href="detail_objet.php?id=<?= (int)$objet['id_objet'] ?>" class="btn btn-info btn-sm">Détail</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
</body>
</html>