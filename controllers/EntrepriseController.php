<?php
require_once 'models/Entreprise.php';

class EntrepriseController
{
    private $entrepriseModel;

    public function __construct()
    {
        $this->entrepriseModel = new Entreprise();
    }

    public function inscrireEntreprise($nom, $adresse, $telephone, $email, $motDePasse)
    {
        $motDePasseHashe = password_hash($motDePasse, PASSWORD_BCRYPT);
        return $this->entrepriseModel->ajouterEntreprise($nom, $adresse, $telephone, $email, $motDePasseHashe);
    }
}
