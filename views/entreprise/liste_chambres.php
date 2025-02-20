<?php
require_once __DIR__ . '/../../models/Chambre.php';
require_once __DIR__ . '/../../models/Hotel.php';

$hotelId = $_GET['hotel'] ?? null;

if (!$hotelId) {
    die("ID d'hôtel non spécifié.");
}

$chambreModel = new Chambre();
$hotelModel = new Hotel();

$hotel = $hotelModel->obtenirHotelParId($hotelId);
$chambres = $chambreModel->obtenirChambresParHotel($hotelId);

?>

<?php require_once '../header.php'; ?>

<h2>Liste des chambres de l'hôtel : <?= htmlspecialchars($hotel['nom'] ?? 'Inconnu') ?></h2>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;"> Chambre ajoutée avec succès !</p>
<?php endif; ?>

<table border="1">
    <tr>
        <th>Numéro</th>
        <th>Prix</th>
        <th>Nombre de lits</th>
        <th>Description</th>
        <th>Photo</th>
        <th>État</th>
    </tr>
    <?php foreach ($chambres as $chambre): ?>
        <tr>
            <td><?= htmlspecialchars($chambre['numero']) ?></td>
            <td><?= htmlspecialchars($chambre['prix']) ?> €</td>
            <td><?= htmlspecialchars($chambre['nombre_lits']) ?></td>
            <td><?= htmlspecialchars($chambre['description']) ?></td>
            <td>
                <?php if (!empty($chambre['photo'])): ?>
                    <img src="../<?= htmlspecialchars($chambre['photo']) ?>" width="100">
                <?php else: ?>
                    Aucune image
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($chambre['etat']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="formulaire_ajouter_chambre.php?hotel=<?= htmlspecialchars($hotelId) ?>"> Ajouter une chambre</a>

<?php require_once '../footer.php'; ?>