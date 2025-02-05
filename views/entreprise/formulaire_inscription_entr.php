<?php require_once '../header.php'; ?>

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

    <button type="submit">S'inscrire</button>
</form>

<?php require_once '../footer.php'; ?>