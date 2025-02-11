<?php
// Inclusion du fichier de configuration et du contrôleur de session entreprise
require_once '../config/configuration.php';
require_once 'SessionEntrController.php';

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
            $requete = $connexion->prepare('SELECT * FROM entreprises WHERE email = :email');
            $requete->bindParam(':email', $email);
            $requete->execute();

            $entreprise = $requete->fetch(PDO::FETCH_ASSOC);

            // Vérifie si l'entreprise existe et si le mot de passe correspond (en comparant le hash)
            if ($entreprise && password_verify($motDePasse, $entreprise['mot_de_passe'])) {
                // Création de la session entreprise via le contrôleur de session dédié
                SessionEntrController::creerSessionEntreprise(
                    $entreprise['id'],
                    $entreprise['nom'],
                    $entreprise['adresse'],
                    $entreprise['email']
                );
                $_SESSION['success'] = "Connexion entreprise réussie.";
                // Redirection vers la page d'accueil. Utilisez BASE_URL si elle est définie.
                header('Location: ../index.php');
                exit();
            } else {
                // Si les informations sont incorrectes, détruire toute session existante
                SessionEntrController::detruireSession();
                // Redirection vers le formulaire de connexion entreprise avec un message d'erreur
                header('Location: ../views/entreprise/formulaire_connexion_entr.php?erreur=1');
                exit();
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de connexion à la base de données
            error_log('Erreur de connexion à la base de données : ' . $e->getMessage());
            header('Location: ../views/entreprise/formulaire_connexion_entr.php?erreur=2');
            exit();
        }
    } else {
        // Si les champs requis ne sont pas remplis, rediriger avec un message d'erreur
        header('Location: ../views/entreprise/formulaire_connexion_entr.php?erreur=3');
        exit();
    }
} else {
    // Si la requête n'est pas de type POST, rediriger vers le formulaire de connexion entreprise
    header('Location: ../views/entreprise/formulaire_connexion_entr.php');
    exit();
}

// Gestion de l'action "deconnexion" pour les entreprises
if (isset($_GET['action']) && $_GET['action'] === 'deconnexion') {
    // Appelle la méthode pour détruire la session
    SessionEntrController::detruireSession();

    // Redirection après déconnexion
    header('Location: ../index.php'); // Page de redirection après la déconnexion
    exit();
}
