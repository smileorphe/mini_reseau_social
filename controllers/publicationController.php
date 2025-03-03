<?php
session_start();
include '../config/config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $contenu = $_POST['contenu'];
    $utilisateur_id = $_SESSION['user_id'];

    $sql = "INSERT INTO publications (utilisateur_id, contenu) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$utilisateur_id, $contenu]);
    header("Location: ../views/accueil.php");
}
?>