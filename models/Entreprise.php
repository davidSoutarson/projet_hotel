<?php
require_once __DIR__ . '/../config/configuration.php';

class Entreprise
{
    private $connexion;

    public function __construct()
    {
        $database = new Database();
        $this->connexion = $database->obtenirConnexion();
    }

    public function ajouterEntreprise($nom, $adresse, $telephone, $email, $motDePasse)
    {
        $requete = "INSERT INTO entreprises (nom, adresse, telephone, email, mot_de_passe)
                    VALUES (:nom, :adresse, :telephone, :email, :mot_de_passe)";
        $stmt = $this->connexion->prepare($requete);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $motDePasse);

        return $stmt->execute();
    }

    public function recupererDernierId()
    {
        return $this->connexion->lastInsertId();
    }
}
