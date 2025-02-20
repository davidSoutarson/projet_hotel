<?php

require_once __DIR__ . '/../models/Hotel.php';
require_once __DIR__ . '/../controllers/SessionEntrController.php';
require_once __DIR__ . '/../config/configuration.php'; // Ajout de la connexion à la DB



class HotelController
{
    private $hotelModel;
    private $connexion;

    public function __construct()
    {
        $this->hotelModel = new Hotel();

        // Initialisation de la connexion à la DB
        $database = new Database();
        $this->connexion = $database->obtenirConnexion();
    }

    public function ajouterHotel($nomHotel, $adresseHotel, $telephoneHotel, $description_hotel, $nombre_chambres, $photoHotel, $id_entreprise)
    {
        // Vérification de la session de l'entreprise
        if (!SessionEntrController::verifierSession()) {
            header('Location: ../views/entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
            exit();
        }

        // Vérification des données manquantes
        if (empty($nomHotel) || empty($adresseHotel) || empty($telephoneHotel) || empty($photoHotel)) {
            header('Location: ../views/entreprise/formulaire_ajouter_hotel.php?erreur=donnees_manquantes');
            exit();
        }

        // Assurer que le nombre de chambres est un entier valide
        $nombre_chambres = is_numeric($nombre_chambres) ? (int) $nombre_chambres : null;

        // Ajout de l'hôtel dans la base de données
        $idHotel = $this->hotelModel->ajouterHotel($nomHotel, $adresseHotel, $telephoneHotel, $description_hotel, $nombre_chambres, $photoHotel, $id_entreprise);

        // Si l'hôtel a été ajouté avec succès, rediriger
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
    /* a verifier */
    public function obtenirNombreDeChambres($id_hotel)
    {
        return $this->hotelModel->getNombreChambres($id_hotel);
    }

    public function obtenirHotelsParEntreprise($id_entreprise)
    {
        if (!$id_entreprise) {
            return []; // Retourne un tableau vide si l'entreprise n'est pas valide
        }

        $requete = "SELECT id, nom FROM hotels WHERE id_entreprise = :id_entreprise";
        $stmt = $this->connexion->prepare($requete); // Utilise $this->connexion ici directement
        $stmt->bindParam(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_hotel') {
    $hotelController = new HotelController();

    // Récupérer et nettoyer les entrées utilisateur
    $nom = trim($_POST['nom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $description_hotel = trim($_POST['description'] ?? '');
    $nombre_chambres = trim($_POST['nombre_chambres'] ?? '');
    $photo = $_FILES['photo']['name'] ?? '';

    // Vérifier l'ID de l'entreprise connectée
    $id_entreprise = SessionEntrController::getIdEntreprise();
    if (!$id_entreprise) {
        header('Location: ../views/entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
        exit();
    }

    // Gestion de l'upload de la photo
    if ($photo && $_FILES['photo']['tmp_name']) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $extension = strtolower(pathinfo($photo, PATHINFO_EXTENSION));

        // Vérification de l'extension et taille du fichier
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5 Mo

        if (!in_array($extension, $allowedExtensions)) {
            header('Location: ../views/entreprise/formulaire_ajouter_hotel.php?erreur=extension_invalide');
            exit();
        }

        if ($_FILES['photo']['size'] > $maxFileSize) {
            header('Location: ../views/entreprise/formulaire_ajouter_hotel.php?erreur=fichier_trop_grand');
            exit();
        }

        $photoName = uniqid('hotel_') . '.' . $extension;
        $uploadFile = $uploadDir . $photoName;

        // Déplacer le fichier téléchargé dans le répertoire
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            // Appeler la méthode pour ajouter l'hôtel avec la photo
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
