<?php require_once '../header.php'; ?>

<h2>Connexion Entreprise</h2>
<form action="../../controllers/EntrepriseController.php" method="POST">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required><br>

    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

    <button type="submit">Se connecter</button>
</form>

<?php require_once '../footer.php'; ?>