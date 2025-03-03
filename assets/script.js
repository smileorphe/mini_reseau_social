document.addEventListener("DOMContentLoaded", function () {
    // Gestion du bouton "J'aime"
    document.querySelectorAll(".like-btn").forEach(button => {
        button.addEventListener("click", function () {
            let postId = this.getAttribute("data-id");
            let btn = this;

            fetch("../controllers/likeController.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `post_id=${postId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btn.classList.toggle("liked");
                    }
                })
                .catch(error => console.log("Erreur:", error));
        });
    });

    // Gestion du bouton "Commenter"
    document.querySelectorAll(".comment-btn").forEach(button => {
        button.addEventListener("click", function () {
            let postId = this.getAttribute("data-id");
            let commentSection = document.getElementById("comments-" + postId);
            if (commentSection.innerHTML === "") {
                commentSection.innerHTML = `
                    <form class="comment-form" data-id="${postId}">
                        <input type="text" name="comment" placeholder="Écrire un commentaire..." required>
                        <button type="submit">Envoyer</button>
                    </form>
                    <div class="comment-list"></div>
                `;
            }
            commentSection.style.display = (commentSection.style.display === "none") ? "block" : "none";

            // Ajouter un événement pour poster un commentaire
            commentSection.querySelector(".comment-form").addEventListener("submit", function (e) {
                e.preventDefault();
                let commentInput = this.querySelector("input[name='comment']");
                let commentList = this.nextElementSibling;

                fetch("../controllers/commentaireController.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `post_id=${postId}&comment=${encodeURIComponent(commentInput.value)}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            commentList.innerHTML += `<p><strong>Vous :</strong> ${commentInput.value}</p>`;
                            commentInput.value = "";
                        }
                    })
                    .catch(error => console.log("Erreur:", error));
            });
        });
    });

    // Gestion du bouton "Partager"
    document.querySelectorAll(".share-btn").forEach(button => {
        button.addEventListener("click", function () {
            let postId = this.getAttribute("data-id");
            let shareUrl = window.location.origin + `/views/post.php?id=${postId}`;
            navigator.clipboard.writeText(shareUrl).then(() => {
                alert("Lien copié ! Partagez-le avec vos amis.");
            });
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".like-btn").forEach(button => {
        button.addEventListener("click", function () {
            let postId = this.getAttribute("data-id");
            this.classList.toggle("liked");
        });
    });

    document.querySelectorAll(".comment-form").forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            let postId = this.getAttribute("data-id");
            let commentInput = this.querySelector("input[name='comment']");
            let commentList = this.nextElementSibling;

            fetch("../controllers/commentController.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `post_id=${postId}&comment=${encodeURIComponent(commentInput.value)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let newComment = document.createElement("p");
                        newComment.innerHTML = `<strong>Vous :</strong> ${commentInput.value}`;
                        commentList.appendChild(newComment);
                        commentInput.value = "";
                    }
                })
                .catch(error => console.log("Erreur:", error));
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".comment-form").forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            let postId = this.getAttribute("data-id");
            let commentInput = this.querySelector("input[name='contenu']");
            let commentList = document.getElementById("comments-" + postId);

            fetch("../controllers/commentController.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `publication_id=${postId}&contenu=${encodeURIComponent(commentInput.value)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        let newComment = document.createElement("p");
                        newComment.innerHTML = `<strong>${data.pseudo} :</strong> ${data.contenu}`;
                        commentList.appendChild(newComment);
                        commentInput.value = "";
                    }
                })
                .catch(error => console.log("Erreur:", error));
        });
    });
});