<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("../inc/fonction.php");
$conn = dbconnect();

if (!isset($_['id']) || !is_numeric($_GET['id'])) {
    die("Objet non trouvé.");
}
$id_objet = (int)$_POST['id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/syle.css">
    <title>Emprunt</title>
</head>
<body>
    <?php include("../inc/navbar.php"); ?>
    <div class="container md-5">
        <form action="../traitements/traitement_emprunt.php" method="post">
        <div class="mb-3">
            <label class="form-label">Nombre de jour :</label>
            <input type="number" class="form-control" name="nbJ" required>
            <input type="hidden" name="id_objet" value="<?= (int)$id_objet ?>">
        </div>
        <button type="submit" class="btn btn-primary">emprunter</button>
    </form>
    </div>
    
</body>
</html>