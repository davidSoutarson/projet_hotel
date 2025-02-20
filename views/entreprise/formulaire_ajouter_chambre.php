<?php
require_once '../header.php';
require_once '../../controllers/HotelController.php';
require_once '../../controllers/SessionEntrController.php';

// Vérification de la session entreprise
if (!SessionEntrController::verifierSession()) {
    header('Location: ../entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
    exit();
}

// Récupérer l'ID de l'hôtel depuis l'URL ou la session
$id_hotel = isset($_GET['hotel']) ? (int)$_GET['hotel'] : (SessionEntrController::getHotelId() ?? 0);

if ($id_hotel > 0) {
    // Récupérer le nombre de chambres de l'hôtel
    $hotelController = new HotelController();
    $nombre_de_chambres = $hotelController->obtenirNombreDeChambres($id_hotel);
} else {
    die("ID d'hôtel non spécifié ou invalide.");
}
?>

<h2>Ajouter des Chambres</h2>

<form action="../../controllers/ChambreController.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_hotel" value="<?= htmlspecialchars($id_hotel) ?>">

    <?php for ($i = 1; $i <= $nombre_de_chambres; $i++) : ?>
        <fieldset>
            <legend>Chambre <?= $i ?></legend>

            <label for="numero_<?= $i ?>">Numéro :</label>
            <input type="text" id="numero_<?= $i ?>" name="numero[]" required><br>

            <label for="prix_<?= $i ?>">Prix :</label>
            <input type="number" id="prix_<?= $i ?>" name="prix[]" required><br>

            <label for="nombre_lits_<?= $i ?>">Nombre de lits :</label>
            <input type="number" id="nombre_lits_<?= $i ?>" name="nombre_lits[]" required><br>

            <label for="description_chambre_<?= $i ?>">Description :</label>
            <textarea id="description_chambre_<?= $i ?>" name="description_chambre[]"></textarea><br>

            <label for="photo_chambre_<?= $i ?>">Photo :</label>
            <input type="file" id="photo_chambre_<?= $i ?>" name="photo_chambre[]" accept="image/*"><br>

            <label for="etat_<?= $i ?>">État :</label>
            <select id="etat_<?= $i ?>" name="etat[]">
                <option value="libre">Libre</option>
                <option value="reserve">Réservé</option>
            </select><br>
        </fieldset>
        <br>
    <?php endfor; ?>

    <button type="submit">Ajouter Chambres</button>
</form>

<?php require_once '../footer.php'; ?>