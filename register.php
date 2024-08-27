

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

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'register') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    $userData = $user->register($login, $password, $email, $firstname, $lastname);
    if ($userData) {
        echo "Inscription réussie : " . htmlspecialchars($userData['login']);
    } else {
        echo "Erreur lors de l'inscription.";
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
    <title>Inscription</title>
    <style>
        /* Styles pour le formulaire */
    </style>
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>
        <form action="register.php" method="post">
            <input type="hidden" name="action" value="register">
            <input type="text" name="login" placeholder="Nom d'utilisateur" required>
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="text" name="firstname" placeholder="Prénom" required>
            <input type="text" name="lastname" placeholder="Nom" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="S'inscrire">
        </form>
        <div class="link">
            <a href="login.php">Déjà inscrit ? Connectez-vous</a>
        </div>
    </div>
</body>
</html>
