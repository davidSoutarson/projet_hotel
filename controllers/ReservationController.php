<?php
session_start();
require_once __DIR__ . '/../config/configuration.php';
require_once MODEL_PATH . 'Reservation.php';
require_once MODEL_PATH . 'Chambre.php';
require_once MODEL_PATH . 'Hotel.php';

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
        $utilisateur = $_SESSION['utilisateur'];
        $idUtilisateur = htmlspecialchars($utilisateur['id'] ?? '', ENT_QUOTES, 'UTF-8');

        // Définition des champs obligatoires attendus du formulaire
        // Remarque : les noms des champs ici doivent correspondre exactement à ceux envoyés par le formulaire.
        $champsRequis = ['choixVille', 'id_hotel', 'id_chambre', 'date_arrivee', 'date_depart', 'id_utilisateur'];

        foreach ($champsRequis as $champ) {
            // Vérifie que le champ est défini et non vide (après suppression des espaces)
            if (!isset($_POST[$champ]) || empty(trim($_POST[$champ]))) {
                $this->redirigerAvecErreur("Champ manquant : $champ");
                return;
            }
        }

        // (Vérification de sécurité en s'assurant qu'un utilisateur ne peut pas soumettre une réservation en utilisant l'ID d'un autre utilisateur.
        // Vérification de la correspondance entre l'ID utilisateur du formulaire et celui de la session
        $idUtilisateurFormulaire = htmlspecialchars($_POST['id_utilisateur'] ?? '', ENT_QUOTES, 'UTF-8');
        if ($idUtilisateurFormulaire !== $idUtilisateur) {
            $this->redirigerAvecErreur("Incohérence entre l'identifiant utilisateur du formulaire et celui de la session.");
            return;
        }

        // Traitement et validation des données reçues

        //? On suppose que 'choixVille' est envoyé en tant qu'entier (ID de la ville), sinon on pourrait le traiter différemment.
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


        /*verifier date*/
        // Utilisation de DateTime pour une validation plus précise des dates
        $dateArriveeObj = DateTime::createFromFormat('Y-m-d', $dateArrivee);
        $dateDepartObj = DateTime::createFromFormat('Y-m-d', $dateDepart);

        // Vérification du format correct (createFromFormat retourne false si le format est incorrect)
        if (!$dateArriveeObj || !$dateDepartObj) {
            $this->redirigerAvecErreur("Format de date invalide (AAAA-MM-JJ attendu)");
            return;
        }

        // Vérifie que la date d'arrivée n'est pas dans le passé
        $aujourdHui = new DateTime(); // Date du jour
        $aujourdHui->setTime(0, 0); // On met l'heure à 00:00 pour éviter les soucis avec les heures actuelles

        if ($dateArriveeObj < $aujourdHui) {
            $this->redirigerAvecErreur("La date d'arrivée ne peut pas être dans le passé.");
            return;
        }

        // Vérifie que la date d'arrivée est bien avant la date de départ
        if ($dateArriveeObj >= $dateDepartObj) {
            $this->redirigerAvecErreur("Les dates d'arrivée et de départ sont incohérentes.");
            return;
        }

        //fin verif date

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

        // partie modifier
        try {
            $reservationReussie = $this->reservationModel->ajouterReservation(
                $idUtilisateur,
                $id_chambre,
                $dateArrivee,
                $dateDepart,
                $id_hotel
            );

            if ($reservationReussie) {
                // ➡️ Enregistrer les détails de la réservation dans la session
                $_SESSION['reservation_details'] = [
                    'ville' => $_POST['nom_Ville'], /* modife */
                    'hotel' => $_POST['nom_hotel'],
                    'chambre' => $_POST['numero_chambre'],
                    'date_arrivee' => $dateArrivee,
                    'date_depart' => $dateDepart
                ];
                session_write_close(); // Assurez-vous que les données sont bien enregistrées

                $this->ajouterLog("Réservation réussie pour l'utilisateur ID: $idUtilisateur");
                header("Location:" . VIEWS_LIEN . "/utilisateur/confirmation_reservation.php");
                exit;
            } else {
                $this->redirigerAvecErreur("Erreur inconnue. Contactez l'administrateur.");
            }
        } catch (Exception $e) {
            $this->ajouterLog("Erreur lors de l'ajout : " . $e->getMessage());
            $this->redirigerAvecErreur("Erreur lors de l'ajout : " . $e->getMessage());
        }

        //fin partie modifier
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
