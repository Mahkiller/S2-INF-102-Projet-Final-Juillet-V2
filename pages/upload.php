<?php
$uploadDir = __DIR__ . '/uploads/';
$maxSize = 50 * 1024 * 1024; // 50 Mo

$allowedMimeTypes = [
    // Images
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
    // Vidéos
    'video/mp4', 'video/webm', 'video/avi', 'video/quicktime'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier'])) {
    $file = $_FILES['fichier'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        die('❌ Erreur lors de l’upload : ' . $file['error']);
    }

    if ($file['size'] > $maxSize) {
        die('❌ Le fichier dépasse la taille maximale de 50 Mo.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedMimeTypes)) {
        die('❌ Type de fichier non autorisé : ' . $mime);
    }

    // Nettoyage du nom de fichier
    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
    $originalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION); 
    $newName = $originalName . '_' . uniqid() . '.' . $extension;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
        echo "✅ Fichier uploadé avec succès : " . htmlspecialchars($newName);
    } else {
        echo "❌ Échec du déplacement du fichier.";
    }
} else {
    echo "📭 Aucun fichier reçu.";
}
?>
