<?php
$hotelController = new HotelController();
$hotels = $hotelController->obtenirTousLesHotels();
?>

<div class="grille-hotels">
    <?php if (!empty($hotels)) : ?>
        <?php foreach ($hotels as $hotel) :
            // Récupérer le prix min et max pour chaque hôtel
            $prix_min = $hotelController->obtenirPrixMin($hotel['id']);
            $prix_max = $hotelController->obtenirPrixMax($hotel['id']);
        ?>
            <div class="vignette-hotel" onclick="selectionnerHotel(<?= htmlspecialchars($hotel['id']) ?>)">
                <h2><?= htmlspecialchars($hotel['hotel_nom']) ?></h2>

                <!-- Affichage de l'image de l'hôtel avec image par défaut si aucune image n'existe -->
                <div class="boite_image_hotel">
                    <img class="image_hotel" src="<?= !empty($hotel['photo_hotel']) ? htmlspecialchars($hotel['photo_hotel']) : '/../../images/default_hotel.jpg' ?>" alt="Photo de l'hôtel">
                </div>

                <!-- Informations sur l'hôtel -->
                <!-- jouter la vile si posible -->
                <p><?= htmlspecialchars($hotel['hotel_adresse']) ?></p>
                <p><?= htmlspecialchars($hotel['telephone']) ?></p>
                </br>
                <p><?= htmlspecialchars($hotel['description_hotel']) ?></p>
                </br>
                <p>Prix : <?= htmlspecialchars($prix_min) ?>€ à <?= htmlspecialchars($prix_max) ?>€</p>

                <!-- Bouton de sélection via un formulaire POST-->
                <form class="fon-tansparent" action="/projet_hotel/controllers/ReservationController.php" method="POST">
                    <input type="hidden" name="action" value="selection_hotel">
                    <input type="hidden" name="id_hotel" value="<?= htmlspecialchars($hotel['id']) ?>">
                    <button class="btn" type="submit">Sélectionner cet hôtel</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Aucun hôtel disponible pour le moment.</p>
    <?php endif; ?>
</div>