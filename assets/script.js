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
                    let likeText = btn.querySelector(".like-text");
                    if (btn.classList.contains("liked")) {
                        likeText.textContent = "Je n'aime plus";
                    } else {
                        likeText.textContent = "J'aime";
                    }
                }
            })
            .catch(error => console.error("Erreur:", error));
        });
    });

    // Fonction de soumission des commentaires
    async function submitComment(form) {
        console.log("Soumission du commentaire");
        
        const postId = form.getAttribute("data-id");
        const commentInput = form.querySelector("input[name='contenu']");
        const commentList = document.getElementById("comments-" + postId);
        
        if (!commentInput.value.trim()) return;

        const formData = new FormData();
        formData.append("publication_id", postId);
        formData.append("contenu", commentInput.value.trim());

        try {
            console.log("Envoi de la requête avec:", {
                publication_id: postId,
                contenu: commentInput.value.trim()
            });

            const response = await fetch("../controllers/commentaireController.php", {
                method: "POST",
                body: formData
            });

            const responseText = await response.text();
            console.log("Réponse brute:", responseText);

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error("Erreur de parsing JSON:", e);
                throw new Error("Réponse invalide du serveur");
            }

            console.log("Données parsées:", data);

            if (data.success) {
                const newComment = document.createElement("p");
                newComment.className = "comment";
                newComment.innerHTML = `<strong>${data.pseudo} :</strong> ${data.contenu}`;
                commentList.insertBefore(newComment, commentList.firstChild);
                commentInput.value = "";
            } else {
                throw new Error(data.error || "Erreur inconnue");
            }
        } catch (error) {
            console.error("Erreur complète:", error);
            alert("Erreur lors de l'ajout du commentaire: " + error.message);
        }
    }

    // Gestion des commentaires
    document.querySelectorAll(".comment-form").forEach(form => {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();
            await submitComment(form);
        });
    });

    // Gestion du bouton "Commenter"
    document.querySelectorAll(".comment-btn").forEach(button => {
        button.addEventListener("click", function() {
            let postId = this.getAttribute("data-id");
            let commentForm = document.querySelector(`.comment-form[data-id="${postId}"]`);
            let commentInput = commentForm.querySelector("input[name='contenu']");
            commentInput.focus();
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