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

/* definition des chemin pour l'enseble du proget */

define('BASE_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
define('MODEL_PATH', BASE_PATH . 'models' . DIRECTORY_SEPARATOR);
define('CONTROLLER_PATH', BASE_PATH . 'controllers' . DIRECTORY_SEPARATOR);

echo '<p>je suis le fichier config/configurartion.php</p>';
