<?php require_once '../header.php'; ?>

<h2>Connexion Utilisateur</h2>

<p>Description pour le devoir ce fichier permet de vérifier si l'utilisateur possède un conte</p>

<form action="../../controllers/UtilisateurController.php" method="POST">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required><br>

    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

    <button type="submit">Se connecter</button>
</form>

<?php require_once '../footer.php'; ?>