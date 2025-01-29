<?php
require_once 'config/config.php';
require_once 'views/header.php';
require_once 'controllers/HotelController.php';

$hotelController = new HotelController();
$hotels = $hotelController->obtenirTousLesHotels();

require_once 'views/hotel/liste_hotels.php';
require_once 'views/footer.php';
