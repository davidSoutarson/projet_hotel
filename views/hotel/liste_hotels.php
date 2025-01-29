<?php
if (!empty($hotels)) {
    echo '<div class="grille-hotels">';
    foreach ($hotels as $hotel) {
        echo '<div class="vignette-hotel">';
        echo '<h2>' . $hotel['nom'] . '</h2>';
        echo '<p>' . $hotel['description'] . '</p>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>Aucun hôtel disponible.</p>';
}
