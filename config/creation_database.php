<?php
try {
    $connexion = new PDO("mysql:host=localhost", "root", "");
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $requete = "CREATE DATABASE IF NOT EXISTS hotel_database CHARACTER SET utf8 COLLATE utf8_general_ci;";
    $connexion->exec($requete);

    $connexion->exec("USE hotel_database;");

    // Tables utilisateurs, entreprises, hotels, chambres,reservations
    $tableUtilisateurs = "CREATE TABLE IF NOT EXISTS utilisateurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        prenom VARCHAR(255) NOT NULL,
        adresse TEXT NOT NULL,
        telephone VARCHAR(20),
        email VARCHAR(255) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(255) NOT NULL
    );";
    $connexion->exec($tableUtilisateurs);

    $tableEntreprises = "CREATE TABLE IF NOT EXISTS entreprises (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        adresse TEXT NOT NULL,
        telephone VARCHAR(20),
        email VARCHAR(255) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(255) NOT NULL
    );";
    $connexion->exec($tableEntreprises);

    $tableHotels = "CREATE TABLE IF NOT EXISTS hotels (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        adresse TEXT NOT NULL,
        telephone VARCHAR(20),
        description_hotel TEXT,
        nombre_chambres INT DEFAULT 0,
        photo_hotel VARCHAR(255),
        id_entreprise INT NOT NULL,
        FOREIGN KEY (id_entreprise) REFERENCES entreprises(id)
    );";
    $connexion->exec($tableHotels);

    $tableChambres = "CREATE TABLE IF NOT EXISTS chambres (
        id INT AUTO_INCREMENT PRIMARY KEY,
        numero INT NOT NULL,
        prix FLOAT NOT NULL,
        nombre_lits INT NOT NULL,
        description_chambre TEXT,
        photo_chambre VARCHAR(255),
        etat ENUM('libre', 'reserve') NOT NULL DEFAULT 'libre',
        id_hotel INT NOT NULL,
        FOREIGN KEY (id_hotel) REFERENCES hotels(id)
    );";
    $connexion->exec($tableChambres);

    $tableReservations = "CREATE TABLE IF NOT EXISTS reservations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date_debut DATE NOT NULL,
        date_fin DATE NOT NULL,
        id_utilisateur INT NOT NULL,
        id_chambre INT NOT NULL,
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id),
        FOREIGN KEY (id_chambre) REFERENCES chambres(id)
    );";
    $connexion->exec($tableReservations);
} catch (PDOException $exception) {
    echo "Erreur lors de la création de la base de données : " . $exception->getMessage();
}

$creatDBTeste = "appel du fichier config/creation_database.php 02 OK";
