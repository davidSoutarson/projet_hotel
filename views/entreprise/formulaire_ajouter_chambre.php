<?php require_once '../header.php'; ?>

<h2>Ajouter une Chambre</h2>
<form action="../../controllers/ChambreController.php" method="POST">
    <label for="numero">Numéro :</label>
    <input type="text" id="numero" name="numero" required><br>

    <label for="prix">Prix :</label>
    <input type="number" id="prix" name="prix" required><br>

    <label for="nombre_lits">Nombre de lits :</label>
    <input type="number" id="nombre_lits" name="nombre_lits" required><br>

    <label for="etat">État :</label>
    <select id="etat" name="etat">
        <option value="libre">Libre</option>
        <option value="reserve">Réservé</option>
    </select><br>

    <button type="submit">Ajouter Chambre</button>
</form>

<?php require_once '../footer.php'; ?>