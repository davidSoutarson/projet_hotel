<?php
class SessionUtilController
{
    /**
     * Crée une session pour l'utilisateur avec les informations fournies.
     *
     * @param int $idUtilisateur L'identifiant de l'utilisateur
     * @param string $nom Le nom de l'utilisateur
     * @param string $prenom Le prénom de l'utilisateur
     * @param string $adresse L'adresse de l'utilisateur
     * @param string $email L'email de l'utilisateur
     */
    public static function creerSessionUtilisateur($idUtilisateur, $nom, $prenom, $adresse, $email)
    {
        // Démarre la session si ce n'est pas encore fait
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Stocke les informations utilisateur dans la session
        $_SESSION['utilisateur'] = [
            'id' => $idUtilisateur,
            'nom' => $nom,
            'prenom' => $prenom,
            'adresse' => $adresse,
            'email' => $email
        ];
    }

    /**
     * Détruit la session utilisateur en cours et supprime le cookie de session.
     */
    public static function detruireSession()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            // Supprime toutes les variables de session
            session_unset();

            // Détruit la session en cours
            session_destroy();

            // Supprime également le cookie de session si présent
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(), // Nom du cookie
                    '', // Valeur vide pour suppression
                    time() - 42000, // Expiration passée
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }
        }
    }

    /**
     * Vérifie si une session utilisateur est active.
     *
     * @return bool true si une session utilisateur existe, false sinon.
     */
    public static function verifierSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        return isset($_SESSION['utilisateur']);
    }

    /**
     * Récupère les informations de l'utilisateur connecté.
     *
     * @return array|null Les informations de l'utilisateur ou null si non connecté.
     */
    public static function obtenirUtilisateurConnecte()
    {
        return $_SESSION['utilisateur'] ?? null;
    }
}

// Gestion de l'action "deconnexion"
if (isset($_GET['action']) && $_GET['action'] === 'deconnexion') {
    // Appelle la méthode pour détruire la session
    SessionUtilController::detruireSession();

    // Redirection après déconnexion
    header('Location: ../index.php'); // Page de redirection après la déconnexion
    exit();
}
