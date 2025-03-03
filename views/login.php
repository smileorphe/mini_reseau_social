
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Mini Réseau Social</title>
    <link rel="stylesheet" href="../assets/styles.css">
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

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .login-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }

        .login-box h2 {
            margin-bottom: 10px;
            color: #1877f2;
        }

        .login-box p {
            color: #555;
            margin-bottom: 20px;
        }

        .login-box input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            border: none;
            background: #1877f2;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-box button:hover {
            opacity: 0.9;
        }

        .register-link {
            color: #1877f2;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .forgot-password {
            display: block;
            margin-top: 10px;
            color: #1877f2;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .forgot-password {
            display: block;
            margin-top: 10px;
            color: #1877f2;
            text-decoration: none;
            font-weight: bold;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .forgot-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100vh;
        }

        .forgot-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }

        .forgot-box h2 {
            margin-bottom: 10px;
            color: #1877f2;
        }

        .forgot-box p {
            color: #555;
            margin-bottom: 20px;
        }

        .forgot-box input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
        }

        .forgot-box button {
            width: 100%;
            padding: 10px;
            border: none;
            background: #1877f2;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .forgot-box button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <h2>Bienvenue</h2>
        <p>Connectez-vous pour continuer</p>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error-message'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="../controllers/utilisateurController.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore inscrit ? <a href="register.php" class="register-link">Créer un compte</a></p>
        <p><a href="forgot_password.php" class="forgot-password">Mot de passe oublié ?</a></p>
    </div>
</div>
</body>
</html>