<?php
require_once __DIR__ . '/../config/configuration.php';

class Chambre
{
    // Propriété pour la connexion à la base de données
    private $connexion;
    // Nom de la table dans la base de données
    private $table = "chambres";

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
     * Ajoute une chambre à l'hôtel.
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
        $requete = "INSERT INTO " . $this->table . " 
                    (numero, prix, nombre_lits, description_chambre, photo_chambre, etat, id_hotel) 
                    VALUES (:numero, :prix, :nombre_lits, :description_chambre, :photo_chambre, :etat, :id_hotel)";

        $stmt = $this->connexion->prepare($requete);

        // Liaison des paramètres pour sécuriser la requête contre les injections SQL
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':nombre_lits', $nombre_lits);
        $stmt->bindParam(':description_chambre', $description);
        $stmt->bindParam(':photo_chambre', $photo);
        $stmt->bindParam(':etat', $etat);
        $stmt->bindParam(':id_hotel', $id_hotel);

        // Exécution de la requête et vérification de son succès
        if ($stmt->execute()) {
            return true;
        } else {
            // Affiche les erreurs SQL en cas d'échec (à enlever en production ou à logger)
            echo "Erreur SQL: ";
            print_r($stmt->errorInfo());
            return false;
        }
    }

    /**
     * Récupère toutes les chambres d'un hôtel donné.
     *
     * @param int $id_hotel ID de l'hôtel
     * @return array Liste des chambres (tableau associatif)
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
     * Retourne le nombre total de chambres pour un hôtel donné.
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

    /**
     * Récupère le dernier numéro de chambre utilisé pour un hôtel donné.
     *
     * @param int $id_hotel ID de l'hôtel
     * @return mixed Retourne le dernier numéro ou 0 s'il n'existe pas
     */
    public function getDernierNumeroChambre($id_hotel)
    {
        // Préparation de la requête SQL pour récupérer le plus grand numéro de chambre
        $requete = "SELECT MAX(numero) AS dernier_numero FROM " . $this->table . " WHERE id_hotel = :id_hotel";
        $stmt = $this->connexion->prepare($requete);
        $stmt->bindParam(':id_hotel', $id_hotel, PDO::PARAM_INT);
        $stmt->execute();
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        // Retourne le dernier numéro trouvé, ou 0 si aucune chambre n'est présente
        return $resultat['dernier_numero'] ?? 0;
    }

    /**
     * Récupère toutes les chambres.
     *
     * @return array Liste de toutes les chambres
     */
    public function obtenirToutesLesChambres()
    {
        $requete = "SELECT * FROM " . $this->table;
        $stmt = $this->connexion->prepare($requete);
        if (!$stmt->execute()) {
            return [];  // Retourne un tableau vide en cas d'erreur
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si une chambre existe dans la base de données.
     *
     * @param int $idChambre ID de la chambre
     * @return bool Retourne vrai si la chambre existe, sinon faux
     */
    public function existeChambre($idChambre)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table . " WHERE id = :id_chambre";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_chambre', $idChambre, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }


    /**
     * Récupère toutes les chambres libres d'un hôtel donné
     *
     * @param int $id_hotel ID de l'hôtel
     * @return array Liste des chambres libres
     */
    public function obtenirChambresLibresParHotel($id_hotel)
    {
        try {
            $requete = "SELECT * FROM " . $this->table . " WHERE id_hotel = :id_hotel AND etat = 'libre'";
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindParam(':id_hotel', $id_hotel, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];  // Retourne un tableau vide si aucune chambre libre
        } catch (PDOException $e) {
            // Journaliser l'erreur pour le suivi et afficher un message utilisateur approprié
            error_log("Erreur lors de la récupération des chambres : " . $e->getMessage());
            return [];
        }
    }

    /** Fin de la classe Chambre */
}
