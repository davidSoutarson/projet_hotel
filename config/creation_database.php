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

    //table des villes_francais
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
        FOREIGN KEY (id_ville') REFERENCES villes(id) ON DELETE CASCADE
    );");

    // Table des chambres
    $connexion->exec("CREATE TABLE IF NOT EXISTS chambres (
        id INT AUTO_INCREMENT PRIMARY KEY,
        numero INT NOT NULL,
        prix FLOAT NOT NULL,
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
        duree_de_reservation INT GENERATED ALWAYS AS (DATEDIFF(date_fin, date_debut)) STORED,
        id_utilisateur INT NOT NULL,
        id_chambre INT NOT NULL,
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
        FOREIGN KEY (id_chambre) REFERENCES chambres(id) ON DELETE CASCADE
    );");

    /* Ajout du trigger pour empêcher les conflits de réservation */

    $trigger = "CREATE TRIGGER avant_insertion_reservation
                BEFORE INSERT ON reservations
                FOR EACH ROW
                BEGIN
                    DECLARE room_count INT;
                    -- Vérifier si la chambre est déjà réservée pour la période demandée
                    SELECT COUNT(*) INTO room_count
                    FROM reservations
                    WHERE id_chambre = NEW.id_chambre
                    AND (
                        (NEW.date_debut BETWEEN date_debut AND date_fin) 
                        OR (NEW.date_fin BETWEEN date_debut AND date_fin)
                        OR (date_debut BETWEEN NEW.date_debut AND NEW.date_fin)
                    );

                    -- Si la chambre est occupée, empêcher l'insertion
                    IF room_count > 0 THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Erreur : Cette chambre est déjà réservée pour cette période.';
                    END IF;
                END;";

    // Supprimer le trigger si existe déjà
    $connexion->exec("DROP TRIGGER IF EXISTS avant_insertion_reservation;");

    // Créer le trigger
    $connexion->exec($trigger);

    /* dans MySQL http://localhost/phpmyadmin/index.php?route=/database/sql&db=hotel_database 
    pour verifier si le triger et bien créé ecrire cette comende [SHOW TRIGGERS FROM hotel_database;] sans[] "  */

    echo "Base de données et tables créées avec succès.  ainsi que le trigger.";
} catch (PDOException $exception) {
    echo "Erreur lors de la création de la base de données : " . $exception->getMessage();
}
