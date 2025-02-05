<?php
require_once __DIR__ . '/../config/configuration.php';

class Entreprise
{
    private $connexion;
    private $table = "entreprises";

    public function __construct()
    {
        $database = new Database();
        $this->connexion = $database->obtenirConnexion();
    }

    public function ajouterEntreprise($nom, $adresse, $telephone, $email, $motDePasse)
    {
        $requete = "INSERT INTO " . $this->table . " (nom, adresse, telephone, email, mot_de_passe) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connexion->prepare($requete);
        return $stmt->execute([$nom, $adresse, $telephone, $email, $motDePasse]);
    }
}
