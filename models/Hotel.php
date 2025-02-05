<?php
require_once __DIR__ . '/../config/configuration.php';

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

echo '<p>je suis le fichier Utilisateur.php</p>';
