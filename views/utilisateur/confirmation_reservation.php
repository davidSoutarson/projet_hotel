<?php
require_once __DIR__ . '/../../config/configuration.php';
require_once VIEWS_PATH . 'header.php';
require_once MODEL_PATH . 'Reservation.php';

// Vérifie si l'utilisateur est bien connecté
if (!isset($_SESSION['utilisateur'])) {
    header("Location: " . VIEWS_LIEN . "utilisateur/formulaire_reservation.php");
    exit;
}

/* var_dump($_SESSION);

var_dump($_SESSION['reservation_details']); */

// Récupération des informations de réservation si elles sont stockées en session
$reservationDetails = $_SESSION['reservation_details'] ?? null;

// Supprimer les détails de la réservation après affichage pour éviter les doublons
unset($_SESSION['reservation_details']);
?>
<div class="container">



    <?php if ($reservationDetails): ?>
        <div class="details">
            <h2>Réservation confirmée avec succès !</h2>

            <p><strong>Nom de l'utilisateur :</strong> <?= htmlspecialchars($_SESSION['utilisateur']['nom']) ?></p>
            <p><strong>Prenom de l'utilisateur :</strong> <?= htmlspecialchars($_SESSION['utilisateur']['prenom']) ?></p>
            <p><strong>email de l'utilisateur :</strong> <?= htmlspecialchars($_SESSION['utilisateur']['email']) ?></p>

            <p><strong>pour la ville de :</strong> <?= htmlspecialchars($reservationDetails['ville']) ?></p>
            <p><strong>Hôtel :</strong> <?= htmlspecialchars($reservationDetails['hotel']) ?></p>
            <p><strong>Chambre :</strong> <?= htmlspecialchars($reservationDetails['chambre']) ?></p>

            <p><strong>Date d'arrivée :</strong> <?= htmlspecialchars($reservationDetails['date_arrivee']) ?></p>
            <p><strong>Date de départ :</strong> <?= htmlspecialchars($reservationDetails['date_depart']) ?></p>
        </div>
    <?php else: ?>
        <p>Votre réservation a bien été enregistrée. Vous recevrez bientôt un email de confirmation.</p>

        <h2>resumer de tout les resevation pour le client: <?php echo $_SESSION['utilisateur']['prenom'] . " " . $_SESSION['utilisateur']['nom']; ?> </h2>
        <!-- utilisation d'une  -->
        <?php
        $reservationModel = new Reservation();
        $idUtilisateur = $_SESSION['utilisateur']['id']; // L'ID de l'utilisateur connecté
        $reservations = $reservationModel->getReservationsByUser($idUtilisateur);
        ?>

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Réservation n°</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Hôtel</th>
                    <th>Adresse de l'hôtel</th>
                    <th>Chambre</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['id']) ?></td>
                        <td><?= htmlspecialchars($reservation['date_debut']) ?></td>
                        <td><?= htmlspecialchars($reservation['date_fin']) ?></td>
                        <td><?= htmlspecialchars($reservation['hotel_nom']) ?></td>
                        <td><?= htmlspecialchars($reservation['hotel_adresse']) ?></td>
                        <td><?= htmlspecialchars($reservation['chambre_numero']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p>merci pour votre confiences</p>
    <?php endif; ?>

    <div class="actions">
        <a href=<?php echo BASE_URL . 'index.php'; ?> class="btn"> Retour à l'accueil</a>
        <!--  <a href="mes_reservations.php" class="btn"> Voir mes réservations</a> -->
    </div>
</div>