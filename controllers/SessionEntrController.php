<?php
class SessionEntrController
{
    /**
     * Crée une session pour l'entreprise avec les informations fournies.
     * par : formulaire_inscription_entr.php
     * @param int $idEntreprise L'identifiant de l'entreprise
     * @param string $nomEntreprise nom de l'entreprise
     * @param string $adresseEntreprise adresse de l'entreprise
     * @param string $emailEntreprise adressse email de l'entreprise
     * --
     */
    public static function creerSessionEntreprise(
        $idEntreprise,
        $nomEntreprise,
        $adresseEntreprise,
        $emailEntreprise
        //--
    ) {
        // Démarre la session si ce n'est pas encore fait
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Stocke les informations entreprise dans la session
        $_SESSION['entreprise'] = [
            'id' => $idEntreprise,
            'nom' => $nomEntreprise,
            'adresse' => $adresseEntreprise,
            'email' => $emailEntreprise
            //--
        ];
    }

    /**
     * Détruit la session entreprise en cours ou supprime uniquement la variable 'entreprise'.
     */
    public static function detruireSession()
    {
        // Démarre la session si ce n'est pas encore fait
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Supprime uniquement la variable 'entreprise'
        unset($_SESSION['entreprise']);

        // Pour détruire complètement la session, décommenter les lignes suivantes :
        session_unset();
        session_destroy();

        // Supprime également le cookie de session si présent
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), // Nom du cookie
                '',             // Valeur vide pour suppression
                time() - 42000, // Expiration passée
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
    }

    /**
     * Vérifie si une session entreprise est active.
     * 
     * @return bool true si session entreprise exite,false sinom. 
     */
    public static function verifierSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        return isset($_SESSION['entreprise']);
    }

    /**
     * Récupère les informations de l'entreprise connecté.
     *
     * @return array|null Les informations de l'entreprise ou null si non connecté.
     */
    public static function obtenirEntrepriseConnecte()
    {
        return $_SESSION['entreprise'] ?? null;
    }

    /**
     * Récupère l'ID de l'entreprise connectée.
     *
     * @return int|null L'ID de l'entreprise ou null si non connecté.
     */
    public static function getIdEntreprise()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        return $_SESSION['entreprise']['id'] ?? null;
    }

    public static function getEntrepriseId()
    {
        return $_SESSION['entreprise']['id'] ?? null;
    }

    public static function getHotelId()
    {
        return $_SESSION['id_hotel'] ?? null;
    }
}

// Gestion de l'action "deconnexion"
if (isset($_GET['action']) && $_GET['action'] === 'deconnexion') {
    // Démarre la session et supprime la variable 'entreprise'
    SessionEntrController::detruireSession();

    // Redirection après déconnexion vers la page d'accueil
    header('Location: ../index.php');
    exit();
}
