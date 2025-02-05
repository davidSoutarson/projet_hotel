<?php
require_once '../config/configuration.php';
require_once 'SessionUtilController.php';

/**
 * Nettoie une chaîne de caractères en supprimant les balises HTML et en échappant les caractères spéciaux.
 *
 * @param string $data La donnée à nettoyer.
 * @return string La donnée nettoyée.
 */
function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation de l'email
    $emailBrut = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $emailValide = filter_var($emailBrut, FILTER_VALIDATE_EMAIL);

    // Récupération de la donnée brute du mot de passe
    $motDePasseBrut = filter_input(INPUT_POST, 'mot_de_passe', FILTER_UNSAFE_RAW);

    // Application de notre fonction de nettoyage
    $email = $emailValide ? sanitize($emailValide) : null;
    $motDePasse = $motDePasseBrut ? sanitize($motDePasseBrut) : null;

    if ($email && $motDePasse) {
        try {
            $database = new Database();
            $connexion = $database->obtenirConnexion();

            $requete = $connexion->prepare('SELECT * FROM utilisateurs WHERE email = :email');
            $requete->bindParam(':email', $email);
            $requete->execute();

            $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

            if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
                SessionUtilController::creerSessionUtilisateur(
                    $utilisateur['id'],
                    $utilisateur['nom'],
                    $utilisateur['prenom'],
                    $utilisateur['adresse'],
                    $utilisateur['email']
                );
                $_SESSION['success'] = "Connexion a réussie.";
                header('Location: ../index.php');
                exit();
            } else {
                SessionUtilController::detruireSession();
                header('Location: ../views/utilisateur/formulaire_connexion_util.php?erreur=1');
                exit();
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de connexion à la base de données
            error_log('Erreur de connexion à la base de données : ' . $e->getMessage());
            header('Location: ../views/utilisateur/formulaire_connexion_util.php?erreur=2');
            exit();
        }
    } else {
        // Données du formulaire invalides
        header('Location: ../views/utilisateur/formulaire_connexion_util.php?erreur=3');
        exit();
    }
} else {
    // Si la requête n'est pas de type POST, rediriger vers le formulaire de connexion
    header('Location: ../views/utilisateur/formulaire_connexion_util.php');
    exit();
}

// Gestion de l'action "deconnexion"
if (isset($_GET['action']) && $_GET['action'] === 'deconnexion') {
    // Appelle la méthode pour détruire la session
    SessionUtilController::detruireSession();

    // Redirection après déconnexion
    header('Location: ../index.php'); // Page de redirection après la déconnexion
    exit();
}
