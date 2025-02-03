<?php
require_once 'config/configuration.php';
require_once 'config/creation_database.php';
require_once 'views/header.php';
require_once 'controllers/HotelController.php';
//index.php ne fonction pas comme prévue! a resoudre  
# panset a lencer lecriutre de de la base de donnner 
# il faut ajouter le css au projet
$hotelController = new HotelController();
$hotels = $hotelController->obtenirTousLesHotels();

require_once 'views/hotel/liste_hotels.php';
require_once 'views/footer.php';
