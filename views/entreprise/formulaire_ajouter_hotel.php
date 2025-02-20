<?php require_once '../header.php';
require_once '../../controllers/SessionEntrController.php';

// Vérifier si l'entreprise est connectée
if (!SessionEntrController::verifierSession()) {
    header('Location: ../entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
    exit();
}

?>

<p> Description pour le devoir : ce fichier permet d'ajouter un hôtel </p>

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

    <label for="nombre_de_chambres">Nombre de chambre:</label>
    <input type="number" id="nombre_chambres" name="nombre_chambres"><br>

    <label for=" photo">Photo :</label>
    <input type="file" id="photo" name="photo" accept="image/*"><br>

    <!-- Ajout du champ caché pour éviter l'erreur -->
    <input type="hidden" name="action" value="ajouter_hotel">

    <button type="submit">Ajouter Hôtel</button>
</form>

<?php require_once '../footer.php'; ?>