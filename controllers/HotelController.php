<?php
// Inclusion du modèle Hotel
require_once __DIR__ . '/../models/Hotel.php';
// Inclusion du contrôleur de session entreprise (pour vérifier si l'entreprise est connectée)
require_once __DIR__ . '/../controllers/SessionEntrController.php';

class HotelController
{
    private $hotelModel;

    /**
     * Constructeur : instancie le modèle Hotel.
     */
    public function __construct()
    {
        $this->hotelModel = new Hotel();
    }

    /**
     * Retourne la liste de tous les hôtels.
     *
     * @return array La liste des hôtels.
     */
    public function obtenirTousLesHotels()
    {
        return $this->hotelModel->obtenirHotels();
    }

    /**
     * Ajoute un nouvel hôtel à la base de données.
     *
     * Avant d'ajouter, on vérifie si l'entreprise est connectée.
     * En cas de succès, on redirige vers le formulaire d'ajout des chambres,
     * sinon, on redirige vers le formulaire d'ajout d'hôtel avec un message d'erreur.
     *
     * @param string $nomHotel        Le nom de l'hôtel.
     * @param string $adresseHotel    L'adresse de l'hôtel.
     * @param string $telephoneHotel  Le téléphone de l'hôtel.
     * @param string $descriptionHotel La description de l'hôtel.
     * @param string $photoHotel      Le chemin ou nom de fichier de la photo.
     */
    public function ajouterHotel($nomHotel, $adresseHotel, $telephoneHotel, $descriptionHotel, $photoHotel)
    {
        // Vérifie si une session entreprise est active
        if (!SessionEntrController::verifierSession()) {
            // Redirection vers le formulaire de connexion entreprise si non connecté
            header('Location: ../views/entreprise/formulaire_connexion_entr.php?erreur=connexion');
            exit();
        }

        // Ajout de l'hôtel via le modèle
        $resultat = $this->hotelModel->ajouterHotel($nomHotel, $adresseHotel, $telephoneHotel, $descriptionHotel, $photoHotel);

        if ($resultat) {
            // Récupère l'ID du dernier hôtel inséré pour pouvoir l'utiliser dans l'ajout des chambres
            $idHotel = $this->hotelModel->recupererDernierId();
            // Redirection vers le formulaire d'ajout de chambre en passant l'ID de l'hôtel
            header('Location: ../views/entreprise/formulaire_ajouter_chambre.php?hotel=' . urlencode($idHotel));
            exit();
        } else {
            // En cas d'erreur lors de l'ajout de l'hôtel, on redirige vers le formulaire d'ajout d'hôtel avec un message d'erreur
            header('Location: ../views/entreprise/formulaire_ajouter_hotel.php?erreur=ajout');
            exit();
        }
    }
}

// Exemple d'utilisation :
// Vérifie si une soumission POST a été effectuée pour l'ajout d'un nouvel hôtel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_hotel') {
    $hotelController = new HotelController();
    // Récupère les données du formulaire (en les filtrant si nécessaire)
    $nomHotel = $_POST['nom'] ?? '';
    $adresseHotel = $_POST['adresse'] ?? '';
    $telephoneHotel = $_POST['telephone'] ?? '';
    $descriptionHotel = $_POST['description'] ?? '';
    $photoHotel = $_FILES['photo']['name'] ?? ''; // Exemple de récupération de nom de fichier

    $hotelController->ajouterHotel($nomHotel, $adresseHotel, $telephoneHotel, $descriptionHotel, $photoHotel);
}
