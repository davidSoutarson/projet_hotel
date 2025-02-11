<?php require_once '../header.php'; ?>

<p> Description pour le devoir ce fichier permet d'ajouter un hotel </p>

<h2>Ajouter un Hôtel</h2>
<form action="../../controllers/HotelController.php" method="POST" enctype="multipart/form-data">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required><br>

    <label for="adresse">Adresse :</label>
    <input type="text" id="adresse" name="adresse" required><br>

    <label for="telephone">Téléphone :</label>
    <input type="text" id="telephone" name="telephone" required><br>

    <label for="description">Description :</label>
    <textarea id="description" name="description"></textarea><br>

    <label for="photo">Photo :</label>
    <input type="file" id="photo" name="photo" accept="image/*"><br>

    <button type="submit">Ajouter Hôtel</button>
</form>

<?php require_once '../footer.php'; ?>