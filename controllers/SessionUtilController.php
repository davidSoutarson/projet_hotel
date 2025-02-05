<?php
class SessionUtilController
{
    /**
     * Crée une session pour l'utilisateur avec les informations fournies.
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
     * Détruit la session utilisateur.
     */
    public static function detruireSession()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }

    /**
     * Vérifie si une session utilisateur est active.
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
     */
    public static function obtenirUtilisateurConnecte()
    {
        return $_SESSION['utilisateur'] ?? null;
    }
}
