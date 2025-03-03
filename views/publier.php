<?php include 'header.php'; ?>
    <form action="../controllers/publicationController.php" method="POST">
        <textarea name="contenu" placeholder="Votre publication" required></textarea>
        <button type="submit">Publier</button>
    </form>
<?php include 'footer.php'; ?>