<?php
// Inclusion des fichiers de configuration et des modèles nécessaires
require_once __DIR__ . '/../../config/configuration.php';
require_once VIEWS_PATH . 'header.php';
require_once MODEL_PATH . 'Hotel.php';
require_once MODEL_PATH . 'Chambre.php';

// Démarrage de la session (si non démarrée)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupération des données utilisateur
$utilisateur = $_SESSION['utilisateur'] ?? null;

// Si l'utilisateur n'est pas connecté, redirige vers la page de connexion ou affiche un message d'erreur
if (!$utilisateur) {
    echo "<div class='info-boxe'>
            <h2>Erreur :</h2>
            <p>Aucune donnée utilisateur trouvée dans la session.</p>
            <p>Vous pouvez créer votre compte <a class='btn' href='" . VIEWS_LIEN . "utilisateur/formulaire_inscription_util.php'>Inscription Utilisateur</a></p>
            <p>Ou vous connecter à votre compte <a class='btn' href='" . VIEWS_LIEN . "utilisateur/formulaire_connexion_util.php'>Connexion Utilisateur</a></p>
          </div>";
    require_once VIEWS_PATH . 'footer.php';
    exit;
}

// Sécurisation des données utilisateur
$nomUtilisateur    = htmlspecialchars($utilisateur['nom'] ?? '', ENT_QUOTES, 'UTF-8');
$prenomUtilisateur = htmlspecialchars($utilisateur['prenom'] ?? '', ENT_QUOTES, 'UTF-8');
$emailUtilisateur  = htmlspecialchars($utilisateur['email'] ?? '', ENT_QUOTES, 'UTF-8');
$idUtilisateur     = htmlspecialchars($utilisateur['id'] ?? '', ENT_QUOTES, 'UTF-8');

// Initialisation des modèles
$hotelModel   = new Hotel();
$chambreModel = new Chambre();

// Récupération des villes depuis le modèle Hotel
$villes = $hotelModel->obtenirVilles();

// Détection de l'étape du formulaire (initialement vide)
$etape = $_POST['etape'] ?? '';
?>

<h2>Bonjour, <?= $prenomUtilisateur . ' ' . $nomUtilisateur; ?>, vous pouvez faire une réservation</h2>

<?php
// =========================
// ÉTAPE 1 : Choix de la ville
// =========================
if (empty($etape)) :
?>
    <form method="post" action="">
        <fieldset class="form-boxe">
            <h3>Pré-sélection - Choix de la ville</h3>
            <p>
                <label for="choixVille">Choix de la ville :</label>
                <select id="choixVille" name="choixVille" required>
                    <option value="">-- Sélectionnez une ville --</option>
                    <?php foreach ($villes as $ville) : ?>
                        <option value="<?= htmlspecialchars($ville['id_ville'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($ville['nom_ville'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
        </fieldset>
        <!-- Passage à l'étape suivante -->
        <input type="hidden" name="etape" value="ville">
        <p><button class="btn" type="submit">Valider la ville</button></p>
    </form>

<?php
// ==============================
// ÉTAPE 2 : Choix de l'hôtel selon la ville sélectionnée
// ==============================
elseif ($etape == 'ville') :

    // Récupération et sécurisation de la ville choisie
    $choixVille = $_POST['choixVille'] ?? '';
    $choixVille = filter_var($choixVille, FILTER_VALIDATE_INT);

    if (!$choixVille) {
        echo "<p style='color:red;'>Veuillez sélectionner une ville valide.</p>";
        exit;
    }

    // Récupérer les hôtels correspondants via le modèle
    $nomsHotels = $hotelModel->obtenirHotelsParVille($choixVille);

    if (empty($nomsHotels)) {
        echo "<p style='color:red;'>Aucun hôtel trouvé pour cette ville.</p>";
        exit;
    }
?>
    <form method="post" action="">
        <fieldset class="form-boxe">
            <h3>Pré-sélection - Choix de l'hôtel</h3>
            <p>
                <label for="id_hotel">Choix de l'hôtel :</label>
                <select id="id_hotel" name="id_hotel" required>
                    <option value="">-- Sélectionnez un hôtel --</option>
                    <?php foreach ($nomsHotels as $hotel) : ?>
                        <option value="<?= htmlspecialchars($hotel['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($hotel['hotel_nom'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
        </fieldset>
        <!-- Conserver la ville sélectionnée pour la suite -->
        <input type="hidden" name="choixVille" value="<?= htmlspecialchars($choixVille, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="etape" value="hotel">
        <p><button class="btn" type="submit">Valider l'hôtel</button></p>
    </form>

<?php
// ===================================
// ÉTAPE 3 : Formulaire complet de réservation
// ===================================
elseif ($etape == 'hotel') :

    // Récupération et sécurisation des sélections précédentes
    $choixVille = $_POST['choixVille'] ?? '';
    $choixVille = filter_var($choixVille, FILTER_VALIDATE_INT);
    $id_hotel   = $_POST['id_hotel'] ?? '';
    $id_hotel   = filter_var($id_hotel, FILTER_VALIDATE_INT);

    if (!$choixVille || !$id_hotel) {
        echo "<p style='color:red;'>Veuillez sélectionner une ville et un hôtel valides.</p>";
        exit;
    }

    // Récupérer les chambres libres pour l'hôtel sélectionné
    $chambres = $chambreModel->obtenirChambresLibresParHotel($id_hotel);

    if (empty($chambres)) {
        echo "<p style='color:red;'>Aucune chambre disponible pour cet hôtel.</p>";
        exit;
    }
?>
    <form action="../../controllers/ReservationController.php" method="POST">
        <h2>Faire une Réservation</h2>
        <fieldset class="form-boxe">
            <h3>Indiquez la durée de votre séjour</h3>
            <p>
                <label for="date_arrivee">Date d'arrivée :</label>
                <input type="date" id="date_arrivee" name="date_arrivee" required>
            </p>
            <p>
                <label for="date_depart">Date de départ :</label>
                <input type="date" id="date_depart" name="date_depart" required>
            </p>
        </fieldset>
        <fieldset class="form-boxe">
            <h3>Choix de la chambre</h3>
            <p>
                <label for="id_chambre">Choix de la chambre :</label>
                <select id="id_chambre" name="id_chambre" required>
                    <option value="">-- Sélectionnez une chambre --</option>
                    <?php foreach ($chambres as $chambre) : ?>
                        <option value="<?= htmlspecialchars($chambre['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($chambre['numero'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
        </fieldset>
        <!-- Transmettre les informations utilisateur -->
        <input type="hidden" name="nom" value="<?= $nomUtilisateur ?>">
        <input type="hidden" name="prenom" value="<?= $prenomUtilisateur ?>">
        <input type="hidden" name="email" value="<?= $emailUtilisateur ?>">
        <input type="hidden" name="id_utilisateur" value="<?= $idUtilisateur ?>">
        <!-- Conserver les sélections de ville et d'hôtel -->
        <input type="hidden" name="choixVille" value="<?= htmlspecialchars($choixVille, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="id_hotel" value="<?= htmlspecialchars($id_hotel, ENT_QUOTES, 'UTF-8') ?>">
        <p><button class="btn" type="submit">Confirmer la réservation</button></p>
    </form>
<?php
endif;
?>

<?php require_once VIEWS_PATH . 'footer.php'; ?>