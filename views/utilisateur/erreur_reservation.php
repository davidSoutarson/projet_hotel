<?php
session_start();

require_once __DIR__ . '/../../config/configuration.php';
$erreur = isset($_SESSION['erreur_reservation']) ? $_SESSION['erreur_reservation'] : "Une erreur inconnue s'est produite.";
unset($_SESSION['erreur_reservation']); // Nettoyer l'erreur après affichage
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Erreur de Réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            color: #721c24;
            text-align: center;
            padding: 50px;
        }

        .erreur-container {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 20px;
            max-width: 400px;
            margin: 100px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        a {
            color: #721c24;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="erreur-container">
        <h1>Erreur de Réservation</h1>
        <p><?php echo htmlspecialchars($erreur); ?></p>
        <p><a href="<?php echo BASE_URL . 'index.php'; ?>">Retour à l'accueil</a></p>
    </div>
</body>

</html>