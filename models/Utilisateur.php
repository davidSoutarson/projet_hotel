<?php
require_once __DIR__ . '/../config/configuration.php';

class Utilisateur
{
    private $connexion;

    public function __construct()
    {
        $database = new Database();
        $this->connexion = $database->obtenirConnexion();
    }

    public function ajouterUtilisateur($nom, $prenom, $adresse, $telephone, $email, $motDePasse)
    {
        $requete = "INSERT INTO utilisateurs (nom, prenom, adresse, telephone, email, mot_de_passe)
                    VALUES (:nom, :prenom, :adresse, :telephone, :email, :mot_de_passe)";
        $stmt = $this->connexion->prepare($requete);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
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
