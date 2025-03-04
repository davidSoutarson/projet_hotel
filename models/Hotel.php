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

    public function ajouterHotel($nomHotel, $adresseHotel, $telephoneHotel, $description_hotel, $nombre_chambres, $photoHotel, $id_entreprise, $id_ville)
    {
        $requete = "INSERT INTO " . $this->table . " (hotel_nom, hotel_adresse, telephone, description_hotel, nombre_chambres, photo_hotel, id_entreprise, :id_ville) 
                VALUES (:hotel_nom, :hotel_adresse, :telephone, :description_hotel,:nombre_chambres, :photo_hotel, :id_entreprise, :id_ville)";

        $stmt = $this->connexion->prepare($requete);
        $stmt->bindParam(':hotel_nom', $nomHotel); // a verifier
        $stmt->bindParam(':hotel_adresse', $adresseHotel); //a verifier
        $stmt->bindParam(':telephone', $telephoneHotel);
        $stmt->bindParam(':description_hotel', $description_hotel);
        $stmt->bindParam(':nombre_chambres', $nombre_chambres);
        $stmt->bindParam(':photo_hotel', $photoHotel);
        $stmt->bindParam(':id_entreprise', $id_entreprise);
        $stmt->bindParam(':id_ville', $id_ville); // a vrifier

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

    public function obtenirHotelParId($id_hotel)
    {
        $sql = "SELECT * FROM hotels WHERE id = :id_hotel";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_hotel', $id_hotel, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* cette fonction retoune le non bbre de canchambr dun hotel en fontion de son id */
    public function getNombreChambres($id_hotel)
    {
        $sql = "SELECT nombre_chambres FROM hotels WHERE id = :id_hotel";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_hotel', $id_hotel, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn(); // Retourne directement le nombre
    }

    /*le nom de chaque hôtel présent dans la base de données.*/
    public function obtenirNomsHotels()
    {
        $requete = "SELECT nom FROM " . $this->table;
        $stmt = $this->connexion->prepare($requete);
        $stmt->execute();
        // Retourne un tableau contenant uniquement les valeurs de la colonne 'nom'
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
