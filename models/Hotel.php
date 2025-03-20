<?php
// Inclusion du fichier de configuration contenant les paramètres de connexion à la base de données
require_once __DIR__ . '/../config/configuration.php';

// Définition de la classe Hotel pour gérer les opérations liées aux hôtels
class Hotel
{
    // Propriétés privées pour la connexion à la base de données et le nom de la table
    private $connexion;
    private $table = "hotels"; // Nom de la table contenant les informations sur les hôtels

    // Constructeur de la classe : initialise la connexion à la base de données
    public function __construct()
    {
        $database = new Database();
        $this->connexion = $database->obtenirConnexion();
    }

    // Récupère la liste complète des hôtels dans la base de données
    public function obtenirHotels()
    {
        $requete = "SELECT * FROM " . $this->table;
        $stmt = $this->connexion->prepare($requete);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne les résultats sous forme de tableau associatif
    }

    // Ajoute un nouvel hôtel dans la base de données
    public function ajouterHotel($nomHotel, $adresseHotel, $telephoneHotel, $description_hotel, $nombre_chambres, $photoHotel, $id_entreprise, $id_ville)
    {
        $requete = "INSERT INTO " . $this->table . " 
                (hotel_nom, hotel_adresse, telephone, description_hotel, nombre_chambres, photo_hotel, id_entreprise, id_ville) 
                VALUES (:hotel_nom, :hotel_adresse, :telephone, :description_hotel, :nombre_chambres, :photo_hotel, :id_entreprise, :id_ville)";

        $stmt = $this->connexion->prepare($requete);

        // Liaison des paramètres pour éviter les injections SQL
        $stmt->bindParam(':hotel_nom', $nomHotel);
        $stmt->bindParam(':hotel_adresse', $adresseHotel);
        $stmt->bindParam(':telephone', $telephoneHotel);
        $stmt->bindParam(':description_hotel', $description_hotel);
        $stmt->bindParam(':nombre_chambres', $nombre_chambres);
        $stmt->bindParam(':photo_hotel', $photoHotel);
        $stmt->bindParam(':id_entreprise', $id_entreprise);
        $stmt->bindParam(':id_ville', $id_ville);

        // Exécution de la requête et retour de l'ID de l'hôtel ajouté en cas de succès
        if ($stmt->execute()) {
            return $this->connexion->lastInsertId();
        } else {
            return false; // En cas d'échec
        }
    }

    // Retourne l'ID du dernier hôtel inséré
    public function recupererDernierId()
    {
        return $this->connexion->lastInsertId();
    }

    // Récupère les informations d'un hôtel spécifique en fonction de son ID
    public function obtenirHotelParId($id_hotel)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id_hotel";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_hotel', $id_hotel, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne les données sous forme de tableau associatif
    }

    // Retourne le nombre de chambres d'un hôtel en fonction de son ID
    public function getNombreChambres($id_hotel)
    {
        $sql = "SELECT nombre_chambres FROM " . $this->table . " WHERE id = :id_hotel";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_hotel', $id_hotel, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn(); // Retourne uniquement la colonne contenant le nombre de chambres
    }

    // Retourne uniquement les noms de tous les hôtels
    public function obtenirNomsHotels()
    {
        $requete = "SELECT hotel_nom FROM " . $this->table;
        $stmt = $this->connexion->prepare($requete);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Retourne les résultats sous forme de tableau contenant les noms des hôtels
    }

    // Récupère tous les hôtels appartenant à une entreprise spécifique
    public function obtenirHotelsParEntreprise($id_entreprise)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE id_entreprise = :id_entreprise";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenirVilles()
    {
        $requete = "
        SELECT v.id AS id_ville, v.nom_ville AS nom_ville
        FROM villes_francais v
        INNER JOIN hotels h ON v.id = h.id_ville
        GROUP BY v.id, v.nom_ville
        ";

        $stmt = $this->connexion->prepare($requete);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif avec ID et nom des villes
    }

    // Récupère les hôtels avec leurs noms et les villes associées
    public function obtenirNomsHotelsV2()
    {
        $requete = "
        SELECT h.id, h.hotel_nom, v.nom_ville AS ville
        FROM " . $this->table . " h
        INNER JOIN villes_francais v ON h.id_ville = v.id
        ";
        $stmt = $this->connexion->prepare($requete);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère les hôtels d'une ville spécifique
    public function obtenirHotelsParVille($villeId)
    {
        $sql = "SELECT id, hotel_nom FROM " . $this->table . " WHERE id_ville = :villeId";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':villeId', $villeId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne les hôtels correspondant à la ville donnée
    }

    //++
    // Récupère le nom d'une ville par son ID
    public function obtenirNomVilleParId($idVille)
    {
        $sql = "SELECT nom_ville FROM villes_francais WHERE id = :id_ville";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_ville', $idVille, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn(); // Retourne directement le nom de la ville
    }

    //++
    // Récupère le nom d'un hôtel par son ID
    public function obtenirNomHotelParId($idHotel)
    {
        $sql = "SELECT hotel_nom FROM " . $this->table . " WHERE id = :id_hotel";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_hotel', $idHotel, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn(); // Retourne directement le nom de l'hôtel
    }

    public function existeHotel($hotelId)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table . " WHERE id = :id_hotel";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_hotel', $hotelId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
