<?php
require_once 'config/config.php';

class Utilisateur
{
    private $connexion;
    private $table = "utilisateurs";

    public function __construct()
    {
        $database = new Database();
        $this->connexion = $database->obtenirConnexion();
    }

    public function ajouterUtilisateur($nom, $prenom, $telephone, $email, $motDePasse)
    {
        $requete = "INSERT INTO " . $this->table . " (nom, prenom, telephone, email, mot_de_passe) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connexion->prepare($requete);
        return $stmt->execute([$nom, $prenom, $telephone, $email, $motDePasse]);
    }
}
