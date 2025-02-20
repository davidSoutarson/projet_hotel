<?php
require_once __DIR__ . '/../models/Chambre.php';
require_once __DIR__ . '/../controllers/SessionEntrController.php';

class ChambreController
{
    private $chambreModel;

    public function __construct()
    {
        $this->chambreModel = new Chambre();
    }

    public function ajouterChambre($numero, $prix, $nombre_lits, $description, $photo_chambre, $etat, $id_hotel)
    {
        // Vérification de session entreprise
        if (!SessionEntrController::verifierSession()) {
            header('Location: ../views/entreprise/formulaire_connexion_entr.php?erreur=non_connecte');
            exit();
        }

        // Vérification des champs obligatoires
        if (empty($numero) || empty($prix) || empty($nombre_lits) || empty($etat) || empty($id_hotel)) {
            header("Location: ../views/entreprise/formulaire_ajouter_chambre.php?hotel=$id_hotel&erreur=donnees_manquantes");
            exit();
        }

        // Gestion de l'upload de l'image
        $photoPath = null;
        if (!empty($photo_chambre['name'])) {
            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $extension = pathinfo($photo_chambre['name'], PATHINFO_EXTENSION);
            $photoName = uniqid('chambre_') . '.' . $extension;
            $photoPath = 'uploads/' . $photoName;

            // Vérification de l'upload
            if (!move_uploaded_file($photo_chambre['tmp_name'], __DIR__ . '/../' . $photoPath)) {
                echo "Échec du téléchargement de l'image : " . $photo_chambre['error'];
                exit();
            }
        }

        // Ajout de la chambre en base de données
        $idChambre = $this->chambreModel->ajouterChambre($numero, $prix, $nombre_lits, $description, $photoPath, $etat, $id_hotel);

        // Vérification si l'ajout est réussi
        if ($idChambre) {
            header("Location: ../views/entreprise/liste_chambres.php?hotel=$id_hotel&success=chambres_ajoutees");
        } else {
            header("Location: ../views/entreprise/formulaire_ajouter_chambre.php?hotel=$id_hotel&erreur=echec_enregistrement");
        }
        exit();
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'ajouter_chambre') {
        $chambreController = new ChambreController();

        $numero = trim($_POST['numero'] ?? '');
        $prix = trim($_POST['prix'] ?? '');
        $nombre_lits = trim($_POST['nombre_lits'] ?? '');
        $description = trim($_POST['description_chambre'] ?? '');
        $etat = trim($_POST['etat'] ?? '');
        $id_hotel = trim($_POST['id_hotel'] ?? ''); // ID de l'hôtel auquel appartient la chambre

        // Affiche les données pour le débogage
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        // Appel à la fonction pour ajouter la chambre
        $chambreController->ajouterChambre($numero, $prix, $nombre_lits, $description, $_FILES['photo_chambre'] ?? null, $etat, $id_hotel);
    }
}
