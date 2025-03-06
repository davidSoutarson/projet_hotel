<?php
// Inclusion du fichier de configuration et du contrôleur de session entreprise
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

// Vérifie si le formulaire de connexion a été soumis (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données du formulaire
    $emailBrut = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $emailValide = filter_var($emailBrut, FILTER_VALIDATE_EMAIL);

    // Récupération de la donnée brute du mot de passe
    $motDePasseBrut = filter_input(INPUT_POST, 'mot_de_passe', FILTER_UNSAFE_RAW);

    // Application de notre fonction de nettoyage
    $email = $emailValide ? sanitize($emailValide) : null;
    $motDePasse = $motDePasseBrut ? sanitize($motDePasseBrut) : null;

    if ($email && $motDePasse) {
        try {
            // Connexion à la base de données
            $database = new Database();
            $connexion = $database->obtenirConnexion();

            // Préparation de la requête pour chercher l'entreprise par email dans la table 'entreprises'
            $requete = $connexion->prepare('SELECT * FROM utilisateurs WHERE email = :email');
            $requete->bindParam(':email', $email);
            $requete->execute();

            $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

            // Vérifie si l'entreprise existe et si le mot de passe correspond (en comparant le hash)
            if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
                // Création de la session entreprise via le contrôleur de session dédié
                SessionUtilController::creerSessionUtilisateur(
                    $utilisateur['id'],
                    $utilisateur['nom'],
                    $utilisateur['prenom'],
                    $utilisateur['adresse'],
                    $utilisateur['email']
                );
                $_SESSION['success'] = "Connexion utilisateur  réussie.";
                // Redirection vers la page d'accueil. Utilisez BASE_URL si elle est définie.
                header('Location: ../index.php');

                exit();
            } else {
                // Si les informations sont incorrectes, détruire toute session existante
                SessionUtilController::detruireSession();
                // Redirection vers le formulaire de connexion entreprise avec un message d'erreur
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
