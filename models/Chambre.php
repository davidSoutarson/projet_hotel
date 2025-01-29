<?php
require_once 'config/config.php';

class Chambre
{
    private $connexion;
    private $table = "chambres";

    public function __construct()
    {
        $database = new Database();
        $this->connexion = $database->obtenirConnexion();
    }

    public function ajouterChambre($numero, $prix, $nombreLits, $etat, $idHotel)
    {
        $requete = "INSERT INTO " . $this->table . " (numero, prix, nombre_lits, etat, id_hotel) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connexion->prepare($requete);
        return $stmt->execute([$numero, $prix, $nombreLits, $etat, $idHotel]);
    }
}
