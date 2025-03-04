<?php
$config = require __DIR__ . '/config.php';
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

/* Definition des chemin pour l'enseble du proget */

if (php_sapi_name() === 'cli') {
    define('BASE_URL', $config['urls'][$config['environment']]);
} else {
    if ($config['environment'] === 'production') {
        define('BASE_URL', $config['urls']['production']);
    } else {
        // Pour le développement, on adapte éventuellement le host si nécessaire
        $host = ($_SERVER['HTTP_HOST'] === 'projet_hotel.test') ? 'localhost' : $_SERVER['HTTP_HOST'];
        define(
            'BASE_URL',
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
                . "://"
                . $host
                . '/projet_hotel/'
        );
    }
}


define('BASE_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

// Définir le chemin vers le répertoire views
define('VIEWS_LIEN', BASE_URL . 'views' . '/');


//?
define('CSS_PATH', BASE_URL . 'css/');


$configTeste = BASE_URL;


// Definir les inclution requette de fichier
define('CONFIG_PATH', BASE_PATH . 'config' . DIRECTORY_SEPARATOR);

define('CONTROLLER_PATH', BASE_PATH . 'controllers' . DIRECTORY_SEPARATOR);

define('IMAGE_PATH', BASE_PATH . 'images' . DIRECTORY_SEPARATOR);

define('LOG_PATH', BASE_PATH . 'log' . DIRECTORY_SEPARATOR);

define('MODEL_PATH', BASE_PATH . 'models' . DIRECTORY_SEPARATOR);

define('UPLOADS_PATH', BASE_PATH . 'uploads' . DIRECTORY_SEPARATOR);

define('VIEWS_PATH', BASE_PATH . 'views' . DIRECTORY_SEPARATOR);

####################################################################################

//ok define('BASE_URL', 'http://localhost/projet_hotel/');
