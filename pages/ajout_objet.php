<?php
session_start();


require_once('../inc/connect.php');

$conn = mysqli_connect('localhost', 'root', '', 'examV3');
mysqli_set_charset($conn, 'utf8mb4');
if (!$conn) {
    die('Erreur connexion BDD : ' . mysqli_connect_error());
}

$categories = [];
$res = mysqli_query($conn, "SELECT * FROM exam2_categorie_objet");
while ($row = mysqli_fetch_assoc($res)) {
    $categories[] = $row;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajouter un objet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<?php include '../inc/navbar.php'; ?>

<div class="container mt-4">
    <h1>Ajouter un objet</h1>

    <form action="../traitement/traitement_ajout_objet.php" method="post" enctype="multipart/form-data" class="mt-3">
        <div class="mb-3">
            <label for="nom_objet" class="form-label">Nom de l'objet :</label>
            <input type="text" id="nom_objet" name="nom_objet" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="id_categorie" class="form-label">Catégorie :</label>
            <select name="id_categorie" id="id_categorie" class="form-select" required>
                <option value="">-- Choisir une catégorie --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int)$cat['id_categorie'] ?>">
                        <?= htmlspecialchars($cat['nom_categorie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Photo de l'objet (image uniquement) :</label>
            <input type="file" id="image" name="image" accept="image/*" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
