<?php
/* 1. Inclusion des fichiers nécessaires */
require_once __DIR__ . '/../../config/configuration.php';
require_once VIEWS_PATH . 'header.php';
require_once CONTROLLER_PATH . '/HotelController.php';
require_once MODEL_PATH . '/Chambre.php';
require_once CONTROLLER_PATH . '/SessionEntrController.php';

/* 2. Vérification de la session */
if (!SessionEntrController::verifierSession()) {
    header('Location: ../entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
    exit();
}


/* 3. Récupération de l'ID de l'entreprise et des hôtels  */
$id_entreprise = SessionEntrController::getEntrepriseId();

$hotelController = new HotelController();
$hotels = $hotelController->obtenirHotelsParEntreprise($id_entreprise);

/* 4. Récupération et validation de l'hôtel sélectionné */
$id_hotel = $_POST['id_hotel'] ?? $_GET['hotel'] ?? 0;
$id_hotel = (int)$id_hotel;

$nombre_de_chambres = 0;
if ($id_hotel > 0) {
    $hotel_ids = array_column($hotels, 'id');
    if (in_array($id_hotel, $hotel_ids)) {
        $nombre_de_chambres = $hotelController->obtenirNombreDeChambres($id_hotel);
    } else {
        $id_hotel = 0;
    }
}
?>

<!-- 5. Formulaire de sélection d'hôtel  -->
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

    <?php
    $hotel_nom = array_column($hotels, 'hotel_nom', 'id')[$id_hotel] ?? '';

    /* 6. Affichage des informations sur les chambres */
    $chambre = new Chambre();
    $chambres_deja_ajoutees = $chambre->getNombreChambres($id_hotel);
    $dernier_numero = $chambre->getDernierNumeroChambre($id_hotel) ?? 0;

    $chambres_a_ajouter = $nombre_de_chambres - $chambres_deja_ajoutees;

    echo "<p> Nombre maximal de chambres autorisé : $nombre_de_chambres </p>";
    echo "<p> Nombre de chambres déjà ajoutées : $chambres_deja_ajoutees </p>";
    echo "<p> Nombre de chambres pouvant être ajoutées : $chambres_a_ajouter </p>";
    echo "<p> Dernier numéro de chambre : $dernier_numero </p>";
    ?>

    <?php if ($chambres_a_ajouter > 0) : ?>
        <!-- 7. Sélection du nombre de lignes -->
        <form action="#" method="POST">
            <input type="hidden" name="id_hotel" value="<?= htmlspecialchars($id_hotel) ?>">

            <label for="groupe">Nombre de lignes :</label>
            <select name="groupe" id="groupe" onchange="this.form.submit()">
                <?php for ($i = 1; $i <= min($chambres_a_ajouter, 10); $i++) : ?>
                    <option value="<?= $i ?>" <?= isset($_POST['groupe']) && $_POST['groupe'] == $i ? 'selected' : '' ?>>
                        <?= $i ?> ligne<?= $i > 1 ? 's' : '' ?>
                    </option>
                <?php endfor; ?>
            </select>
            <button type="submit">Valider</button>
        </form>

        <?php
        $groupe = $_POST['groupe'] ?? min($chambres_a_ajouter, 10);
        $groupe = (int)$groupe;
        ?>

        <!-- 8. Formulaire principal d'ajout des chambres -->

        <form class="mTop-5" action="../../controllers/ChambreController.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="action" value="ajouter_chambre">
            <input type="hidden" name="id_hotel" value="<?= htmlspecialchars($id_hotel) ?>">

            <h2>Ajouter des Chambres</h2>

            <?php for ($i = 1; $i <= $groupe; $i++) : ?>
                <?php
                $numero_chambre = $dernier_numero + $i;
                $floor = floor($numero_chambre / 100);
                $roomNumber = sprintf("%02d", $numero_chambre % 100);
                $value = $floor . $roomNumber;
                ?>


                <fieldset class="form-boxe">
                    <h3>Hôtel : <?= htmlspecialchars($hotel_nom) ?></h3>
                    <legend>Chambre <?= $numero_chambre ?></legend>

                    <div class="Ligne1">
                        <!-- champ nemero[] -->
                        <label for="numero_<?= $numero_chambre ?>">Numéro :</label>
                        <input class="Ts" type="text" id="numero_<?= $numero_chambre ?>" value="<?= $value ?>" name="numero[]" required>

                        <!-- champ prix[] -->
                        <label for="prix_<?= $numero_chambre ?>">Prix :</label>
                        <input class="Ts" type="number" id="prix_<?= $numero_chambre ?>" name="prix[]" step="0.01" required>

                        <label for="nombre_lits_<?= $numero_chambre ?>">Nombre de lits :</label>
                        <input class="Ts2" type="number" id="nombre_lits_<?= $numero_chambre ?>" value="2" name="nombre_lits[]" required>

                        <label for="etat_<?= $numero_chambre ?>">État :</label>
                        <select class="Ts" id="etat_<?= $numero_chambre ?>" name="etat[]" required>
                            <option value="libre">libre</option>
                            <option value="reserve">reserver</option>
                        </select>

                    </div>

                    <label for="photo_chambre<?= $numero_chambre ?>">Photo :</label>
                    <input type="file" id="photo_chambre<?= $numero_chambre ?>" name="photo_chambre[]" accept="image/*"><br>

                    <label for="description_chambre_<?= $numero_chambre ?>">Description :</label>
                    <textarea id="description_chambre_<?= $numero_chambre ?>" name="description_chambre[]"></textarea><br>
                </fieldset>
                <br>
            <?php endfor; ?>

            <button class="btn ajouter" type="submit">Ajouter Chambres</button>
        </form>


    <?php else : ?>
        <!--  9. Gestion des cas particuliers -->
        <p>Le nombre maximal de chambres pour l'hôtel <strong><?= htmlspecialchars($hotel_nom) ?></strong> a été atteint.</p>
    <?php endif; ?>
<?php else : ?>
    <!--  9. Gestion des cas particuliers -->
    <p>Aucun hôtel sélectionné ou non valide.</p>
<?php endif; ?>

<!-- 10. Inclusion du pied de page -->
<?php require_once VIEWS_PATH . 'footer.php'; ?>