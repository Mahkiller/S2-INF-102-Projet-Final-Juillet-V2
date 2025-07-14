<?php
session_start();
require_once('../inc/connexion.php');

if (!isset($_SESSION['id_membre'])) {                                                                                                                                                                                                                                                                                                                                                                                                                                                  
    header('Location: ../pages/login.php');
    exit();
}

$id_membre = $_SESSION['id_membre'];
$nom_objet = trim($_POST['nom_objet']);
$id_categorie = $_POST['id_categorie'];

if (empty($nom_objet) || empty($id_categorie)) {
    die('Nom de l\'objet ou catégorie manquant.');
}

$uploadDir = __DIR__ . '/../uploads/';
$maxSize = 50 * 1024 * 1024;
$allowedMimeTypes = [
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
    'video/mp4', 'video/webm', 'video/avi', 'video/quicktime'
];

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Insertion objet
$stmt = $bdd->prepare("INSERT INTO exam2_objet (nom_objet, id_categorie, id_membre) VALUES (?, ?, ?)");
$stmt->execute([$nom_objet, $id_categorie, $id_membre]);
$id_objet = $bdd->lastInsertId();

$totalFiles = count($_FILES['image']['name']);
$successCount = 0;
$errorMessages = [];

for ($i = 0; $i < $totalFiles; $i++) {
    if ($_FILES['image']['error'][$i] !== UPLOAD_ERR_OK) {
        $errorMessages[] = "Erreur lors du fichier #" . ($i + 1);
        continue;
    }

    if ($_FILES['image']['size'][$i] > $maxSize) {
        $errorMessages[] = "Fichier #" . ($i + 1) . " trop gros.";
        continue;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name'][$i]);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMimeTypes)) {
        $errorMessages[] = "Type non autorisé pour fichier #" . ($i + 1);
        continue;
    }

    $baseName = pathinfo($_FILES['image']['name'][$i], PATHINFO_FILENAME);
    $extension = pathinfo($_FILES['image']['name'][$i], PATHINFO_EXTENSION);
    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName);
    $finalName = $safeName . '_' . uniqid() . '.' . $extension;

    if (move_uploaded_file($_FILES['image']['tmp_name'][$i], $uploadDir . $finalName)) {
        $stmtImg = $bdd->prepare("INSERT INTO exam2_images_objet (id_objet, nom_image) VALUES (?, ?)");
        $stmtImg->execute([$id_objet, $finalName]);
        $successCount++;
    } else {
        $errorMessages[] = "Échec déplacement fichier #" . ($i + 1);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Résultat ajout objet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
    <h1>Ajout d'objet</h1>
    <?php if ($successCount > 0): ?>
        <div class="alert alert-success">
            ✅ Objet ajouté avec succès.<br>
            📂 <?= $successCount ?> fichier(s) uploadé(s).
        </div>
    <?php else: ?>
        <div class="alert alert-danger">❌ Aucun fichier uploadé.</div>
    <?php endif; ?>

    <?php if (!empty($errorMessages)): ?>
        <div class="alert alert-warning">
            ⚠️ Erreurs rencontrées :<br>
            <ul>
                <?php foreach ($errorMessages as $msg): ?>
                    <li><?= htmlspecialchars($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <a href="../pages/ajout_objet.php" class="btn btn-secondary mt-3">Retour</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
