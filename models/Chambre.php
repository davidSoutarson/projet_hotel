<?php
require_once __DIR__ . '/../config/configuration.php';

class Chambre
{
    private $connexion;
    private $table = "chambres"; // Nom de la table correspondante

    /**
     * Constructeur : Initialise la connexion à la base de données
     */
    public function __construct()
    {
        $database = new Database();
        $this->connexion = $database->obtenirConnexion();

        // Vérification de la connexion à la base de données
        if (!$this->connexion) {
            die("Erreur : Connexion à la base de données échouée.");
        }
    }

    /**
     * Ajoute une chambre à l'hôtel
     *
     * @param string $numero Numéro de la chambre
     * @param float $prix Prix de la chambre
     * @param int $nombre_lits Nombre de lits
     * @param string|null $description Description de la chambre
     * @param string|null $photo URL de la photo
     * @param string $etat État de la chambre ('libre' ou 'réservé')
     * @param int $id_hotel ID de l'hôtel auquel appartient la chambre
     * @return bool Retourne vrai si l'ajout a réussi, sinon faux
     */
    public function ajouterChambre($numero, $prix, $nombre_lits, $description, $photo, $etat, $id_hotel)
    {
        $requete = "INSERT INTO " . $this->table . " (numero, prix, nombre_lits, description_chambre, photo_chambre, etat, id_hotel) 
                    VALUES (:numero, :prix, :nombre_lits, :description_chambre, :photo_chambre, :etat, :id_hotel)";

        $stmt = $this->connexion->prepare($requete);

        // Bind des valeurs
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':nombre_lits', $nombre_lits);
        $stmt->bindParam(':description_chambre', $description);
        $stmt->bindParam(':photo_chambre', $photo);
        $stmt->bindParam(':etat', $etat);
        $stmt->bindParam(':id_hotel', $id_hotel);

        // Exécution de la requête et vérification
        if ($stmt->execute()) {
            return true;
        } else {
            // Affiche les erreurs SQL si échec
            echo "Erreur SQL: ";
            print_r($stmt->errorInfo());
            return false;
        }
    }

    /**
     * Récupère toutes les chambres d'un hôtel donné
     *
     * @param int $id_hotel ID de l'hôtel
     * @return array Liste des chambres
     */
    public function obtenirChambresParHotel($id_hotel)
    {
        $requete = "SELECT * FROM " . $this->table . " WHERE id_hotel = :id_hotel";
        $stmt = $this->connexion->prepare($requete);
        $stmt->bindParam(':id_hotel', $id_hotel, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le nombre total de chambres pour un hôtel
     *
     * @param int $id_hotel ID de l'hôtel
     * @return int Nombre de chambres
     */
    public function getNombreChambres($id_hotel)
    {
        $requete = "SELECT COUNT(*) FROM " . $this->table . " WHERE id_hotel = :id_hotel";
        $stmt = $this->connexion->prepare($requete);
        $stmt->bindParam(':id_hotel', $id_hotel, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
