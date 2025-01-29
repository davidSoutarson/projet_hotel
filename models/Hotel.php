<?php
require_once 'config/config.php';

class Hotel
{
    private $connexion;
    private $table = "hotels";

    public function __construct()
    {
        $database = new Database();
        $this->connexion = $database->obtenirConnexion();
    }

    public function obtenirHotels()
    {
        $requete = "SELECT * FROM " . $this->table;
        $stmt = $this->connexion->prepare($requete);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
