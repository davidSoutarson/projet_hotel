<?php
session_start(); // Démarre la session si ce n'est pas déjà fait
?>

<header>
    <h1>Bienvenue sur notre site de réservation d'hôtels</h1>

    <nav>
        <ul class="menu">
            <li><a href="/index.php">Accueil</a></li>

            <?php if (isset($_SESSION['utilisateur'])): ?>
                <li>Compte Utilisateur : <?= htmlspecialchars($_SESSION['utilisateur']['prenom']) . ' ' . htmlspecialchars($_SESSION['utilisateur']['nom']); ?></li>
                <li><a href="/controllers/SessionUtilController.php?action=deconnexion">Se déconnecter</a></li>
                <li><a href="/views/utilisateur/formulaire_reservation.php">Réservation</a></li>
            <?php elseif (isset($_SESSION['entreprise'])): ?>
                <li>Compte Entreprise : <?= htmlspecialchars($_SESSION['entreprise']['nom']); ?></li>
                <li><a href="/controllers/SessionEntrController.php?action=deconnexion">Se déconnecter</a></li>

                <!-- Liens spécifiques aux entreprises -->
                <li><a href="/views/entreprise/formulaire_ajouter_hotel.php">Ajouter des hôtels</a></li>
                <li><a href="/views/entreprise/formulaire_ajouter_chambre.php">Ajouter des chambres</a></li>
            <?php else: ?>
                <p>La cration d'un conte Utilisateur vous permer de resever une chambre parmie motre liste d'hotel disponible </p>
                <li><a href="/views/utilisateur/formulaire_inscription_util.php">Inscription Utilisateur</a></li>
                <li><a href="/views/utilisateur/formulaire_connexion_util.php">Connexion Utilisateur</a></li>
                <li><a href="/views/utilisateur/formulaire_reservation.php">Réservation</a></li>
                <p>La cration d'un conte Entreprise vous donne la posibiliter dajouter veau hotel </p>
                <li><a href="/views/entreprise/formulaire_inscription_entr.php">Inscription Entreprise</a></li>
                <li><a href="/views/entreprise/formulaire_connexion_entr.php">Connexion Entreprise</a></li>
            <?php endif; ?>


        </ul>
    </nav>
</header>