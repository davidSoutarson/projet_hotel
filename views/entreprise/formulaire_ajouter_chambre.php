<?php
/* require_once 'E:/laragon/www/projet_hotel/config/configuration.php'; */
require_once __DIR__ . '/../../config/configuration.php';
require_once VIEWS_PATH . 'header.php';
require_once CONTROLLER_PATH . '/HotelController.php';
require_once CONTROLLER_PATH . '/SessionEntrController.php';

// Vérification de la session entreprise
if (!SessionEntrController::verifierSession()) {
    header('Location: ../entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
    exit();
}

// Récupération de l'ID de l'entreprise depuis la session
$id_entreprise = SessionEntrController::getEntrepriseId();

// Récupération de la liste des hôtels
$hotelController = new HotelController();
$hotels = $hotelController->obtenirHotelsParEntreprise($id_entreprise);

// Récupération de l'ID de l'hôtel (POST ou GET)
$id_hotel = $_POST['id_hotel'] ?? $_GET['hotel'] ?? 0;
$id_hotel = (int) $id_hotel;

$nombre_de_chambres = 0;
if ($id_hotel > 0) {
    // Vérifier si l'hôtel sélectionné appartient bien à l'entreprise
    $hotel_ids = array_column($hotels, 'id');
    if (in_array($id_hotel, $hotel_ids)) {
        $nombre_de_chambres = $hotelController->obtenirNombreDeChambres($id_hotel);
    } else {
        $id_hotel = 0; // Réinitialiser si non valide
    }
}
?>

<h2>Ajouter des Chambres</h2>

<!-- Étape 1 : Sélection de l'hôtel -->
<form action="#" method="POST">
    <label for="hotel">Sélectionner l'hôtel :</label>
    <select id="hotel" name="id_hotel" required onchange="this.form.submit()">
        <option value="">-- Choisir un hôtel --</option>
        <?php foreach ($hotels as $hotel) : ?>
            <option value="<?= htmlspecialchars($hotel['id']) ?>" <?= ($id_hotel == $hotel['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($hotel['hotel_nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <noscript><button type="submit">Sélectionner</button></noscript>
</form>

<?php if ($id_hotel > 0) : ?>

    <!-- Formulaire pour sélectionner l'intervalle -->
    <form action="#" method="POST">
        <input type="hidden" name="id_hotel" value="<?= htmlspecialchars($id_hotel) ?>">
        <label for="interval">Nombre de chambres par étage :</label>
        <select name="interval" id="interval">
            <option value="3" <?= ($_POST['interval'] ?? 5) == 3 ? 'selected' : ''; ?>>3 chambres</option>
            <option value="5" <?= ($_POST['interval'] ?? 5) == 5 ? 'selected' : ''; ?>>5 chambres</option>
            <option value="10" <?= ($_POST['interval'] ?? 5) == 10 ? 'selected' : ''; ?>>10 chambres</option>
            <option value="10" <?= ($_POST['interval'] ?? 5) == 15 ? 'selected' : ''; ?>>15 chambres</option>
        </select>
        <button type="submit">Valider</button>
    </form>

    <?php
    $interval = $_POST['interval'] ?? 5; // Valeur par défaut : 5 chambres par étage
    $interval = (int) $interval;

    // Vérifier que l'hôtel existe dans la liste avant d'afficher son nom
    $hotel_nom = '';
    foreach ($hotels as $hotel) {
        if ($hotel['id'] == $id_hotel) {
            $hotel_nom = $hotel['hotel_nom'];
            break;
        }
    }
    ?>

    <form class="mTop-5" action="../../controllers/ChambreController.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="ajouter_chambre">
        <input type="hidden" name="id_hotel" value="<?= htmlspecialchars($id_hotel) ?>">

        <?php for ($i = 1; $i <= $nombre_de_chambres; $i++) : ?>
            <fieldset class="form-boxe">
                <h3>Hôtel : <?= htmlspecialchars($hotel_nom) ?></h3>
                <legend>Chambre <?= $i ?></legend>

                <div class="Ligne1">
                    <?php
                    // Calcul de l'étage en fonction de l'intervalle choisi
                    $floor = ceil($i / $interval);
                    $roomNumber = sprintf("%02d", $i);
                    $value = $floor . " " . $roomNumber;
                    ?>
                    <label for="numero_<?= $i ?>">Numéro :</label>
                    <input class="Ts" type="text" id="numero_<?= $i ?>" value="<?= $value ?>" name="numero[]" required>

                    <label for="prix_<?= $i ?>">Prix :</label>
                    <input class="Ts" type="number" id="prix_<?= $i ?>" name="prix[]" required>

                    <label for="nombre_lits_<?= $i ?>">Nombre de lits :</label>
                    <input class="Ts" type="number" id="nombre_lits_<?= $i ?>" value="2" name="nombre_lits[]" required>
                </div>

                <label for="description_chambre_<?= $i ?>">Description :</label>
                <textarea id="description_chambre_<?= $i ?>" name="description_chambre[]"></textarea><br>

                <div class="Ligne3">
                    <div class="col-1">
                        <label for="photo_chambre_<?= $i ?>">Photo :</label>
                        <input type="file" id="photo_chambre_<?= $i ?>" name="photo_chambre[]" accept="image/*"><br>
                    </div>
                    <div class="col-2">
                        <label for="etat_<?= $i ?>">État :</label>
                        <select id="etat_<?= $i ?>" name="etat[]">
                            <option value="libre">Libre</option>
                            <option value="reserve">Réservé</option>
                        </select>
                    </div>
                </div>
            </fieldset>
            <br>
        <?php endfor; ?>

        <button class="btn ajouter" type="submit">Ajouter Chambres</button>
    </form>

<?php else : ?>
    <p>Aucun hôtel sélectionné ou non valide.</p>
<?php endif; ?>

<?php require_once VIEWS_PATH . 'footer.php'; ?>