

<?php

// Inclure le fichier contenant la classe User
require 'User.php';

// Connexion à la base de données
$mysqli = new mysqli();

if ($mysqli->connect_error) {
    die("Échec de la connexion : " . $mysqli->connect_error);
}

// Création d'une instance de User
$user = new User($mysqli);

// Vérification de l'action à exécuter
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    // Gestion des actions basées sur la valeur du paramètre 'action'
    switch ($action) {
        case 'update':
            if ($user->isConnected()) {
                $login = $_POST['login'];
                $password = $_POST['password'];
                $email = $_POST['email'];
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];

                if ($user->update($login, $password, $email, $firstname, $lastname)) {
                    echo "Mise à jour réussie.";
                } else {
                    echo "Erreur lors de la mise à jour.";
                }
            } else {
                echo "Vous devez être connecté pour mettre à jour vos informations.";
            }
            break;

        case 'delete':
            if ($user->isConnected()) {
                if ($user->delete()) {
                    echo "Utilisateur supprimé et déconnecté.";
                } else {
                    echo "Erreur lors de la suppression.";
                }
            } else {
                echo "Vous devez être connecté pour supprimer votre compte.";
            }
            break;

        case 'disconnect':
            if ($user->isConnected()) {
                $user->disconnect();
                echo "Déconnexion réussie.";
            } else {
                echo "Vous n'êtes pas connecté.";
            }
            break;

        default:
            echo "Action non reconnue.";
            break;
    }
}

// Fermeture de la connexion à la base de données
$mysqli->close();

?>
