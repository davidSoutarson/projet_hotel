<?php
require_once __DIR__ . '/../models/Chambre.php';
require_once __DIR__ . '/../controllers/SessionEntrController.php';

// Définition de la classe ChambreController
class ChambreController
{
    private $chambreModel;

    public function __construct()
    {
        $this->chambreModel = new Chambre();
    }

    /**
     * Ajoute une chambre en base de données.
     * Cette méthode traite une seule chambre.
     */
    public function ajouterChambre($numero, $prix, $nombre_lits, $description, $photo_chambre, $etat, $id_hotel)
    {
        // Vérification de la session entreprise
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
        if (!empty($photo_chambre) && !empty($photo_chambre['tmp_name'])) {
            // Définir le chemin absolu du dossier "uploads"
            $uploadDir = __DIR__ . '/../uploads/';

            // Vérifier et créer le dossier s'il n'existe pas
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Obtenir l'extension du fichier en minuscule
            $extension = strtolower(pathinfo($photo_chambre['name'], PATHINFO_EXTENSION));

            // Vérification de l'extension et de la taille du fichier
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $maxFileSize = 5 * 1024 * 1024; // 5 Mo

            if (!in_array($extension, $allowedExtensions)) {
                header('Location: ../views/entreprise/formulaire_ajouter_chambre.php?erreur=extension_invalide');
                exit();
            }

            if ($photo_chambre['size'] > $maxFileSize) {
                header('Location: ../views/entreprise/formulaire_ajouter_chambre.php?erreur=fichier_trop_grand');
                exit();
            }

            // Générer un nom unique pour l'image
            $photoName = uniqid('chambre_') . '.' . $extension;
            $uploadFile = $uploadDir . $photoName;  // Chemin complet de l'image

            // Déplacer le fichier téléchargé dans le dossier "uploads"
            if (move_uploaded_file($photo_chambre['tmp_name'], $uploadFile)) {
                // Sauvegarder uniquement le chemin relatif dans la base de données
                $photoPath = 'uploads/' . $photoName;
            } else {
                header('Location: ../views/entreprise/formulaire_ajouter_chambre.php?erreur=echec_upload');
                exit();
            }
        }

        // Ajout de la chambre en base de données via le modèle
        $result = $this->chambreModel->ajouterChambre($numero, $prix, $nombre_lits, $description, $photoPath, $etat, $id_hotel);

        return $result;
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pour faciliter le débogage (à retirer en production)
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    if (isset($_POST['action']) && $_POST['action'] === 'ajouter_chambre') {
        $chambreController = new ChambreController();

        // Récupérer et nettoyer les données (chaque champ est un tableau)
        $numeros       = $_POST['numero'] ?? [];
        $prixArr       = $_POST['prix'] ?? [];
        $nombreLitsArr = $_POST['nombre_lits'] ?? [];
        $descriptions  = $_POST['description_chambre'] ?? [];
        $etats         = $_POST['etat'] ?? [];
        $id_hotel      = trim($_POST['id_hotel'] ?? '');

        // Réorganiser le tableau $_FILES pour la photo
        $photosArr = $_FILES['photo_chambre'] ?? null;

        // Nombre de chambres à ajouter
        $nbChambres = count($numeros);

        // Itérer sur chaque chambre
        for ($i = 0; $i < $nbChambres; $i++) {
            $numero = trim($numeros[$i]);
            $prix = trim($prixArr[$i]);
            $nombre_lits = trim($nombreLitsArr[$i]);
            $description = trim($descriptions[$i]);
            $etat = trim($etats[$i]);

            // Préparer la photo pour cette chambre (si fournie)
            $photo = null;
            if ($photosArr && isset($photosArr['name'][$i]) && !empty($photosArr['name'][$i])) {
                $photo = [
                    'name'     => $photosArr['name'][$i],
                    'type'     => $photosArr['type'][$i],
                    'tmp_name' => $photosArr['tmp_name'][$i],
                    'error'    => $photosArr['error'][$i],
                    'size'     => $photosArr['size'][$i]
                ];
            }

            // Appel à la méthode pour ajouter la chambre
            $ajout = $chambreController->ajouterChambre($numero, $prix, $nombre_lits, $description, $photo, $etat, $id_hotel);

            if (!$ajout) {
                // Vous pouvez choisir d'arrêter la boucle ou de continuer et collecter les erreurs
                echo "Échec de l'ajout de la chambre numéro " . ($i + 1);
            }
        }
        // Redirection après traitement de toutes les chambres (optionnel)
        header("Location: ../views/entreprise/liste_chambres.php?hotel=$id_hotel&success=chambres_ajoutees");
        exit();
    }
}
