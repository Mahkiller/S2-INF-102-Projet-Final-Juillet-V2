<?php
session_start();
require_once("../inc/connect.php");
require_once("../inc/fonction.php");

if (!isset($_SESSION['id_membre'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/ajout_objet.php");
    exit();
}

$conn = dbconnect();

$id_membre = $_SESSION['id_membre'];
$nom_objet = trim($_POST['nom_objet']);
$id_categorie = intval($_POST['id_categorie']);

// Validation simple
if (empty($nom_objet) || $id_categorie <= 0) {
    die("Nom d'objet ou catégorie invalide.");
}

// Insertion objet
$stmt = mysqli_prepare($conn, "INSERT INTO exam2_objet (nom_objet, id_categorie, id_membre) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sii", $nom_objet, $id_categorie, $id_membre);
if (!mysqli_stmt_execute($stmt)) {
    die("Erreur lors de l'insertion de l'objet : " . mysqli_error($conn));
}
$id_objet = mysqli_insert_id($conn);

// Upload des images
$uploadDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$maxSize = 5 * 1024 * 1024; // 5Mo max par fichier

$uploadedCount = 0;
$errors = [];

if (isset($_FILES['images'])) {
    for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
        $error = $_FILES['images']['error'][$i];
        if ($error !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur upload image #" . ($i + 1);
            continue;
        }

        $size = $_FILES['images']['size'][$i];
        if ($size > $maxSize) {
            $errors[] = "Image #" . ($i + 1) . " trop volumineuse.";
            continue;
        }

        $tmpName = $_FILES['images']['tmp_name'][$i];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmpName);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimeTypes)) {
            $errors[] = "Image #" . ($i + 1) . " type non autorisé.";
            continue;
        }

        // Nom safe
        $originalName = pathinfo($_FILES['images']['name'][$i], PATHINFO_FILENAME);
        $originalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
        $extension = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
        $finalName = $originalName . '_' . uniqid() . '.' . $extension;

        $destination = $uploadDir . $finalName;

        if (move_uploaded_file($tmpName, $destination)) {
            // Insertion dans la table des images liées à l'objet
            $stmtImg = mysqli_prepare($conn, "INSERT INTO exam2_images_objet (id_objet, nom_image) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmtImg, "is", $id_objet, $finalName);
            if (mysqli_stmt_execute($stmtImg)) {
                $uploadedCount++;
            } else {
                $errors[] = "Erreur base pour image #" . ($i + 1);
                // Optionnel: supprimer fichier uploadé si insertion échoue
                unlink($destination);
            }
        } else {
            $errors[] = "Erreur déplacement fichier image #" . ($i + 1);
        }
    }
} else {
    $errors[] = "Aucune image uploadée.";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajout d'objet - Résultat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
    <h1>Ajout d'objet</h1>
    <?php if ($uploadedCount > 0): ?>
        <div class="alert alert-success">
            Objet ajouté avec succès.<br>
            <?= $uploadedCount ?> image(s) uploadée(s).
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            Aucun fichier uploadé avec succès.
        </div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-warning">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <a href="../pages/ajout_objet.php" class="btn btn-primary mt-3">Retour</a>
</div>
</body>
</html>
