<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Si le champ pseudo est présent, c'est une inscription
    if (isset($_POST['pseudo'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $pseudo = $_POST['pseudo'];
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];
        
        // Vérifier si l'email existe déjà
        $check_stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $check_stmt->execute([$email]);
        if ($check_stmt->fetch()) {
            $_SESSION['error'] = "Cet email est déjà utilisé.";
            header("Location: ../views/register.php");
            exit;
        }

        // Gérer l'upload de la photo
        $photo = 'default.png';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $photo = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['photo']['tmp_name'], '../uploads/' . $photo);
            }
        }

        // Inscription de l'utilisateur
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, pseudo, email, mot_de_passe, photo) VALUES (?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$nom, $prenom, $pseudo, $email, $mot_de_passe_hash, $photo])) {
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['photo'] = $photo;
            header("Location: ../views/accueil.php");
            exit;
        } else {
            $_SESSION['error'] = "Erreur lors de l'inscription.";
            header("Location: ../views/register.php");
            exit;
        }
    } 
    // Sinon, c'est une tentative de connexion
    else {
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        $check_stmt = $conn->prepare("SELECT id, mot_de_passe, pseudo, photo FROM utilisateurs WHERE email = ?");
        $check_stmt->execute([$email]);
        $user = $check_stmt->fetch();

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['pseudo'] = $user['pseudo'];
            $_SESSION['photo'] = $user['photo'];
            header("Location: ../views/accueil.php");
            exit;
        } else {
            $_SESSION['error'] = "Email ou mot de passe incorrect.";
            header("Location: ../views/login.php");
            exit;
        }
    }
}
?>