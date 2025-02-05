<?php
session_start(); // Démarre la session si ce n'est pas déjà fait
?>
<header>
    <h1>Bienvenue sur notre site de réservation d'hôtels</h1>
    <nav>
        <ul class="menu">
            <p>menu consernent les ulilisateurs</p>
            <li><a href="/index.php">Accueil</a></li>
            <li><a href="/views/utilisateur/formulaire_inscription_util.php">Inscription Utilisateur</a></li>
            <li><a href="/views/utilisateur/formulaire_connexion_util.php">Connexion Utilisateur</a></li>
            <li><a href="/views/utilisateur/formulaire_reservation.php">Reservation</a></li>

        </ul>
        <ul>
            <p>menu consernent les entreprise</p>
            <li><a href="/views/entreprise/formulaire_inscription_entr.php">Inscription Entreprise</a></li>
            <li><a href="/views/entreprise/formulaire_connexion_entr.php">Connexion Entreprise</a></li>
            <li><a href="/views/entreprise/formulaire_ajouter_hotel.php">Ajouter des hotel</a></li>
            <li><a href="/views/entreprise/formulaire_ajouter_chambre.php">Ajouter des chambre</a></li>
        </ul>
    </nav>
    <div>
        <h2> info etat conextion </h2>

        <?php if (isset($_SESSION['utilisateur'])): ?>
            <p>Compte Utilisateur : <?= htmlspecialchars($_SESSION['utilisateur']['prenom']) . ' ' . htmlspecialchars($_SESSION['utilisateur']['nom']); ?></p>
            <a href="/controllers/SessionUtilController.php?action=deconnexion" class="btn-deconnexion">Se déconnecter</a>

        <?php elseif (isset($_SESSION['entreprise'])): ?>
            <p>Compte Entreprise : <?= htmlspecialchars($_SESSION['entreprise']['nom']) . ' ' . htmlspecialchars($_SESSION['entreprise']['nom']); ?></p>
            <button><a href="/controllers/SessionEntrController.php?action=deconnexion">Se déconnecter entreprise</a></button>
        <?php endif; ?>
    </div>

</header>