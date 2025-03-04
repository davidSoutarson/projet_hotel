<?php
require_once 'E:\laragon\www\projet_hotel\config\configuration.php';
require_once VIEWS_PATH . 'header.php';
require_once CONTROLLER_PATH . '\HotelController.php';
require_once CONTROLLER_PATH . '\SessionEntrController.php';

// Vérification de la session entreprise
if (!SessionEntrController::verifierSession()) {
    header('Location: ../entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
    exit();
}

// Récupérer l'ID de l'entreprise depuis la session
$id_entreprise = SessionEntrController::getEntrepriseId();

// Récupérer la liste des hôtels de l'entreprise
$hotelController = new HotelController();
$hotels = $hotelController->obtenirHotelsParEntreprise($id_entreprise);

// Récupérer l'ID de l'hôtel depuis POST ou GET (pour compatibilité avec le lien du fichier 4)
$id_hotel = 0;
if (isset($_POST['id_hotel']) && !empty($_POST['id_hotel'])) {
    $id_hotel = (int) $_POST['id_hotel'];
} elseif (isset($_GET['hotel']) && !empty($_GET['hotel'])) {
    $id_hotel = (int) $_GET['hotel'];
}

$nombre_de_chambres = 0;
echo "id_hotel=" . $id_hotel . "<br>";

if ($id_hotel > 0) {
    // Récupérer le nombre de chambres pour cet hôtel
    $nombre_de_chambres = $hotelController->obtenirNombreDeChambres($id_hotel);
    echo "nombre_de_chambres = " . $nombre_de_chambres . "<br>";
}
?>

<h2>Ajouter des Chambres</h2>

<!-- Étape 1 : Sélection de l'hôtel -->

<form action="formulaire_ajouter_chambre.php" method="POST">
    <label for="hotel">Sélectionner l'hôtel :</label>
    <select id="hotel" name="id_hotel" required onchange="this.form.submit()">
        <option value="">-- Choisir un hôtel --</option>
        <?php foreach ($hotels as $hotel) : ?>
            <option value="<?= htmlspecialchars($hotel['id']) ?>" <?= ($id_hotel == $hotel['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($hotel['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <noscript><button type="submit">Sélectionner</button></noscript>
</form>

<!-- Étape 2 : Affichage du formulaire si un hôtel est sélectionné -->
<?php if ($id_hotel > 0) : ?>

    <form class="mTop-5" action="../../controllers/ChambreController.php" method="POST" enctype="multipart/form-data">
        <!-- Champ caché pour l'action -->
        <input type="hidden" name="action" value="ajouter_chambre">
        <input type="hidden" name="id_hotel" value="<?= htmlspecialchars($id_hotel) ?>">

        <?php
        // Ici, on détermine combien de chambres peuvent être ajoutées.
        // Selon la logique, vous pouvez fixer un nombre maximum ou utiliser le nombre existant.
        // je vais a ficher les champd de formulair  auten de fois qu' il y a de chambre dans l'hotel
        $nbChambresAAjouter = $nombre_de_chambres;
        for ($i = 1; $i <= $nbChambresAAjouter; $i++) :
        ?>
            <fieldset class="form-boxe">
                <legend>Chambre <?= $i ?></legend>

                <div class="Ligne1">

                    <label for=" numero_<?= $i ?>">Numéro :</label>
                    <input class="Ts" type="text" id="numero_<?= $i ?>" name="numero[]" required>

                    <label for="prix_<?= $i ?>">Prix :</label>
                    <input class="Ts" type="number" id="prix_<?= $i ?>" name="prix[]" required>

                    <label for="nombre_lits_<?= $i ?>">Nombre de lits :</label>
                    <input class="Ts" type="number" id="nombre_lits_<?= $i ?>" name="nombre_lits[]" required>

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
<?php elseif ($id_hotel > 0) : ?>
    <p>Cet hôtel ne permet pas d'ajouter de nouvelles chambres.</p>
<?php endif; ?>

<?php require_once VIEWS_PATH . 'footer.php'; ?>