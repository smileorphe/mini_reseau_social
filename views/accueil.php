<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include '../config/config.php';
include 'header.php';

$stmt = $conn->query("SELECT p.id, p.contenu, p.date_creation, u.pseudo, u.photo FROM publications p JOIN utilisateurs u ON p.utilisateur_id = u.id ORDER BY p.date_creation DESC");
$posts = $stmt->fetchAll();
?>

<h2>Bienvenue, <?php echo $_SESSION['pseudo']; ?> !</h2>
<img src="../uploads/<?php echo $_SESSION['photo']; ?>" alt="Photo de profil" width="100" height="100">

<div class="feed-container">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-header">
                <img src="../uploads/<?php echo $post['photo']; ?>" width="50" height="50">
                <strong><?php echo $post['pseudo']; ?></strong>
            </div>
            <p><?php echo nl2br($post['contenu']); ?></p>
            <button class="like-btn">J'aime</button>
            <button class="comment-btn">Commenter</button>
            <button class="share-btn">Partager</button>

            <div class="comments-section" id="comments-<?php echo $post['id']; ?>"> </div>
            <form class="comment-form" data-id="<?php echo $post['id']; ?>">
                <input type="text" name="contenu" placeholder="Écrire un commentaire..." required>
                <button type="submit">Envoyer</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<script src="../assets/script.js"></script>
<?php include 'footer.php'; ?>
