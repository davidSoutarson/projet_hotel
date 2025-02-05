<?php
session_start();

class SessionEntrController
{
    public static function creerSessionEntreprise($idEntreprise, $nomEntreprise, $emailEntreprise)
    {
        $_SESSION['entreprise'] = [
            'id' => $idEntreprise,
            'nom' => $nomEntreprise,
            'email' => $emailEntreprise
        ];
    }

    public static function detruireSession()
    {
        session_unset();
        session_destroy();
    }

    public static function verifierSession()
    {
        return isset($_SESSION['entreprise']);
    }
}
