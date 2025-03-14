<?php
session_start();
require_once __DIR__ . '/../../config/configuration.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Chambre.php';
require_once __DIR__ . '/../models/Hotel.php';

/**
 * Contrôleur de réservation
 * Gère l'ajout d'une réservation en validant les entrées, vérifiant la disponibilité, et redirigeant en cas d'erreur.
 */
class ReservationController
{
    private $reservationModel;
    private $chambreModel;
    private $hotelModel;

    /**
     * Constructeur : Initialise les modèles nécessaires.
     */
    public function __construct()
    {
        $this->reservationModel = new Reservation();
        $this->chambreModel = new Chambre();
        $this->hotelModel = new Hotel();
    }

    /**
     * Ajoute une réservation.
     *
     * Vérifie d'abord que l'utilisateur est connecté, puis s'assure que tous les champs requis sont fournis.
     * Effectue plusieurs vérifications : format des dates, cohérence des dates, existence de l'hôtel et de la chambre,
     * ainsi que la disponibilité de la chambre.
     * En cas de succès, la réservation est ajoutée et l'utilisateur est redirigé vers la page de confirmation.
     */
    public function ajouterReservation()
    {
        $this->ajouterLog("Début de l'ajout de réservation.");

        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
            $this->redirigerAvecErreur("Utilisateur non connecté");
            return;
        }
        $idUtilisateur = $_SESSION['utilisateur']['id_utilisateur'];

        // Définition des champs obligatoires attendus du formulaire
        // Remarque : les noms des champs ici doivent correspondre exactement à ceux envoyés par le formulaire.
        $champsRequis = ['choixVille', 'id_hotel', 'id_chambre', 'date_arrivee', 'date_depart'];
        foreach ($champsRequis as $champ) {
            // Vérifie que le champ est défini et non vide (après suppression des espaces)
            if (!isset($_POST[$champ]) || empty(trim($_POST[$champ]))) {
                $this->redirigerAvecErreur("Champ manquant : $champ");
                return;
            }
        }

        // Traitement et validation des données reçues

        // On suppose que 'choixVille' est envoyé en tant qu'entier (ID de la ville), sinon on pourrait le traiter différemment.
        $choixVille = filter_var($_POST['choixVille'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        // Récupération et validation de l'ID de l'hôtel (champ 'id_hotel')
        $id_hotel = filter_var($_POST['id_hotel'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        // Récupération et validation de l'ID de la chambre (champ 'id_chambre')
        $id_chambre = filter_var($_POST['id_chambre'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        // Traitement des dates en échappant les caractères spéciaux et en supprimant les espaces inutiles
        $dateArrivee = htmlspecialchars(trim($_POST['date_arrivee']));
        $dateDepart  = htmlspecialchars(trim($_POST['date_depart']));

        // Vérification de la validité numérique des IDs de l'hôtel et de la chambre
        if (!$id_chambre || !$id_hotel) {
            $this->redirigerAvecErreur("ID de chambre ou d'hôtel invalide");
            return;
        }

        // Vérification du format des dates (AAAA-MM-JJ)
        if (!$this->verifierFormatDate($dateArrivee) || !$this->verifierFormatDate($dateDepart)) {
            $this->redirigerAvecErreur("Format de date invalide (format attendu : AAAA-MM-JJ)");
            return;
        }

        // Vérifier que la date d'arrivée n'est pas dans le passé
        if (strtotime($dateArrivee) < strtotime(date('Y-m-d'))) {
            $this->redirigerAvecErreur("La date d'arrivée ne peut pas être dans le passé.");
            return;
        }

        // Vérifier la cohérence des dates : la date d'arrivée doit être strictement inférieure à la date de départ
        if (strtotime($dateArrivee) >= strtotime($dateDepart)) {
            $this->redirigerAvecErreur("Les dates d'arrivée et de départ sont incohérentes");
            return;
        }

        // Vérifier que l'hôtel existe en base de données
        if (!$this->hotelModel->existeHotel($id_hotel)) {
            $this->redirigerAvecErreur("L'hôtel sélectionné n'existe pas.");
            return;
        }

        // Vérifier que la chambre existe en base de données
        if (!$this->chambreModel->existeChambre($id_chambre)) {
            $this->redirigerAvecErreur("La chambre sélectionnée n'existe pas.");
            return;
        }

        // Vérifier la disponibilité de la chambre pour les dates demandées
        if (!$this->reservationModel->verifierDisponibilite($id_chambre, $dateArrivee, $dateDepart)) {
            $this->redirigerAvecErreur("La chambre est indisponible pour les dates choisies.");
            return;
        }

        // Tentative d'ajout de la réservation dans la base
        try {
            $reservationReussie = $this->reservationModel->ajouterReservation(
                $idUtilisateur,
                $$id_chambre,
                $dateArrivee,
                $dateDepart,
                $id_hotel
            );

            if ($reservationReussie) {
                $this->ajouterLog("Réservation réussie pour l'utilisateur ID: $idUtilisateur");
                header("Location:" . VIEWS_LIEN . "/utilisateur/confirmation_reservation.php");
                exit;
            } else {
                $this->redirigerAvecErreur("Erreur inconnue. Contactez l'administrateur.");
            }
        } catch (Exception $e) {
            // En cas d'exception, log l'erreur et redirige avec un message d'erreur
            $this->ajouterLog("Erreur lors de l'ajout : " . $e->getMessage());
            $this->redirigerAvecErreur("Erreur lors de l'ajout : " . $e->getMessage());
        }
    }

    /**
     * Vérifie si une date respecte le format AAAA-MM-JJ.
     *
     * @param string $date
     * @return bool
     */
    private function verifierFormatDate($date)
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
    }

    /**
     * Redirige l'utilisateur en cas d'erreur en enregistrant un message d'erreur.
     *
     * @param string $erreur
     */
    private function redirigerAvecErreur($erreur)
    {
        $this->ajouterLog("Erreur : $erreur");
        $_SESSION['erreur_reservation'] = htmlspecialchars($erreur);
        header("Location: " . VIEWS_LIEN . "utilisateur/erreur_reservation.php");
        exit;
    }

    /**
     * Ajoute une entrée dans le fichier de log pour le suivi des actions.
     *
     * @param string $message
     */
    private function ajouterLog($message)
    {
        $logFile = __DIR__ . '/../log/reservation.log';
        $date = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$date] $message\n", FILE_APPEND);
    }
}

// Exécution du contrôleur uniquement si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ReservationController();
    $controller->ajouterReservation();
} else {
    echo "<p style='color:red;'>Méthode de requête invalide.</p>";
}
