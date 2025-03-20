<?php
require_once __DIR__ . '/../config/configuration.php';

class Reservation
{
    private $connexion;
    private $table = "reservations";

    public function __construct()
    {
        try {
            $database = new Database();
            $this->connexion = $database->obtenirConnexion();
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function ajouterReservation($idUtilisateur, $idChambre, $dateArrivee, $dateDepart)
    {
        try {
            $requete = "INSERT INTO " . $this->table . " 
                        (id_utilisateur, id_chambre, date_debut, date_fin) 
                        VALUES (:id_utilisateur, :id_chambre, :date_debut, :date_fin)";
            $stmt = $this->connexion->prepare($requete);

            $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':id_chambre', $idChambre, PDO::PARAM_INT);
            $stmt->bindParam(':date_debut', $dateArrivee, PDO::PARAM_STR);
            $stmt->bindParam(':date_fin', $dateDepart, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de la réservation : " . $e->getMessage());
            return false;
        }
    }

    // Vérification de la disponibilité de la chambre
    public function verifierDisponibilite($idChambre, $dateArrivee, $dateDepart)
    {
        $sql = "SELECT COUNT(*) 
            FROM reservations 
            WHERE id_chambre = :id_chambre 
            AND (
                (date_debut <= :date_fin AND date_fin >= :date_debut)
            )";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_chambre', $idChambre);
        $stmt->bindParam(':date_debut', $dateArrivee);
        $stmt->bindParam(':date_fin', $dateDepart);
        $stmt->execute();

        return $stmt->fetchColumn() == 0;  // Retourne vrai si la chambre est libre
    }

    // fonction récupérant tout les réservation d'un utilisateur

    // Fonction récupérant toutes les réservations d'un utilisateur avec les détails de l'hôtel et de la chambre
    public function getReservationsByUser($idUtilisateur)
    {
        try {
            $sql = "SELECT 
                    r.*, 
                    h.hotel_nom, 
                    h.hotel_adresse, 
                    c.numero AS chambre_numero
                FROM " . $this->table . " r
                JOIN chambres c ON r.id_chambre = c.id
                JOIN hotels h ON c.id_hotel = h.id
                WHERE r.id_utilisateur = :id_utilisateur
                ORDER BY r.date_debut DESC";
            $stmt = $this->connexion->prepare($sql);
            $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();

            // Récupère toutes les lignes sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des réservations pour l'utilisateur : " . $e->getMessage());
            return [];  // Retourne un tableau vide en cas d'erreur
        }
    }
}
