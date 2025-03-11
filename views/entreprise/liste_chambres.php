<?php
require_once __DIR__ . '/../../config/configuration.php';
require_once VIEWS_PATH . 'header.php';
require_once MODEL_PATH . 'Chambre.php';
require_once MODEL_PATH . 'Hotel.php';
require_once CONTROLLER_PATH . 'SessionEntrController.php';  // Vérification de la session entreprise

// Vérifier si l'entreprise est connectée
if (!SessionEntrController::verifierSession()) {
    header('Location: ../entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
    exit();
}

// Récupérer l'ID de l'entreprise depuis la session
$id_entreprise = SessionEntrController::getEntrepriseId();

// Instancier le modèle Hotel (pour récupérer la liste des hôtels)
$hotelModel = new Hotel();

// Récupérer l'ID de l'hôtel depuis l'URL
$hotelId = $_GET['hotel'] ?? null;

// Fonction de sélection de l'hôtel désiré si aucun n'est spécifié
if (!$hotelId) {
    // Supposons que la méthode obtenirHotelsParEntreprise retourne tous les hôtels de l'entreprise
    $hotels = $hotelModel->obtenirHotelsParEntreprise($id_entreprise);

    if (empty($hotels)) {
        die("<p>Aucun hôtel disponible pour votre entreprise.</p>");
    }
?>
    <h2>Sélectionnez l'hôtel désiré</h2>
    <form method="get" action="">
        <label for="hotel">Hôtel :</label>
        <select name="hotel" id="hotel">
            <?php foreach ($hotels as $hotel): ?>
                <option value="<?= htmlspecialchars($hotel['id']) ?>">
                    <?= htmlspecialchars($hotel['hotel_nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Valider</button>
    </form>
<?php
    exit();
}

// Une fois l'hôtel sélectionné, on peut poursuivre le traitement
$chambreModel = new Chambre();

// Vérifier si l'hôtel appartient à l'entreprise connectée
$hotel = $hotelModel->obtenirHotelParId($hotelId);
if (!$hotel || $hotel['id_entreprise'] !== $id_entreprise) {
    die("<p>Cet hôtel ne vous appartient pas ou il n'existe pas.</p>");
}

// Obtenir les chambres pour cet hôtel
$chambres = $chambreModel->obtenirChambresParHotel($hotelId);

/* var_dump($chambres); */

?>



<?php require_once '../header.php'; ?>

<h2>Liste des chambres de l'hôtel : <?= htmlspecialchars($hotel['hotel_nom'] ?? 'Inconnu') ?></h2>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Chambre(s) ajoutée(s) avec succès !</p>
<?php endif; ?>

<table border="1">
    <tr>
        <th>Numéro</th>
        <th>Prix</th>
        <th>Nombre de lits</th>
        <th>Description</th>
        <th>Photo</th>
        <th>État</th>
        <th>Modifier</th>
    </tr>
    <?php foreach ($chambres as $chambre): ?>
        <tr>
            <td><?= htmlspecialchars($chambre['numero']) ?></td>
            <td><?= htmlspecialchars($chambre['prix']) ?> €</td>
            <td><?= htmlspecialchars($chambre['nombre_lits']) ?></td>
            <td><?= htmlspecialchars($chambre['description_chambre']) ?></td>
            <td>
                <?php if (!empty($chambre['photo_chambre'])): ?>
                    <img src="../../<?= htmlspecialchars($chambre['photo_chambre']) ?>" width="100">
                <?php else: ?>
                    Aucune image
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($chambre['etat']) ?></td>
            <td>
                <a class="btn" href="#">modifier</a>
                <a class="btn" href="#">supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<a class="btn ajouter" href="formulaire_ajouter_chambre.php?hotel=<?= htmlspecialchars($hotelId) ?>">Ajouter des chambres</a>

<?php require_once VIEWS_PATH . 'footer.php'; ?>