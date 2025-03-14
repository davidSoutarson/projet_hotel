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
}
