<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="../asset/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/syle.css">
</head>
<body>
<?php include("../inc/navbar.php"); ?>
<div class="container mt-5">
    <h2>Inscription</h2>
    <?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'email'): ?>
        <div class="alert alert-danger">Cet email est déjà utilisé.</div>
    <?php elseif (isset($_GET['erreur']) && $_GET['erreur'] == 'sql'): ?>
        <div class="alert alert-danger">Erreur lors de l'inscription.</div>
    <?php endif; ?>
    <form action="../traitements/traitement_inscription.php" method="post">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="date_de_naissance" class="form-label">Date de naissance</label>
            <input type="date" class="form-control" name="date_de_naissance" required>
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <select class="form-control" name="genre" required>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
                <option value="Autre">Autre</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" class="form-control" name="ville">
        </div>
        <div class="mb-3">
            <label for="mdp" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="mdp" required>
        </div>
        <button type="submit" class="btn btn-success">S'inscrire</button>
    </form>
</div>
</body>
</html>