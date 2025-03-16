<?php
require_once __DIR__ . '/../config/configuration.php';

class InsertData
{
    private $connexion;

    public function __construct()
    {
        $database = new Database();
        $this->connexion = $database->obtenirConnexion();
    }

    // Permet d'accéder à la connexion pour des requêtes externes
    public function getConnexion()
    {
        return $this->connexion;
    }

    public function insererDonneesExemple()
    {
        try {
            // Données à insérer
            $idEntreprise = 1;
            $idVille = 2;

            // Définir le mot de passe en clair et le hasher avec BCRYPT
            $motdepasseBrut = "motdepasse";
            $motdepasseHashe = password_hash($motdepasseBrut, PASSWORD_BCRYPT);

            // Insertion d'une entreprise d'exemple avec le mot de passe hashé
            $sqlEntreprise = "INSERT INTO entreprises (nom, adresse, telephone, email, mot_de_passe)
                      VALUES ('Entreprise Exemple', '123 Rue Exemple, Paris', '0123456789', 'contact@exemple.com', :motdepasse)
                      ON DUPLICATE KEY UPDATE
                      nom = VALUES(nom),
                      adresse = VALUES(adresse),
                      telephone = VALUES(telephone),
                      mot_de_passe = VALUES(mot_de_passe)";

            $stmtEntreprise = $this->connexion->prepare($sqlEntreprise);
            $stmtEntreprise->bindParam(':motdepasse', $motdepasseHashe);
            $stmtEntreprise->execute();

            // Insertion d'une ville d'exemple
            $sqlVille = "INSERT INTO villes_francais (nom_ville, code_postal_hotel)
                 VALUES ('Paris', 75000)
                 ON DUPLICATE KEY UPDATE
                 nom_ville = VALUES(nom_ville),
                 code_postal_hotel = VALUES(code_postal_hotel)";
            $this->connexion->exec($sqlVille);

            // Récupérer l'ID de l'entreprise insérée
            $stmt = $this->connexion->query("SELECT id FROM entreprises WHERE email = 'contact@exemple.com'");
            $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);
            $idEntreprise = $entreprise['id'];

            // Récupérer l'ID de la ville insérée
            $stmt = $this->connexion->query("SELECT id FROM villes_francais WHERE nom_ville = 'Paris'");
            $ville = $stmt->fetch(PDO::FETCH_ASSOC);
            $idVille = $ville['id'];

            // Insertion d'un hôtel d'exemple avec la photo par défaut
            $sqlHotel = "INSERT INTO hotels (
                    hotel_nom, hotel_adresse, telephone, description_hotel,
                    nombre_chambres, photo_hotel, id_entreprise, id_ville
                 ) 
                 VALUES (
                    'Hotel Exemple', '45 Boulevard Exemple, Paris', '0147258369',
                    'Un hôtel confortable au cœur de Paris', 20, 'images/default_hotel.jpg',
                    :idEntreprise, :idVille
                 )
                 ON DUPLICATE KEY UPDATE
                 hotel_nom = VALUES(hotel_nom),
                 hotel_adresse = VALUES(hotel_adresse),
                 telephone = VALUES(telephone),
                 description_hotel = VALUES(description_hotel),
                 nombre_chambres = VALUES(nombre_chambres),
                 photo_hotel = VALUES(photo_hotel),
                 id_entreprise = VALUES(id_entreprise),
                 id_ville = VALUES(id_ville)";

            $stmtHotel = $this->connexion->prepare($sqlHotel);
            $stmtHotel->bindParam(':idEntreprise', $idEntreprise, PDO::PARAM_INT);
            $stmtHotel->bindParam(':idVille', $idVille, PDO::PARAM_INT);
            $stmtHotel->execute();

            // Récupérer l'ID de l'hôtel inséré
            $stmt = $this->connexion->query("SELECT id FROM hotels WHERE hotel_nom = 'Hotel Exemple'");
            $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
            $idHotel = $hotel['id'];

            // Insertion d'une chambre d'exemple avec la photo par défaut
            $sqlChambre = "INSERT INTO chambres (
                       numero, prix, nombre_lits, description_chambre,
                       photo_chambre, etat, id_hotel
                   )
                   VALUES (
                       01, 120.50, 2, 'Chambre double avec vue sur la ville',
                       'images/default_chambre.jpg', 'libre', :idHotel
                   )
                   ON DUPLICATE KEY UPDATE
                   numero = VALUES(numero), 
                   prix = VALUES(prix), 
                   nombre_lits = VALUES(nombre_lits), 
                   description_chambre = VALUES(description_chambre), 
                   photo_chambre = VALUES(photo_chambre), 
                   etat = VALUES(etat), 
                   id_hotel = VALUES(id_hotel)";

            $stmtChambre = $this->connexion->prepare($sqlChambre);
            $stmtChambre->bindParam(':idHotel', $idHotel, PDO::PARAM_INT);
            $stmtChambre->execute();

            echo "Données d'exemple insérées avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion des données : " . $e->getMessage();
        }
    }
}
