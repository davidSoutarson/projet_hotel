<?php
require_once __DIR__ . '/../config/configuration.php';
class Reservation
{
    private $connexion;
    private $table = "reservations";

    public function __construct()
    {
        $database = new Database();
        $this->connexion = $database->obtenirConnexion();
    }

    public function ajouterReservation($idUtilisateur, $idChambre, $dateArrivee, $dateDepart)
    {
        $requete = "INSERT INTO " . $this->table . " (id_utilisateur, id_chambre, date_debut, date_fin) 
                    VALUES (:id_utilisateur, :id_chambre, :date_debut, :date_fin)";
        $stmt = $this->connexion->prepare($requete);

        $stmt->bindParam(':id_utilisateur', $idUtilisateur);
        $stmt->bindParam(':id_chambre', $idChambre);
        $stmt->bindParam(':date_debut', $dateArrivee);
        $stmt->bindParam(':date_fin', $dateDepart);

        return $stmt->execute();
    }
}
