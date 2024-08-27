

<?php
class User
{
    private $mysqli;
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    // Méthode pour enregistrer un nouvel utilisateur
    public function register($login, $password, $email, $firstname, $lastname)
    {
        $stmt = $this->mysqli->prepare("INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Erreur de préparation : ' . $this->mysqli->error);
        }
        $stmt->bind_param("sssss", $login, $password, $email, $firstname, $lastname);
        $result = $stmt->execute();
        if ($result) {
            $id = $stmt->insert_id;
            $stmt->close();
            return [
                'id' => $id,
                'login' => $login,
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname
            ];
        } else {
            $stmt->close();
            return false;
        }
    }

    // Méthode pour connecter un utilisateur
    public function connect($login, $password)
    {
        $stmt = $this->mysqli->prepare("SELECT id, login, email, firstname, lastname FROM utilisateurs WHERE login = ? AND password = ?");
        $stmt->bind_param("ss", $login, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            $this->id = $user['id'];
            $this->login = $user['login'];
            $this->email = $user['email'];
            $this->firstname = $user['firstname'];
            $this->lastname = $user['lastname'];
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    // Méthode pour déconnecter un utilisateur
    public function disconnect()
    {
        $this->id = null;
        $this->login = null;
        $this->email = null;
        $this->firstname = null;
        $this->lastname = null;
    }

    // Méthode pour supprimer un utilisateur
    public function delete()
    {
        if ($this->id) {
            $stmt = $this->mysqli->prepare("DELETE FROM utilisateurs WHERE id = ?");
            $stmt->bind_param("i", $this->id);
            $result = $stmt->execute();
            $stmt->close();
            if ($result) {
                $this->disconnect();
                return true;
            }
        }
        return false;
    }

    // Méthode pour mettre à jour un utilisateur
    public function update($login, $password, $email, $firstname, $lastname)
    {
        if ($this->id) {
            $stmt = $this->mysqli->prepare("UPDATE utilisateurs SET login = ?, password = ?, email = ?, firstname = ?, lastname = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $login, $password, $email, $firstname, $lastname, $this->id);
            $result = $stmt->execute();
            $stmt->close();
            if ($result) {
                $this->login = $login;
                $this->email = $email;
                $this->firstname = $firstname;
                $this->lastname = $lastname;
                return true;
            }
        }
        return false;
    }

    // Méthode pour obtenir les informations d'un utilisateur par ID
    public function getUserById($id)
    {
        $stmt = $this->mysqli->prepare("SELECT login, email, firstname, lastname FROM utilisateurs WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    // Méthode pour vérifier si un utilisateur est connecté
    public function isConnected()
    {
        return $this->id !== null;
    }
}
