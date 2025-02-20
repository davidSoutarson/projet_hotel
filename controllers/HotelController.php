<?php
require_once __DIR__ . '/../models/Hotel.php';
require_once __DIR__ . '/../controllers/SessionEntrController.php';

class HotelController
{
    private $hotelModel;

    public function __construct()
    {
        $this->hotelModel = new Hotel();
    }

    public function ajouterHotel($nomHotel, $adresseHotel, $telephoneHotel, $description_hotel, $nombre_chambre, $photoHotel, $id_entreprise)
    {
        if (!SessionEntrController::verifierSession()) {
            header('Location: ../views/entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
            exit();
        }

        if (empty($nomHotel) || empty($adresseHotel) || empty($telephoneHotel) || empty($photoHotel)) {
            header('Location: ../views/entreprise/formulaire_ajouter_hotel.php?erreur=donnees_manquantes');
            exit();
        }

        // Conversion de nombre_chambre en entier, avec gestion des valeurs vides
        $nombre_chambre = is_numeric($nombre_chambre) ? (int) $nombre_chambre : null;

        $idHotel = $this->hotelModel->ajouterHotel($nomHotel, $adresseHotel, $telephoneHotel, $description_hotel, $nombre_chambre, $photoHotel, $id_entreprise);

        if ($idHotel) {
            header("Location: ../views/entreprise/formulaire_ajouter_chambre.php?hotel=$idHotel&success=hotel_ajoute");
        } else {
            header('Location: ../views/entreprise/formulaire_ajouter_hotel.php?erreur=echec_enregistrement');
        }
        exit();
    }

    public function obtenirTousLesHotels()
    {
        return $this->hotelModel->obtenirHotels();
    }

    public function obtenirNombreDeChambres($id_hotel)
    {
        require_once __DIR__ . '/../models/Hotel.php';
        $hotelModel = new Hotel();

        return $hotelModel->getNombreChambres($id_hotel);
    }
}

// Vérification avant de traiter le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_hotel') {
    $hotelController = new HotelController();

    $nom = trim($_POST['nom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $description_hotel = trim($_POST['description'] ?? '');
    $nombre_chambre = trim($_POST['nombre_de_chambre'] ?? '');
    $photo = $_FILES['photo']['name'] ?? '';

    // Récupération de l'ID de l'entreprise connectée
    $id_entreprise = SessionEntrController::getIdEntreprise();
    if (!$id_entreprise) {
        header('Location: ../views/entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
        exit();
    }

    if ($photo && $_FILES['photo']['tmp_name']) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $extension = pathinfo($photo, PATHINFO_EXTENSION);
        $photoName = uniqid('hotel_') . '.' . $extension;
        $uploadFile = 'uploads/' . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/../uploads/' . $photoName)) {
            // Enregistrer l'hôtel avec nombre_chambre converti en entier ou NULL
            $hotelController->ajouterHotel($nom, $adresse, $telephone, $description_hotel, $nombre_chambre, $uploadFile, $id_entreprise);
        } else {
            header('Location: ../views/entreprise/formulaire_ajouter_hotel.php?erreur=echec_upload');
            exit();
        }
    } else {
        header('Location: ../views/entreprise/formulaire_ajouter_hotel.php?erreur=photo_manquante');
        exit();
    }
}
