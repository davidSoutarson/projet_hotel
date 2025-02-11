<?php
require_once 'config/configuration.php';
require_once 'config/creation_database.php';
require_once 'views/header.php';
require_once 'controllers/HotelController.php';

echo '<h2> Afiche tout les hotels </h2>';

$hotelController = new HotelController();
$hotels = $hotelController->obtenirTousLesHotels();

require_once 'views/hotel/liste_hotels.php';
require_once 'views/footer.php';
