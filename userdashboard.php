

<?php
session_start(); // Démarrer la session

// Inclure le fichier contenant la classe User
require 'User.php';

// Connexion à la base de données
$mysqli = new mysqli("localhost", "username", "password", "database");

if ($mysqli->connect_error) {
    die("Échec de la connexion : " . $mysqli->connect_error);
}

// Création d'une instance de User
$user = new User($mysqli);

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de l'utilisateur
$userId = $_SESSION['user_id'];
$userData = $user->getUserById($userId); // Ajoutez une méthode getUserById() dans User.php

// Traitement des actions CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'update') {
        // Logique pour mettre à jour les informations de l'utilisateur
        $login = $_POST['login'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $user->update($login, $password, $email, $firstname, $lastname);
        header("Location: user_dashboard.php");
        exit();
    } elseif ($action == 'delete') {
        // Logique pour supprimer l'utilisateur
        $user->delete();
        session_destroy();
        header("Location: index.php");
        exit();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <!-- Inclure le fichier CSS de Bootswatch Quartz -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/quartz/bootstrap.min.css">
</head>
<body>
    <!-- Navbar Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">Accueil</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="user_dashboard.php">Mon Tableau de Bord</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Mon Tableau de Bord</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($userData['login']); ?></td>
                    <td><?php echo htmlspecialchars($userData['email']); ?></td>
                    <td><?php echo htmlspecialchars($userData['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($userData['lastname']); ?></td>
                </tr>
            </tbody>
        </table>
        <form action="user_dashboard.php" method="post">
            <input type="hidden" name="action" value="update">
            <div class="form-group">
                <label for="login">Nom d'utilisateur</label>
                <input type="text" class="form-control" name="login" value="<?php echo htmlspecialchars($userData['login']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control" name="firstname" value="<?php echo htmlspecialchars($userData['firstname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($userData['lastname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
        <form action="user_dashboard.php" method="post" class="mt-2">
            <input type="hidden" name="action" value="delete">
            <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
        </form>
    </div>

    <!-- Inclure les fichiers JS de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
