# 🏨 Projet Hôtel

Application web de **réservation d'hôtels** en PHP (sans framework), avec une architecture **MVC** et une base **MySQL/MariaDB** relationnelle.

> Projet réalisé pendant la formation **Intégrateur Développeur Web et Mobile** (ESECAD).
> Deux rôles : **Entreprise** (qui gère hôtels et chambres) et **Utilisateur** (qui réserve).

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)
![MVC](https://img.shields.io/badge/Architecture-MVC-2EA44F?style=flat)

---

## ✅ Fonctionnalités

**Côté Entreprise (gestionnaire)**
- Inscription / connexion
- Ajout d'hôtels (nom, adresse, ville + code postal, description, photo)
- Ajout de **plusieurs chambres en une fois** (numéro, prix, nombre de lits, état, photo)
- Upload d'images (hôtels et chambres)

**Côté Utilisateur (client)**
- Inscription / connexion
- Liste des hôtels (avec **prix min / max** par hôtel)
- Sélection d'un hôtel → affichage de **ses chambres**
- **Réservation** d'une chambre avec dates (arrivée / départ)
- Page de **confirmation** et page d'**erreur** avec message lisible

**Transverse**
- Journal des réservations (`log/reservation.log`)
- Données d'exemple insérées au premier lancement

---

## 🔒 Sécurité (déjà implémentée)

- **PDO + prepared statements** sur toutes les requêtes (protection contre l'injection SQL)
- **Hachage des mots de passe** avec `password_hash()` (`PASSWORD_BCRYPT`)
- **2 triggers SQL** qui **empêchent la double réservation** d'une même chambre sur des dates qui se chevauchent :
  - `avant_insertion_reservation` (BEFORE INSERT)
  - `avant_mise_a_jour_reservation` (BEFORE UPDATE)
- **Anti-usurpation** : à la réservation, l'ID utilisateur envoyé par le formulaire **doit correspondre à la session**
- **Validation des entrées** : dates au format `AAAA-MM-JJ`, dates cohérentes (arrivée < départ, pas dans le passé), IDs entiers, champs requis
- **Uploads encadrés** : liste blanche d'extensions (`jpg/jpeg/png/gif`), taille max **5 Mo**, renommage unique (`uniqid`)
- **Sessions dédiées** utilisateur / entreprise + vérification de session avant les actions sensibles
- **Journalisation** des actions et erreurs

---

## 🗄️ Base de données (`hotel_database`)

6 tables reliées par **clés étrangères (`ON DELETE CASCADE`)** :

| Table | Rôle |
|---|---|
| `utilisateurs` | Clients qui réservent |
| `entreprises` | Gestionnaires des hôtels |
| `villes_francais` | Villes (nom + code postal) |
| `hotels` | Hôtels (liés à une entreprise + une ville) |
| `chambres` | Chambres (liées à un hôtel, état `libre`/`reserve`) |
| `reservations` | Réservations — avec **colonne générée** `duree = DATEDIFF(date_fin, date_debut)` |

Plus les **2 triggers** anti double-réservation (voir section Sécurité).

---

## 🧰 Stack

- **PHP** (procédural, architecture MVC, sans framework)
- **MySQL / MariaDB** (PDO)
- **HTML / CSS**

---

## 📂 Architecture

```
projet_hotel/
├── config/
│   ├── config.php            # environnement (dev / prod) + URLs
│   ├── configuration.php     # classe Database (PDO) + constantes de chemins
│   └── creation_database.php # création de la BDD, des tables et des triggers
├── controllers/              # logique applicative (un contrôleur par entité/rôle)
│   ├── HotelController.php
│   ├── ChambreController.php
│   ├── ReservationController.php
│   ├── UtilisateurController.php
│   ├── EntrepriseController.php
│   ├── SessionUtilController.php
│   ├── SessionEntrController.php
│   └── connexion*Controller.php
├── models/                   # accès aux données + logique métier
│   ├── Hotel.php · Chambre.php · Reservation.php
│   ├── Utilisateur.php · Entreprise.php · Ville.php
│   └── Insert_data.php       # données d'exemple
├── views/                    # HTML (entreprise/, hotel/, utilisateur/, header, footer)
├── css/style.css
├── images/                   # photos par défaut
├── uploads/                  # photos téléversées (générées à l'exécution)
├── log/reservation.log
└── index.php                 # point d'entrée (liste des hôtels)
```

---

## 🚀 Installation (Laragon / XAMPP)

1. **Cloner** le projet :
   ```bash
   git clone https://github.com/davidSoutarson/projet_hotel.git
   ```
2. **Placer** le dossier dans la racine du serveur web :
   - Laragon : `C:\laragon\www\projet_hotel`
   - XAMPP : `C:\xampp\htdocs\projet_hotel`
3. **Configurer la BDD** dans `config/configuration.php` (par défaut : hôte `localhost`, utilisateur `root`, mot de passe vide — c'est la config Laragon/XAMPP par défaut).
4. **Ouvrir** dans le navigateur : `http://localhost/projet_hotel/`
   → au premier chargement, la base `hotel_database`, ses **tables**, ses **triggers** et les **données d'exemple** sont créés automatiquement.
5. **Compte de démonstration (entreprise)** :
   - **Email** : `contact@exemple.com`
   - **Mot de passe** : `motdepasse`
   - (Un hôtel et une chambre d'exemple sont déjà présents.)

> ℹ️ Le projet suppose un serveur PHP + MySQL/MariaDB (Laragon, XAMPP, MAMP…).

---

## 🗺️ Roadmap (à venir)

- [ ] **CSRF** sur les formulaires (token)
- [ ] **`session_regenerate_id(true)`** à la connexion (anti session fixation)
- [ ] **Validation de la ville** contre la base officielle (data.gouv.fr)
- [ ] **Design responsive** (mobile friendly)
- [ ] **Router / URLs propres** (`index.php?page=…` ou `.htaccess`)
- [ ] **API REST** pour la gestion des réservations
- [ ] **JavaScript dynamique** (AJAX / `fetch`)
- [ ] **Autoloader Composer** (PSR-4) + **`.gitignore`** + **tests**

---

## 👤 Auteur

**David Soutarson** — Intégrateur Développeur Web et Mobile (formation ESECAD)
· GitHub : [davidSoutarson](https://github.com/davidSoutarson)
· Portfolio : https://devweb-david-s.github.io/
