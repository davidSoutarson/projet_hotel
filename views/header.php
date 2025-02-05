<?php
session_start(); // Démarre la session si ce n'est pas déjà fait
?>
<header>
    <h1>Bienvenue sur notre site de réservation d'hôtels</h1>
    <nav>
        <p>menu consernent les ulilisateurs</p>
        <ul class="menu">
            <li><a href="/index.php">Accueil</a></li>
            <li><a href="/views/utilisateur/formulaire_inscription_util.php">Inscription Utilisateur</a></li>
            <li><a href="/views/utilisateur/formulaire_connexion_util.php">Connexion Utilisateur</a></li>
            <li><a href="/views/utilisateur/formulaire_reservation.php">Reservation</a></li>

        </ul>

        <p>menu consernent les entreprise</p>
        <ul>
            <li><a href="/views/entreprise/formulaire_inscription_entr.php">Inscription Entreprise</a></li>
            <li><a href="/views/entreprise/formulaire_connexion_entr.php">Connexion Entreprise</a></li>
            <li><a href="/views/entreprise/formulaire_ajouter_hotel.php">Ajouter des hotel</a></li>
            <li><a href="/views/entreprise/formulaire_ajouter_chambre.php">Ajouter des chambre</a></li>
        </ul>
    </nav>
    <div>
        <h2> Etat de la connexion </h2>
        <?php

        if (isset($_SESSION['utilisateur'])) {
            echo '<p>Compte Utilisateur : ' . htmlspecialchars($_SESSION['utilisateur']['prenom'], ENT_QUOTES, 'UTF-8')
                . ' ' . htmlspecialchars($_SESSION['utilisateur']['nom'], ENT_QUOTES, 'UTF-8') . '</p>';
            echo '<button><a href="/controllers/SessionUtilController.php?action=deconnexion"> déconnexion utilisateur </a></button>';
        } elseif (isset($_SESSION['entreprise'])) {
            echo '<p>Compte Entreprise : ' . htmlspecialchars($_SESSION['entreprise']['nom'], ENT_QUOTES, 'UTF-8')
                . ' ' . htmlspecialchars($_SESSION['entreprise']['nom'], ENT_QUOTES, 'UTF-8') . '</p>';
            echo '<button><a href="/controllers/SessionEntrController.php?action=deconnexion"> déconnexion entreprise</a></button>';
        } else {
            // Aucun compte n'est connecté
            echo '<p>Vous n\'êtes pas connecté.</p>';
        }


        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . '</div>';
            unset($_SESSION['success']);
        }
        ?>
    </div>

</header>