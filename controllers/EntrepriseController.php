<?php
require_once __DIR__ . '/../config/configuration.php';
require_once MODEL_PATH . 'Entreprise.php';
require_once CONTROLLER_PATH . 'SessionEntrController.php';
/**
 * Contrôleur pour la gestion des Entreprise.
 */
class EntrepriseController
{

    private $entrepriseModel;

    /**
     * Initialisation du modèle Entreprise.
     */
    public function __construct()
    {
        $this->entrepriseModel = new Entreprise();
    }

    /**
     * Inscription d'une Entreprise avec gestion de session et redirection ver formulaire_connexion_entr.php.
     */
    public function inscrireEntreprise()
    {
        // Récupération des données utilisateur depuis les variables POST formulaire_inscription_entr.php
        $inscription_entr_nom = $_POST['nom'] ?? '';
        $inscription_entr_adresse = $_POST['adresse'] ?? '';
        $inscription_entr_telephone = $_POST['telephone'] ?? '';
        $inscription_entr_email = $_POST['email'] ?? '';
        $inscription_entr_mot_de_passe = $_POST['mot_de_passe'] ?? '';


        // Vérification des champs obligatoires
        if (!empty($inscription_entr_nom) && !empty($inscription_entr_adresse) && !empty($inscription_entr_email) && !empty($inscription_entr_mot_de_passe)) {
            $motDePasseHashe = password_hash($inscription_entr_mot_de_passe, PASSWORD_BCRYPT);

            // Ajout de l'entreprise à la base de données
            $resultat = $this->entrepriseModel->ajouterEntreprise(
                $inscription_entr_nom,
                $inscription_entr_adresse,
                $inscription_entr_telephone,
                $inscription_entr_email,
                $motDePasseHashe
            );

            if ($resultat) {
                // Récupération de l'identifiant de la entreprise inscrite
                $idEntreprise = $this->entrepriseModel->recupererDernierId();

                // Création de la session utilisateur via le contrôleur des sessions
                SessionEntrController::creerSessionEntreprise(
                    $idEntreprise,
                    $inscription_entr_nom,
                    $inscription_entr_adresse,
                    $inscription_entr_email
                );

                // Redirection vers le formulaire de connexion après l'inscription
                header('Location: ../views/entreprise/formulaire_connexion_entr.php');
                exit();
            } else {
                echo "Erreur lors de l'inscription entrepise.";
            }
        } else {
            echo "Tous les champs obligatoires doivent être renseignés.";
        }
    }
}

// Vérification si une soumission POST a été effectuée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entrepriseController = new entrepriseController();
    $entrepriseController->inscrireEntreprise();
}
