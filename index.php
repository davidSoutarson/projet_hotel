<?php
require_once 'config/configuration.php';
require_once 'config/creation_database.php';
require_once 'views/header.php';
require_once 'controllers/HotelController.php';

echo '<h2> Afiche tout les hotels </h2>';

$hotelController = new HotelController();
$hotels = $hotelController->obtenirTousLesHotels();

// Afficher les hôtels (exemple avec var_dump pour déboguer)
/* echo '<pre>';
var_dump($hotels);
echo '</pre>'; */

require_once 'views/hotel/liste_hotels.php';
require_once 'views/footer.php';
