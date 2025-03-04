<?php
require_once 'E:\laragon\www\projet_hotel\config\configuration.php';
require_once VIEWS_PATH . 'header.php';
?>

<h2>Inscription Utilisateur</h2>
<form action="../../controllers/UtilisateurController.php" method="POST">

    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" placeholder="Ecriver vrotre nom ici" required><br>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" placeholder="Ecriver vrotre prénom ici" required><br>

    <label for="adresse">Adresse :</label>
    <input type="text" id="adresse" name="adresse" placeholder="Ecriver vrotre adrésse ici" required><br>

    <label for="telephone">Téléphone :</label>
    <input type="text" id="telephone" name="telephone" placeholder="Ecriver vrotre numero de téléphone sur 10 chifre ici"><br>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" placeholder="Ecriver vrotre adresse Email ici" required><br>

    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Ecriver vrotre mots de passe ici" required><br>

    <button class="btn" type="submit">S'inscrire</button>
</form>

<?php require_once VIEWS_PATH . 'footer.php'; ?>