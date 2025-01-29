<?php
require_once 'models/Utilisateur.php';

class UtilisateurController
{
    private $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
    }

    public function inscrireUtilisateur($nom, $prenom, $telephone, $email, $motDePasse)
    {
        $motDePasseHashe = password_hash($motDePasse, PASSWORD_BCRYPT);
        return $this->utilisateurModel->ajouterUtilisateur($nom, $prenom, $telephone, $email, $motDePasseHashe);
    }
}
