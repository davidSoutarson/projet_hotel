<?php
require_once __DIR__ . '/../../config/configuration.php';
require_once VIEWS_PATH . 'header.php';

// Vérifie si l'utilisateur est bien connecté
if (!isset($_SESSION['utilisateur'])) {
    header("Location: connexion.php");
    exit;
}

// Récupération des informations de réservation si elles sont stockées en session
$reservationDetails = $_SESSION['reservation_details'] ?? null;

// Supprimer les détails de la réservation après affichage pour éviter les doublons
unset($_SESSION['reservation_details']);
?>
<div class="container">

    <h1> Réservation confirmée avec succès !</h1>

    <?php if ($reservationDetails): ?>
        <div class="details">
            <p><strong>Nom de l'utilisateur :</strong> <?= htmlspecialchars($_SESSION['utilisateur']['nom']) ?></p>
            <p><strong>Hôtel :</strong> <?= htmlspecialchars($reservationDetails['hotel']) ?></p>
            <p><strong>Chambre :</strong> <?= htmlspecialchars($reservationDetails['chambre']) ?></p>
            <p><strong>Date d'arrivée :</strong> <?= htmlspecialchars($reservationDetails['date_arrivee']) ?></p>
            <p><strong>Date de départ :</strong> <?= htmlspecialchars($reservationDetails['date_depart']) ?></p>
        </div>
    <?php else: ?>
        <p>Votre réservation a bien été enregistrée. Vous recevrez bientôt un email de confirmation.</p>
    <?php endif; ?>

    <div class="actions">
        <a href="../index.php" class="btn">Retour à l'accueil</a>
        <a href="mes_reservations.php" class="btn">Voir mes réservations</a>
    </div>
</div>