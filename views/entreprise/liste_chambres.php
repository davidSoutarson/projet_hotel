<?php
require_once __DIR__ . '/../../models/Chambre.php';
require_once __DIR__ . '/../../models/Hotel.php';
require_once __DIR__ . '/../../controllers/SessionEntrController.php';  // Ajouter le contrôleur pour vérifier la session entreprise

// Vérifier si l'entreprise est connectée
if (!SessionEntrController::verifierSession()) {
    header('Location: ../entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
    exit();
}

// Récupérer l'ID de l'entreprise depuis la session
$id_entreprise = SessionEntrController::getEntrepriseId();

// Récupérer l'ID de l'hôtel depuis l'URL
$hotelId = $_GET['hotel'] ?? null;

// Vérifier si l'ID de l'hôtel est spécifié et si cet hôtel appartient à l'entreprise
if (!$hotelId) {
    die("ID d'hôtel non spécifié.");
}

// Instancier les modèles
$chambreModel = new Chambre();
$hotelModel = new Hotel();

// Vérifier si l'hôtel appartient à l'entreprise connectée
$hotel = $hotelModel->obtenirHotelParId($hotelId);

if (!$hotel || $hotel['id_entreprise'] !== $id_entreprise) {
    die("Cet hôtel ne vous appartient pas ou il n'existe pas.");
}

// Obtenir les chambres pour cet hôtel
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