<?php
try {
    /* Connexion au serveur MySQL */
    $connexion = new PDO("mysql:host=localhost", "root", "");
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* Création de la base de données */
    $connexion->exec("CREATE DATABASE IF NOT EXISTS hotel_database CHARACTER SET utf8 COLLATE utf8_general_ci;");
    $connexion->exec("USE hotel_database;");

    /* Création des tables */

    // Table des utilisateurs
    $connexion->exec("CREATE TABLE IF NOT EXISTS utilisateurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        prenom VARCHAR(255) NOT NULL,
        adresse TEXT NOT NULL,
        telephone VARCHAR(20),
        email VARCHAR(255) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(255) NOT NULL
    );");

    // Table des entreprises
    $connexion->exec("CREATE TABLE IF NOT EXISTS entreprises (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        adresse TEXT NOT NULL,
        telephone VARCHAR(20),
        email VARCHAR(255) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(255) NOT NULL
    );");

    // Table des villes françaises
    $connexion->exec("CREATE TABLE IF NOT EXISTS villes_francais (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom_ville VARCHAR(255) NOT NULL,
        code_postal_hotel INT NOT NULL
    );");

    // Table des hôtels
    $connexion->exec("CREATE TABLE IF NOT EXISTS hotels (
        id INT AUTO_INCREMENT PRIMARY KEY,
        hotel_nom VARCHAR(255) NOT NULL,
        hotel_adresse TEXT NOT NULL,
        telephone VARCHAR(20),
        description_hotel TEXT,
        nombre_chambres INT DEFAULT 0,
        photo_hotel VARCHAR(255),
        id_entreprise INT NOT NULL,
        id_ville INT NOT NULL,
        FOREIGN KEY (id_entreprise) REFERENCES entreprises(id) ON DELETE CASCADE,
        FOREIGN KEY (id_ville) REFERENCES villes_francais(id) ON DELETE CASCADE
    );");

    // Table des chambres
    $connexion->exec("CREATE TABLE IF NOT EXISTS chambres (
        id INT AUTO_INCREMENT PRIMARY KEY,
        numero INT NOT NULL,
        prix DECIMAL(10, 2) NOT NULL,
        nombre_lits INT NOT NULL,
        description_chambre TEXT,
        photo_chambre VARCHAR(255),
        etat ENUM('libre', 'reserve') NOT NULL DEFAULT 'libre',
        id_hotel INT NOT NULL,
        FOREIGN KEY (id_hotel) REFERENCES hotels(id) ON DELETE CASCADE
    );");

    // Table des réservations
    $connexion->exec("CREATE TABLE IF NOT EXISTS reservations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date_debut DATE NOT NULL,
        date_fin DATE NOT NULL,
        id_utilisateur INT NOT NULL,
        id_chambre INT NOT NULL,
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
        FOREIGN KEY (id_chambre) REFERENCES chambres(id) ON DELETE CASCADE
    );");

    /*    // Ajout d'index pour améliorer les performances
    $connexion->exec("CREATE INDEX idx_hotel_ville ON hotels(id_ville);");
    $connexion->exec("CREATE INDEX idx_chambre_hotel ON chambres(id_hotel);");
    $connexion->exec("CREATE INDEX idx_reservation_utilisateur ON reservations(id_utilisateur);"); */

    /* Gestion des triggers */
    // Supprimer les triggers existants si nécessaire
    $connexion->exec("DROP TRIGGER IF EXISTS avant_insertion_reservation;");
    $connexion->exec("DROP TRIGGER IF EXISTS avant_mise_a_jour_reservation;");

    // Trigger pour empêcher les conflits de réservation lors des INSERTIONS
    $triggerInsertion = "CREATE TRIGGER avant_insertion_reservation
                        BEFORE INSERT ON reservations
                        FOR EACH ROW
                        BEGIN
                            DECLARE room_count INT;

                            SELECT COUNT(*) INTO room_count
                            FROM reservations
                            WHERE id_chambre = NEW.id_chambre
                            AND (
                                (NEW.date_debut < date_fin AND NEW.date_fin > date_debut)
                            );

                            IF room_count > 0 THEN
                                SIGNAL SQLSTATE '45000'
                                SET MESSAGE_TEXT = 'Erreur : Cette chambre est déjà réservée pour cette période.';
                            END IF;
                        END;";
    $connexion->exec($triggerInsertion);

    // Trigger pour empêcher les conflits de réservation lors des MISES À JOUR
    $triggerMiseAJour = "CREATE TRIGGER avant_mise_a_jour_reservation
                        BEFORE UPDATE ON reservations
                        FOR EACH ROW
                        BEGIN
                            DECLARE room_count INT;

                            SELECT COUNT(*) INTO room_count
                            FROM reservations
                            WHERE id_chambre = NEW.id_chambre
                            AND id != NEW.id  -- Évite de vérifier la réservation elle-même
                            AND (
                                (NEW.date_debut < date_fin AND NEW.date_fin > date_debut)
                            );

                            IF room_count > 0 THEN
                                SIGNAL SQLSTATE '45000'
                                SET MESSAGE_TEXT = 'Erreur : Conflit de réservation lors de la mise à jour.';
                            END IF;
                        END;";
    $connexion->exec($triggerMiseAJour);

    echo "Base de données et tables créées avec succès, ainsi que les triggers.";
} catch (PDOException $exception) {
    echo "Erreur lors de l'exécution : " . $exception->getMessage() . "<br>";
    echo "Fichier : " . $exception->getFile() . "<br>";
    echo "Ligne : " . $exception->getLine();
}
