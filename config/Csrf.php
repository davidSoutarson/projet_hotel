<?php
/**
 * ============================================================================
 *  [IA-Hermès]  Protection contre les attaques CSRF (Cross-Site Request Forgery)
 * ============================================================================
 *  À quoi ça sert ?
 *  Une attaque CSRF consiste à faire soumettre un formulaire de VOTRE site
 *  par un AUTRE site malveillant, à l'insu de l'utilisateur (ex. : forcer
 *  une réservation ou une modification de données).
 *
 *  Comment on se protège ?
 *   1) On génère un jeton (token) aléatoire, unique, stocké dans la SESSION.
 *   2) Ce jeton est glissé dans le formulaire (champ caché).
 *   3) À la réception du POST, on compare le jeton reçu avec celui de la session.
 *        - Identiques  → c'est bien VOTRE formulaire qu'on a soumis → on accepte.
 *        - Absent/faux → c'est une falsification → on refuse.
 *
 *  Utilisation :
 *   - Dans chaque FORMULAIRE (vues)      :  echo Csrf::field();
 *   - Dans chaque CONTRÔLEUR qui gère POST : Csrf::verify();
 * ============================================================================
 */
class Csrf
{
    /** [IA-Hermès] Démarre la session si elle n'est pas encore active. */
    public static function ensureSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /** [IA-Hermès] Crée (une seule fois) le jeton de la session et le renvoie. */
    public static function token()
    {
        self::ensureSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /** [IA-Hermès] Renvoie le champ caché à coller juste après la balise <form>. */
    public static function field()
    {
        return '<input type="hidden" name="csrf_token" value="'
             . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * [IA-Hermès] À appeler AU DÉBUT de chaque contrôleur qui traite un POST.
     * Refuse la requête si le jeton est manquant ou invalide.
     */
    public static function verify()
    {
        self::ensureSession();
        $reçu = $_POST['csrf_token'] ?? '';
        // hash_equals : comparaison protégée contre les "timing attacks" (sécurité).
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $reçu)) {
            $_SESSION['erreur'] = 'Requête refusée : jeton de sécurité invalide (protection CSRF).';
            header('Location: index.php');
            exit;
        }
    }
}
