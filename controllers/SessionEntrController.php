<?php
session_start();

class SessionEntrController
{
    /**
     * Crée une session pour l'utilisateur avec les informations fournies.
     * par : formulaire_inscription_entr.php
     */
    public static function creerSessionEntreprise(
        $idEntreprise,
        $nomEntreprise,
        $adresseEntreprise,
        $emailEntreprise
    ) {
        // Démarre la session si ce n'est pas encore fait
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Stocke les informations utilisateur dans la session
        $_SESSION['entreprise'] = [
            'id' => $idEntreprise,
            'nom' => $nomEntreprise,
            'adresse' => $adresseEntreprise,
            'email' => $emailEntreprise
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
        return isset($_SESSION['entreprise']);
    }
}


# verifier le bon fontionenment de ma setion enreprise