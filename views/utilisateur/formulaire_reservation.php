<?php
/* require_once 'E:\laragon\www\projet_hotel\config\configuration.php'; */
require_once __DIR__ . '/../../config/configuration.php';
require_once VIEWS_PATH . 'header.php';
require_once MODEL_PATH . 'Hotel.php';
require_once MODEL_PATH . 'Chambre.php';
?>

<h2>Faire une Réservation</h2>
<form action="#" method="POST">

    <p>choix de la ville</p>



    <!-- je vais devoir ajouter les VILLE au fomulaire de creation d'hotel et a la basse de donner  -->


    <label for="choixHotel">Choix hôtel. Dans la vile de: ..... </label>

    <!-- option "ux" amelior lexperiense utilisateur -->
    <!-- TRAVAILE EN COURS  afiche la liste des hotel diponible si acun hotel n'a était selectioner TRAVAILE EN COURS -->
    <select id="choixHotel" name="choixHotel" required>
        <!-- Dynamique : Ajouter les options chois des diferen hotel via une boucle PHP -->
        <?php
        $hotelModel = new Hotel();
        // Récupération du tableau des noms d'hôtels TRAVAILE EN COURS
        $nomsHotels = $hotelModel->obtenirNomsHotels();
        // Affichage du résultat

        var_dump($nomsHotels);

        foreach ($nomsHotels as $key => $nomHotels) {
            $n = 0;
            $n++;
            echo '<option value="' . $n . '">' . $nomHotels . '</option>';
        }


        ?>
    </select><br>

    <label for="choixChambre">Choix chambre :</label>

    <!-- lorse qu'un hotele et selectionet afiche les chambre de cette hotele et leur prix  -->
    <select id="choixChambre" name="choixChambre" required>
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
<?php require_once VIEWS_PATH . 'footer.php'; ?>