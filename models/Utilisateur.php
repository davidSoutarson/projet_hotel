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

    public function ajouterUtilisateur($nom, $prenom, $telephone, $email, $motDePasse)
    {
        $requete = "INSERT INTO utilisateurs (nom, prenom, telephone, email, mot_de_passe)
                    VALUES (:nom, :prenom, :telephone, :email, :mot_de_passe)";
        $stmt = $this->connexion->prepare($requete);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $motDePasse);

        return $stmt->execute();
    }
}


echo '<p>je suis le fichier models/Utilisateur.php</p>';
