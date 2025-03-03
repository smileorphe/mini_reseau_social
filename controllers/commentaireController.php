<?php
session_start();
include '../config/config.php';

// Création d'un fichier de log
$logFile = __DIR__ . '/comment_log.txt';
function writeLog($message) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

writeLog("Début de la requête");
writeLog("POST: " . print_r($_POST, true));
writeLog("SESSION: " . print_r($_SESSION, true));

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    try {
        // Vérification des données reçues
        if (!isset($_POST['publication_id']) || !isset($_POST['contenu'])) {
            writeLog("Données manquantes dans POST");
            throw new Exception('Données manquantes');
        }

        // Validation des données
        $publication_id = intval($_POST['publication_id']);
        $contenu = trim(htmlspecialchars($_POST['contenu']));
        $utilisateur_id = intval($_SESSION['user_id']);

        writeLog("Données validées: publication_id=$publication_id, utilisateur_id=$utilisateur_id, contenu=$contenu");

        if (empty($contenu)) {
            writeLog("Contenu vide");
            throw new Exception('Le contenu ne peut pas être vide');
        }

        if ($publication_id <= 0) {
            writeLog("ID de publication invalide: $publication_id");
            throw new Exception('ID de publication invalide');
        }

        // Vérifier si la publication existe
        $check_pub = $conn->prepare("SELECT id FROM publications WHERE id = ?");
        $check_pub->execute([$publication_id]);
        if (!$check_pub->fetch()) {
            writeLog("Publication introuvable: $publication_id");
            throw new Exception('Publication introuvable');
        }

        writeLog("Publication trouvée, tentative d'insertion du commentaire");

        // Insérer le commentaire
        $stmt = $conn->prepare("INSERT INTO commentaires (publication_id, utilisateur_id, contenu) VALUES (?, ?, ?)");
        
        try {
            $result = $stmt->execute([$publication_id, $utilisateur_id, $contenu]);
            writeLog("Résultat de l'insertion: " . ($result ? "succès" : "échec"));
            
            if (!$result) {
                writeLog("Erreur SQL: " . print_r($stmt->errorInfo(), true));
                throw new Exception('Erreur lors de l\'insertion');
            }
        } catch (PDOException $e) {
            writeLog("Exception PDO: " . $e->getMessage());
            throw new Exception('Erreur base de données: ' . $e->getMessage());
        }

        $last_id = $conn->lastInsertId();
        writeLog("ID du nouveau commentaire: $last_id");

        // Récupérer le commentaire inséré
        $stmt = $conn->prepare("
            SELECT c.*, u.pseudo 
            FROM commentaires c 
            JOIN utilisateurs u ON c.utilisateur_id = u.id 
            WHERE c.id = ?
        ");
        
        if (!$stmt->execute([$last_id])) {
            writeLog("Erreur lors de la récupération du commentaire");
            throw new Exception('Erreur lors de la récupération du commentaire');
        }

        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$comment) {
            writeLog("Commentaire non trouvé après insertion");
            throw new Exception('Commentaire non trouvé après insertion');
        }

        writeLog("Commentaire récupéré avec succès: " . print_r($comment, true));

        $response = [
            'success' => true,
            'pseudo' => $comment['pseudo'],
            'contenu' => $comment['contenu']
        ];

        writeLog("Réponse envoyée: " . print_r($response, true));
        echo json_encode($response);

    } catch (Exception $e) {
        writeLog("ERREUR: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    writeLog("Requête invalide ou utilisateur non connecté");
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Requête invalide ou utilisateur non connecté'
    ]);
}
?>