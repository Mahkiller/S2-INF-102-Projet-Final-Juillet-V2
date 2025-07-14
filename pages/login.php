<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="../asset/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/syle.css">
</head>
<body>
<?php include("../inc/navbar.php"); ?>
<div class="container mt-5">
    <h2>Connexion</h2>
    <?php if (isset($_GET['inscription']) && $_GET['inscription'] == 'success'): ?>
        <div class="alert alert-success">Inscription réussie, vous pouvez vous connecter.</div>
    <?php elseif (isset($_GET['erreur'])): ?>
        <div class="alert alert-danger">Email ou mot de passe incorrect.</div>
    <?php endif; ?>
    <form action="../traitements/traitements_login.php" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label for="mdp" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="mdp" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>
</body>
</html>