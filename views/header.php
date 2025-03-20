<?php
session_start();
require_once __DIR__ . '/../config/configuration.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo CSS_PATH . 'style.css'; ?>">
    <title>Mon Site Hôtel</title>
</head>

<body>
    <div id=wrape>
        <header>
            <h1>Bienvenue sur notre site de réservation d'hôtels</h1>
            <div class="conteneur-menus-info">

                <div class="conteneur-menus-">
                    <nav class="nav_entreprise">
                        <p class="tire_menue">Menu concernant les entreprises</p>
                        <ul class="menu">
                            <li><a href="<?php echo VIEWS_LIEN . 'entreprise/formulaire_inscription_entr.php'; ?>">Inscription Entreprise</a></li>
                            <li><a href="<?php echo VIEWS_LIEN . 'entreprise/formulaire_connexion_entr.php'; ?>">Connexion Entreprise</a></li>
                            <li><a href="<?php echo VIEWS_LIEN . 'entreprise/formulaire_ajouter_hotel.php'; ?>">Ajouter un hôtel</a></li>
                            <li><a href="<?php echo VIEWS_LIEN . 'entreprise/formulaire_ajouter_chambre.php'; ?>">Ajouter une chambre</a></li>
                            <li><a href="<?php echo VIEWS_LIEN . 'entreprise/liste_chambres.php'; ?>">liste chambre</a></li>
                        </ul>
                    </nav>

                    <nav class="nav_utilisateur">
                        <p class="tire_menue">Menu concernant les utilisateurs</p>
                        <ul class="menu">
                            <li><a href="<?php echo BASE_URL . 'index.php'; ?>">Accueil</a></li>
                            <li><a href="<?php echo VIEWS_LIEN . 'utilisateur/formulaire_inscription_util.php'; ?>">Inscription Utilisateur</a></li>
                            <li><a href="<?php echo VIEWS_LIEN . 'utilisateur/formulaire_connexion_util.php'; ?>">Connexion Utilisateur</a></li>
                            <li><a href="<?php echo VIEWS_LIEN . 'utilisateur/formulaire_reservation.php'; ?>">Réservation</a></li>
                            <li><a href="<?php echo VIEWS_LIEN . 'utilisateur/confirmation_reservation.php'; ?>">Vautre Réservation</a></li>
                        </ul>
                    </nav>
                </div>

                <div class="info-connexion">
                    <h2>État de la connexion</h2>
                    <?php
                    if (isset($_SESSION['utilisateur'])) {
                        echo '<p>Compte Utilisateur : <span class="valider">' . htmlspecialchars($_SESSION['utilisateur']['prenom'], ENT_QUOTES, 'UTF-8')
                            . ' ' . htmlspecialchars($_SESSION['utilisateur']['nom'], ENT_QUOTES, 'UTF-8') . '</span></p>';
                        echo '<button class="btn exit"><a class="blanc" href="' . BASE_URL . 'controllers/SessionUtilController.php?action=deconnexion">Déconnexion utilisateur</a></button>';
                    } elseif (isset($_SESSION['entreprise'])) {
                        echo '<p>Compte Entreprise : ' . htmlspecialchars($_SESSION['entreprise']['nom'], ENT_QUOTES, 'UTF-8') . '</p>';
                        echo '<button class="btn exit"> <a class="blanc" href="' . BASE_URL . 'controllers/SessionEntrController.php?action=deconnexion">Déconnexion entreprise</a></button>';
                    } else {
                        echo '<p>Vous n\'êtes pas connecté.</p>';
                    }

                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . '</div>';
                        unset($_SESSION['success']);
                    }
                    ?>
                </div>
            </div>
        </header>
</body>