<?php
/* require_once 'E:\laragon\www\projet_hotel\config\configuration.php'; */
require_once __DIR__ . '/../../config/configuration.php';
require_once VIEWS_PATH . 'header.php';
?>

<h2>Connexion Utilisateur</h2>

<p>Description pour le devoir ce fichier permet de vérifier si l'utilisateur possède un conte</p>

<form action="../../controllers/connexionUtilControleur.php" method="POST">

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required><br>

    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

    <button class="btn" type="submit">Se connecter</button>
</form>

<?php
if (isset($_GET['erreur'])) {
    switch ($_GET['erreur']) {
        case 'session_expired':
            echo "<p class='error-message'>Votre session a expiré. Veuillez vous reconnecter.</p>";
            break;
        case '3':
            echo "<p class='error-message'>Erreur inconnue. Contactez l'administrateur.</p>";
            break;
        default:
            echo "<p class='error-message'>Une erreur est survenue.</p>";
    }
}


require_once VIEWS_PATH . 'footer.php';

?>