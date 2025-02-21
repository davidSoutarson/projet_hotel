<?php
require_once '../models/Reservation.php';

echo "<pre>";
var_dump($_POST);
echo "</pre>";

class ReservationController
{
    private $reservationModel;

    public function __construct()
    {
        $this->reservationModel = new Reservation();
    }

    public function ajouterReservation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idUtilisateur = $_POST['nom'] ?? null; // Id utilisateur peut être géré par session si disponible
            $hotelId = $_POST['hotel'] ?? null;
            $dateArrivee = $_POST['date_arrivee'] ?? null;
            $dateDepart = $_POST['date_depart'] ?? null;

            if ($idUtilisateur && $hotelId && $dateArrivee && $dateDepart) {
                $reservationReussie = $this->reservationModel->ajouterReservation(
                    $idUtilisateur,
                    $hotelId,
                    $dateArrivee,
                    $dateDepart
                );

                if ($reservationReussie) {
                    header('Location: ../views/utilisateur/formulaire_reservation.php?success=true');
                } else {
                    header('Location: ../views/utilisateur/formulaire_reservation.php?error=database');
                }
            } else {
                header('Location: ../views/utilisateur/formulaire_reservation.php?error=missing_data');
            }
        }
    }
}

$controller = new ReservationController();
$controller->ajouterReservation();
