<?php
require_once __DIR__ . '/../config/configuration.php';

/* Ce modèle gère la vérification de l'existence d'une ville et son insertion dans la table villes_francais si nécessaire. */

class Ville
{
    private $pdo;

    public function __construct()
    {
        // Initialisation de la connexion à la base de données via la classe Database
        $database = new Database();
        $this->pdo = $database->obtenirConnexion();
    }

    /**
     * Vérifie si la ville existe déjà dans la table 'villes_francais'.
     * Si elle n'existe pas, l'insère et renvoie son ID.
     *
     * @param string $nom       Nom de la ville
     * @param string $codePostal Code postal de la ville
     * @return mixed            ID de la ville ou false en cas d'erreur
     */
    public function verifierEtInsererVille($nom, $codePostal)
    {
        // Vérifier si la ville existe déjà
        $stmt = $this->pdo->prepare("SELECT id FROM villes_francais WHERE nom_ville = :nom AND code_postal_hotel = :code_postal");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':code_postal', $codePostal);
        $stmt->execute();
        $cityId = $stmt->fetchColumn();

        if (!$cityId) {
            // Insertion de la ville dans la table 'villes_francais'
            $stmtInsert = $this->pdo->prepare("INSERT INTO villes_francais (nom_ville, code_postal_hotel) VALUES (:nom, :code_postal)");
            $stmtInsert->bindParam(':nom', $nom);
            $stmtInsert->bindParam(':code_postal', $codePostal);
            if ($stmtInsert->execute()) {
                $cityId = $this->pdo->lastInsertId();
            } else {
                return false;
            }
        }
        return $cityId;
    }
}
