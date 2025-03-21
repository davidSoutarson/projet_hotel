<?php
/* require_once 'E:\laragon\www\projet_hotel\config\configuration.php'; */
require_once __DIR__ . '/../../config/configuration.php';
require_once VIEWS_PATH . 'header.php';
require_once CONTROLLER_PATH . 'SessionEntrController.php';

// Vérifier si l'entreprise est connectée
if (!SessionEntrController::verifierSession()) {
    header('Location: ../entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
    exit();
}

?>

<p> Description pour le devoir :</p>
<p> Ce fichier permet d'ajouter un hôtel en france metropoliténe uniquement </p>

<h2>Ajouter un Hôtel</h2>

<form action="../../controllers/HotelController.php" method="POST" enctype="multipart/form-data">
    <p>
        <label for="nom">Nom de l'hotel :</label>
        <input type="text" id="nom" name="nom" required>
    </p>

    <p>
        <label for="ville">Ville :</label>
        <input type="text" id="ville" name="ville" required>
    </p>

    <p>
        <label for="code_postal">Code postal :</label>
        <input type="text" id="code_postal" name="code_postal" pattern="\d{5}" maxlength="5" inputmode="numeric" required>
    </p>

    <p>
        <label for="adresse">Adresse :</label>
        <input type="text" id="adresse" name="adresse" required>
    </p>

    <p>
        <label for="telephone">Téléphone :</label>
        <input type="text" id="telephone" name="telephone" pattern="\d{10}" maxlength="10" inputmode="numeric" required>
    </p>

    <p>
        <label for="description">Description :</label>
        <textarea id="description" name="description"></textarea><br>
    </p>

    <p>
        <label for="nombre_chambres">Nombre de chambres:</label>
        <input type="number" id="nombre_chambres" name="nombre_chambres"><br>
    </p>

    <p>
        <label for=" photo">Photo :</label>
        <input type="file" id="photo" name="photo" accept="image/*"><br>
    </p>

    <!-- Ajout du champ caché pour éviter l'erreur -->
    <input type="hidden" name="action" value="ajouter_hotel">
    <p>
        <button class="btn ajouter" type="submit">Ajouter Hôtel</button>
    </p>
</form>

<?php require_once VIEWS_PATH . 'footer.php'; ?>