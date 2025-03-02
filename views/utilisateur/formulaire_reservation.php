<?php require_once '../header.php'; ?>

<h2>Faire une Réservation</h2>
<form action="../../controllers/ReservationController.php" method="POST">
    <label for="hotel">Hôtel :</label>

    <!-- option "ux" amelior lexperiense utilisateur -->
    <!-- afiche la liste des hotel diponible si acun hotel n'a était selectioner -->
    <select id="hotel" name="hotel" required>
        <!-- Dynamique : Ajouter les options chois des diferen hotel via une boucle PHP -->
        <option value="1">Hôtel 1</option>
        <option value="2">Hôtel 2</option>
    </select><br>

    <!-- lorse qu'un hotele et selectionet afiche les chambre de cette hotele et leur prix  -->
    <select id="hotel" name="hotel" required>
        <!-- Dynamique : Ajouter les options via une boucle PHP -->
        <option value="1"> chambre 1</option>
        <option value="2"> chambre 2</option>
    </select><br>

    <!-- lorse qu'une setion utilisateur et ative prerenplie le formulaire 
    --   avec le non de lutilisateur sont telephone 
    -->

    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required><br>

    <label for="telephone">Téléphone :</label>
    <input type="text" id="telephone" name="telephone" required><br>

    <label for="date_arrivee">Date d'arrivée :</label>
    <input type="date" id="date_arrivee" name="date_arrivee" required><br>

    <label for="date_depart">Date de départ :</label>
    <input type="date" id="date_depart" name="date_depart" required><br>

    <button class="btn" type="submit">Réserver</button>
</form>

<?php
echo "<pre>";
var_dump($_POST);
echo "</pre>";

?>

<p>on en parle appré</p>
<?php require_once '../footer.php'; ?>