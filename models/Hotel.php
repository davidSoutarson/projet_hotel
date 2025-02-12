<?php
require_once __DIR__ . '/../config/configuration.php';
class Hotel
{
    private $connexion;
    private $table = "hotels"; // Assurez-vous que le nom correspond à votre base de données

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

    public function ajouterHotel($nomHotel, $adresseHotel, $telephoneHotel, $description_hotel, $photoHotel, $id_entreprise)
    {
        $requete = "INSERT INTO " . $this->table . " (nom, adresse, telephone, description_hotel, photo_hotel, id_entreprise) 
                VALUES (:nom, :adresse, :telephone, :description_hotel, :photo_hotel, :id_entreprise)";

        $stmt = $this->connexion->prepare($requete);
        $stmt->bindParam(':nom', $nomHotel);
        $stmt->bindParam(':adresse', $adresseHotel);
        $stmt->bindParam(':telephone', $telephoneHotel);
        $stmt->bindParam(':description_hotel', $description_hotel); // Correction du paramètre
        $stmt->bindParam(':photo_hotel', $photoHotel); // Correction du paramètre
        $stmt->bindParam(':id_entreprise', $id_entreprise);

        if ($stmt->execute()) {
            return $this->connexion->lastInsertId(); // Retourne l'ID du dernier hôtel inséré
        } else {
            return false; // En cas d'échec
        }
    }

    public function recupererDernierId()
    {
        return $this->connexion->lastInsertId();
    }
}
