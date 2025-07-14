<?php
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $params = [];
    if (isset($_GET['id_categorie'])) {
        $params[] = "id_categorie=" . intval($_GET['id_categorie']);
    }
    if (isset($_GET['statut'])) {
        $params[] = "statut=" . urlencode($_GET['statut']);
    }
    $query = implode("&", $params);
    header("Location: ../pages/categorie.php" . ($query ? "?$query" : ""));
    exit;
} else {
    header("Location: ../pages/categorie.php");
    exit;
}
?>