<?php
/* require_once 'E:\laragon\www\projet_hotel\config\configuration.php'; */
require_once __DIR__ . '/../../config/configuration.php';
require_once VIEWS_PATH . 'header.php';
?>

<h2>Inscription Entreprise</h2>
<form action="../../controllers/EntrepriseController.php" method="POST">
    <label for="nom">Nom de l'entreprise :</label>
    <input type="text" id="nom" name="nom" required><br>

    <label for="adresse">Adresse :</label>
    <input type="text" id="adresse" name="adresse" required><br>

    <label for="telephone">Téléphone :</label>
    <input type="text" id="telephone" name="telephone"><br>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required><br>

    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

    <button class="btn" type="submit">S'inscrire</button>
</form>

<?php require_once VIEWS_PATH . 'footer.php'; ?>