<?php include 'includes/_navbar.php'; ?>

<?php
// Inclure le fichier contenant la classe User
require 'User.php';

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "classes");

if ($mysqli->connect_error) {
    die("Échec de la connexion : " . $mysqli->connect_error);
}

// Création d'une instance de User
$user = new User($mysqli);

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'connect') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    if ($user->connect($login, $password)) {
        echo "Connexion réussie : " . htmlspecialchars($user->login);
    } else {
        echo "Erreur lors de la connexion.";
    }
}

// Fermeture de la connexion à la base de données
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        /* Styles pour le formulaire */
    </style>
</head>

<body>
    <div class="container">
        <h2>Connexion</h2>
        <form action="login.php" method="post">
            <input type="hidden" name="action" value="connect">
            <input type="text" name="login" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Se connecter">
        </form>
        <div class="link">
            <a href="register.php">Pas encore inscrit ? Inscrivez-vous</a>
        </div>
    </div>
</body>

</html>