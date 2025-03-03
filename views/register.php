<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Mini Réseau Social</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .register-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .register-box {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 350px;
    }

    .register-box h2 {
        margin-bottom: 10px;
        color: #1877f2;
    }

    .register-box p {
        color: #555;
        margin-bottom: 20px;
    }

    .register-box input {
        width: 90%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        display: block;
    }

    .register-box button {
        width: 100%;
        padding: 10px;
        border: none;
        background: #1877f2;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
    }

    .register-box button:hover {
        opacity: 0.9;
    }

    .login-link {
        color: #1877f2;
        text-decoration: none;
        font-weight: bold;
    }

    .login-link:hover {
        text-decoration: underline;
    }

    .error-message {
        color: red;
        font-size: 14px;
        margin-bottom: 10px;
    }
</style>
<body>
<div class="register-container">
    <div class="register-box">
        <h2>Créer un compte</h2>
        <p>Rejoignez notre communauté dès maintenant</p>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error-message'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="../controllers/utilisateurController.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="text" name="pseudo" placeholder="Pseudo" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <label for="photo">Photo de profil :</label>
            <input type="file" name="photo" id="photo" accept="image/*">
            <button type="submit">S'inscrire</button>
        </form>
        <p>Déjà un compte ? <a href="login.php" class="login-link">Se connecter</a></p>
    </div>
</div>
</body>
</html>

