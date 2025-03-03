<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $publication_id = $_POST['publication_id'];
    $contenu = htmlspecialchars($_POST['contenu']);
    $utilisateur_id = $_SESSION['user_id'];

    // Insérer le commentaire dans la base de données
    $stmt = $conn->prepare("INSERT INTO commentaires (publication_id, utilisateur_id, contenu) VALUES (?, ?, ?)");
    $stmt->execute([$publication_id, $utilisateur_id, $contenu]);

    // Récupérer le commentaire inséré
    $last_id = $conn->lastInsertId();
    $stmt = $conn->prepare("SELECT c.contenu, u.pseudo FROM commentaires c JOIN utilisateurs u ON c.utilisateur_id = u.id WHERE c.id = ?");
    $stmt->execute([$last_id]);
    $comment = $stmt->fetch();

    // Retourner le commentaire en JSON
    echo json_encode($comment);
    exit;
}
?>