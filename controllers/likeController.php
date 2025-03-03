<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id'];

    // Vérifier si l'utilisateur a déjà aimé
    $stmt = $conn->prepare("SELECT * FROM likes WHERE utilisateur_id = ? AND publication_id = ?");
    $stmt->execute([$userId, $postId]);
    $like = $stmt->fetch();

    if ($like) {
        // Supprimer le "J'aime"
        $stmt = $conn->prepare("DELETE FROM likes WHERE utilisateur_id = ? AND publication_id = ?");
        $stmt->execute([$userId, $postId]);
    } else {
        // Ajouter le "J'aime"
        $stmt = $conn->prepare("INSERT INTO likes (utilisateur_id, publication_id) VALUES (?, ?)");
        $stmt->execute([$userId, $postId]);
    }

    echo json_encode(["success" => true]);
}
?>
