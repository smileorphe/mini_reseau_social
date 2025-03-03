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
            <?php
            // Vérifier si l'utilisateur a déjà aimé cette publication
            $check_like = $conn->prepare("SELECT * FROM likes WHERE utilisateur_id = ? AND publication_id = ?");
            $check_like->execute([$_SESSION['user_id'], $post['id']]);
            $is_liked = $check_like->fetch() ? 'liked' : '';
            ?>
            <button class="like-btn <?php echo $is_liked; ?>" data-id="<?php echo $post['id']; ?>">
                <span class="like-text"><?php echo $is_liked ? 'Je n\'aime plus' : 'J\'aime'; ?></span>
            </button>
            <button class="comment-btn" data-id="<?php echo $post['id']; ?>">Commenter</button>
            <button class="share-btn" data-id="<?php echo $post['id']; ?>">Partager</button>

            <div class="comments-section" id="comments-<?php echo $post['id']; ?>">
                <?php
                // Récupérer les commentaires existants
                $comments_stmt = $conn->prepare("
                    SELECT c.contenu, u.pseudo 
                    FROM commentaires c 
                    JOIN utilisateurs u ON c.utilisateur_id = u.id 
                    WHERE c.publication_id = ? 
                    ORDER BY c.date_creation DESC
                ");
                $comments_stmt->execute([$post['id']]);
                $comments = $comments_stmt->fetchAll();
                
                foreach ($comments as $comment) {
                    echo '<p class="comment"><strong>' . htmlspecialchars($comment['pseudo']) . ' :</strong> ' . 
                         htmlspecialchars($comment['contenu']) . '</p>';
                }
                ?>
            </div>
            <form class="comment-form" data-id="<?php echo $post['id']; ?>" method="post" onsubmit="event.preventDefault(); submitComment(this);">
                <input type="text" name="contenu" placeholder="Écrire un commentaire..." required>
                <button type="submit">Envoyer</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<script src="../assets/script.js"></script>
<?php include 'footer.php'; ?>
