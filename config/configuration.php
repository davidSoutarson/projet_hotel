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


define('BASE_URL', 'http://localhost/projet_hotel/');

define('BASE_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);


// Définir le chemin vers le répertoire CSS
define('CSS_PATH', BASE_URL . 'css/');

define('MODEL_PATH', BASE_PATH . 'models' . DIRECTORY_SEPARATOR);
define('CONTROLLER_PATH', BASE_PATH . 'controllers' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', BASE_PATH . 'views' . DIRECTORY_SEPARATOR);
define('UPLOADS_PATH', BASE_PATH . 'uploads' . DIRECTORY_SEPARATOR);

//define('UTLISATEUR_PATH', VIEWS_PATH . 'fomulaire_incription' . DIRECTORY_SEPARATOR);

$configTeste = "appel du fichier config/configuration.php 01 OK";
