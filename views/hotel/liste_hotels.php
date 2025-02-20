<?php
require_once '../header.php';
require_once '../../controllers/HotelController.php';
require_once '../../controllers/SessionUtilController.php';

// Vérifier si l'utilisateur est connecté
if (!SessionUtilController::verifierSession()) {
    header('Location: ../utilisateur/formulaire_connexion.php?erreur=non_connecte');
    exit();
}

$hotelController = new HotelController();
$hotels = $hotelController->obtenirTousLesHotels();
?>

<h2>Liste des hôtels</h2>

<div class="hotel-list">
    <?php foreach ($hotels as $hotel) :
        // Récupérer le prix min/max des chambres de cet hôtel
        $prix_min = $hotelController->obtenirPrixMin($hotel['id']);
        $prix_max = $hotelController->obtenirPrixMax($hotel['id']);
    ?>
        <div class="hotel-card" onclick="selectionnerHotel(<?= $hotel['id'] ?>)">
            <h3><?= htmlspecialchars($hotel['nom']) ?></h3>
            <p>Prix : <?= $prix_min ?>€ - <?= $prix_max ?>€</p>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function selectionnerHotel(id_hotel) {
        window.location.href = "selection_chambres.php?id_hotel=" + id_hotel;
    }
</script>

<?php require_once '../footer.php'; ?>