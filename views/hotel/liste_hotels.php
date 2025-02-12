<?php
if (!empty($hotels)) {
    echo '<div class="grille-hotels">';
    foreach ($hotels as $hotel) {
        echo '<div class="vignette-hotel">';
        echo '<h2>' . $hotel['nom'] . '</h2>';
        echo '<p>' . $hotel['adresse'] . '</p>';
        echo '<p>' . $hotel['telephone'] . '</p>';
        echo '<p>' . $hotel['description_hotel'] . '</p>';
        echo '<div class="boite_image_hotel"><img class="image_hotel" src="' . $hotel['photo_hotel'] . '" alt="Photo de l\'hôtel"></div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>Aucun hôtel disponible.</p>';
}
