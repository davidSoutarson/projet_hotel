<?php
require_once __DIR__ . '/../config/configuration.php';
require_once MODEL_PATH . 'Utilisateur.php';
require_once CONTROLLER_PATH . 'SessionUtilController.php';

/**
 * Contrôleur pour la gestion des utilisateurs.
 */
class UtilisateurController
{
    private $utilisateurModel;

    /**
     * Initialisation du modèle Utilisateur.
     */
    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
    }

    /**
     * Inscription d'un utilisateur avec gestion de session et redirection.
     */
    public function inscrireUtilisateur()
    {
        // Récupération des données utilisateur depuis les variables POST
        $inscription_util_nom = $_POST['nom'] ?? '';
        $inscription_util_prenom = $_POST['prenom'] ?? '';
        $inscription_util_adresse = $_POST['adresse'] ?? '';
        $inscription_util_telephone = $_POST['telephone'] ?? '';
        $inscription_util_email = $_POST['email'] ?? '';
        $inscription_util_mot_de_passe = $_POST['mot_de_passe'] ?? '';

        // Vérification des champs obligatoires
        if (!empty($inscription_util_nom) && !empty($inscription_util_prenom) && !empty($inscription_util_adresse) && !empty($inscription_util_email) && !empty($inscription_util_mot_de_passe)) {
            $motDePasseHashe = password_hash($inscription_util_mot_de_passe, PASSWORD_BCRYPT);

            // Ajout de l'utilisateur à la base de données
            $resultat = $this->utilisateurModel->ajouterUtilisateur(
                $inscription_util_nom,
                $inscription_util_prenom,
                $inscription_util_adresse,
                $inscription_util_telephone,
                $inscription_util_email,
                $motDePasseHashe
            );

            if ($resultat) {
                // Récupération de l'identifiant du dernier utilisateur inscrit
                $idUtilisateur = $this->utilisateurModel->recupererDernierId();

                // Création de la session utilisateur via le contrôleur des sessions
                SessionUtilController::creerSessionUtilisateur(
                    $idUtilisateur,
                    $inscription_util_nom,
                    $inscription_util_prenom,
                    $inscription_util_adresse,
                    $inscription_util_email
                );

                // Redirection vers le formulaire de connexion après l'inscription
                header('Location: ../views/utilisateur/formulaire_connexion_util.php');
                exit();
            } else {
                echo "Erreur lors de l'inscription utilisateur.";
            }
        } else {
            echo "Tous les champs obligatoires doivent être renseignés.";
        }
    }
}

// Vérification si une soumission POST a été effectuée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateurController = new UtilisateurController();
    $utilisateurController->inscrireUtilisateur();
}
