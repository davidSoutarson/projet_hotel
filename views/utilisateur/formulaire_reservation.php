<?php
// Inclusion des fichiers de configuration et des modèles nécessaires
require_once __DIR__ . '/../../config/configuration.php';
require_once VIEWS_PATH . 'header.php';
require_once MODEL_PATH . 'Hotel.php';
require_once MODEL_PATH . 'Chambre.php';

// Vérification que la session est bien active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupération des données utilisateur
$utilisateur = $_SESSION['utilisateur'] ?? null;

// Vérification si les données utilisateur sont présentes
if (!$utilisateur) {
    echo "<p>Erreur : Aucune donnée utilisateur trouvée dans la session.</p>";
    echo '<p>Vous pouvez créer votre compte <a href="' . VIEWS_LIEN . 'utilisateur/formulaire_inscription_util.php">Inscription Utilisateur</a></p>';
    echo '<p>Ou vous connecter à votre compte <a href="' . VIEWS_LIEN . 'utilisateur/formulaire_connexion_util.php">Connexion Utilisateur</a></p>';
    exit;
}

// Récupération des données utilisateur et échappement pour la sécurité
$nomUtilisateur = htmlspecialchars($utilisateur['nom'] ?? '', ENT_QUOTES, 'UTF-8');
$prenomUtilisateur = htmlspecialchars($utilisateur['prenom'] ?? '', ENT_QUOTES, 'UTF-8');
$emailUtilisateur = htmlspecialchars($utilisateur['email'] ?? '', ENT_QUOTES, 'UTF-8');

// Initialisation des modèles
$hotelModel = new Hotel();
$chambreModel = new Chambre();

//methode opention
// Récupération des villes ####### avoir #########a vrifier ########## 1 le id des ville pose probleme
$villes = $hotelModel->obtenirVilles();

// Récupération des hotels  ####### avoir #########a vrifier ##########
$nomsHotels = $hotelModel->obtenirNomsHotelsV2();

// Par défaut, récupère toutes les chambres
$chambres = $chambreModel->obtenirToutesLesChambres();

// Si un hôtel a été sélectionné, on récupère uniquement les chambres libres de cet hôtel
if (isset($_POST['id_hotel']) && !empty($_POST['id_hotel'])) {
    $id_hotel = filter_var($_POST['id_hotel'], FILTER_VALIDATE_INT);
    if ($id_hotel) {
        $chambres = $chambreModel->obtenirChambresLibresParHotel($id_hotel);
    }
}

// Initialisation du tableau d'erreurs
$errors = [];

// Vérification et traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_arrivee = $_POST['date_arrivee'] ?? '';
    $date_depart = $_POST['date_depart'] ?? '';

    // Vérification des champs obligatoires
    if (empty($date_arrivee) || empty($date_depart)) {
        $errors[] = "Les deux dates sont obligatoires.";
    }

    // Vérification du format des dates
    if (!strtotime($date_arrivee) || !strtotime($date_depart)) {
        $errors[] = "Le format des dates est invalide.";
    }

    // Vérification de la cohérence des dates
    if (strtotime($date_arrivee) > strtotime($date_depart)) {
        $errors[] = "La date d'arrivée ne peut pas être postérieure à la date de départ.";
    }

    // Affichage du message de succès si aucune erreur
    if (empty($errors)) {
        echo "<p style='color: green;'>Réservation enregistrée avec succès !</p>";
    }
}

// Affichage des erreurs s'il y en a
if (!empty($errors)) {
    echo "<ul style='color: red;'>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
}
?>

<!-- Message de salutation personnalisé affiché pour l'utilisateur connecté -->
<h2>Bonjour, <?= $prenomUtilisateur . ' ' . $nomUtilisateur; ?>, vous pouvez faire une réservation</h2>

<form action="#" method="POST">
    <h2>Faire une Réservation</h2>

    <!-- Cette partie recueille les dates de séjour de l'utilisateur pour la réservation -->
    <fieldset class="form-boxe">
        <h3>Indiquez la durée de votre séjour</h3>
        <p><label for="date_arrivee">Date d'arrivée :</label>
            <input type="date" id="date_arrivee" name="date_arrivee" required>
        </p>
        <p><label for="date_depart">Date de départ :</label>
            <input type="date" id="date_depart" name="date_depart" required>
        </p>
    </fieldset>

    <fieldset class="form-boxe">
        <h3>Pré-sélection</h3>
        <!-- Cette section permet à l'utilisateur de sélectionner une ville.
             La soumission du formulaire est automatiquement déclenchée pour mettre à jour les options suivantes. -->
        <p>
            <label for="choixVille">Choix de la ville :</label>
            <select id="choixVille" name="choixVille" onchange="this.form.submit()">
                <option value="">-- Sélectionnez une ville --</option>
                <?php foreach ($villes as $ville) : ?>
                    <option value="<?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?>"
                        <?= (isset($_POST['choixVille']) && $_POST['choixVille'] === $ville) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p><br>

        <!-- Cette partie permet de choisir un hôtel en fonction de la ville sélectionnée -->
        <p>
            <label for="id_hotel">Choix de l'hôtel :</label>
            <select id="id_hotel" name="id_hotel" onchange="this.form.submit()">
                <option value="">-- Sélectionnez un hôtel --</option>
                <?php foreach ($nomsHotels as $hotel) : ?>
                    <option value="<?= htmlspecialchars($hotel['id'], ENT_QUOTES, 'UTF-8') ?>"
                        <?= (isset($_POST['id_hotel']) && $_POST['id_hotel'] == $hotel['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($hotel['hotel_nom'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <!-- Cette liste affiche les chambres disponibles dans l'hôtel sélectionné -->
        <p>
            <label for="id_chambre">Choix de la chambre :</label>
            <select id="id_chambre" name="id_chambre">
                <option value="">-- Sélectionnez une chambre --</option>
                <?php foreach ($chambres as $chambre) : ?>
                    <option value="<?= htmlspecialchars($chambre['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($chambre['numero'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
    </fieldset>



    <!-- Champs cachés pour transmettre les informations de l'utilisateur -->
    <p>
        <input type="tex" name="nom" value="<?= $nomUtilisateur ?>">
    </p>
    <p>
        <input type="tex" name="prenom" value="<?= $prenomUtilisateur ?>">
    </p>
    <p>
        <input type="email" name="email" value="<?= $emailUtilisateur ?>">
    </p>

    <p><button class="btn" type="submit">Confirmer les choix</button></p>
</form>

<?php require_once VIEWS_PATH . 'footer.php'; ?>