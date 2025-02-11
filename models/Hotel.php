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

    public function ajouterHotel($nomHotel, $adresseHotel, $telephoneHotel, $descriptionHotel, $photoHotel, $id_entreprise)
    {
        $requete = "INSERT INTO " . $this->table . " (nom, adresse, telephone, description, photo, id_entreprise) 
                VALUES (:nom, :adresse, :telephone, :description, :photo, :id_entreprise)";
        $stmt = $this->connexion->prepare($requete);
        $stmt->bindParam(':nom', $nomHotel);
        $stmt->bindParam(':adresse', $adresseHotel);
        $stmt->bindParam(':telephone', $telephoneHotel);
        $stmt->bindParam(':description', $descriptionHotel);
        $stmt->bindParam(':photo', $photoHotel);
        $stmt->bindParam(':id_entreprise', $id_entreprise);
        $stmt->execute();
    }
    public function recupererDernierId()
    {
        return $this->connexion->lastInsertId();
    }
}
