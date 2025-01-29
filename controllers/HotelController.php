<?php
require_once 'models/Hotel.php';

class HotelController
{
    private $hotelModel;

    public function __construct()
    {
        $this->hotelModel = new Hotel();
    }

    public function obtenirTousLesHotels()
    {
        return $this->hotelModel->obtenirHotels();
    }
}
