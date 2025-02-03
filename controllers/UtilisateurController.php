<?php
require_once '../config/configuration.php';
require_once MODEL_PATH . 'Utilisateur.php';

class UtilisateurController
{
    private $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
    }

    public function inscrireUtilisateur()
    {
        // Vérification si les champs POST sont définis
        $inscription_util_nom = $_POST['nom'] ?? '';
        $inscription_util_prenom = $_POST['prenom'] ?? '';
        $inscription_util_telephone = $_POST['telephone'] ?? '';
        $inscription_util_email = $_POST['email'] ?? '';
        $inscription_util_mot_de_passe = $_POST['mot_de_passe'] ?? '';

        // Vérification des champs obligatoires
        if (!empty($inscription_util_nom) && !empty($inscription_util_prenom) && !empty($inscription_util_email) && !empty($inscription_util_mot_de_passe)) {
            $motDePasseHashe = password_hash($inscription_util_mot_de_passe, PASSWORD_BCRYPT);

            // Ajout de l'utilisateur à la base de données
            $resultat = $this->utilisateurModel->ajouterUtilisateur(
                $inscription_util_nom,
                $inscription_util_prenom,
                $inscription_util_telephone,
                $inscription_util_email,
                $motDePasseHashe
            );

            if ($resultat) {
                echo "Inscription réussie !";
            } else {
                echo "Erreur lors de l'inscription.";
            }
        } else {
            echo "Tous les champs obligatoires doivent être renseignés.";
        }
    }
}

// Vérifiez si une soumission POST a été effectuée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateurController = new UtilisateurController();
    $utilisateurController->inscrireUtilisateur();
}

// verifier la creation de table utilisateur base de donne

//3. Améliorations possibles
# Redirection après l'inscription : Au lieu de simplement afficher un message, vous pouvez rediriger vers une page de confirmation.
# Validation de l'email et sécurité : Ajoutez des vérifications supplémentaires pour l'email et des contraintes pour les mots de passe.
