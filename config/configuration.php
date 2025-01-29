<?php
class Database
{
    private $hote = "localhost";
    private $nom_base_donnees = "hotel_database";
    private $utilisateur = "root";
    private $mot_de_passe = "";
    public $connexion;

    public function obtenirConnexion()
    {
        $this->connexion = null;
        try {
            $this->connexion = new PDO("mysql:host=" . $this->hote . ";dbname=" . $this->nom_base_donnees, $this->utilisateur, $this->mot_de_passe);
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        return $this->connexion;
    }
}
