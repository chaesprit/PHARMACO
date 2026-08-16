-- Schéma de la base de données — Système de Gestion de Pharmacie
-- 3 entités officielles (utilisateur, medicament, ordonnance) + tables
-- de jointure/métier justifiées par une fonctionnalité explicite du
-- cahier des charges (voir README pour le détail des choix).

CREATE DATABASE IF NOT EXISTS gestion_pharmacie
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE gestion_pharmacie;

-- Entité officielle : Utilisateur (3 rôles : responsable, pharmacien, client)
CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom            VARCHAR(100) NOT NULL,
    prenom         VARCHAR(100) NOT NULL,
    email          VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe   VARCHAR(255) NOT NULL,
    role           ENUM('responsable', 'pharmacien', 'client') NOT NULL,
    telephone      VARCHAR(20),
    date_creation  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Entité officielle : Médicament
-- seuil_critique est paramétrable par médicament plutôt qu'une valeur
-- codée en dur, pour rester conforme à la section 14 du cahier des charges.
CREATE TABLE medicament (
    id_medicament  INT AUTO_INCREMENT PRIMARY KEY,
    nom            VARCHAR(150) NOT NULL,
    description    TEXT,
    categorie      VARCHAR(100),
    fabricant      VARCHAR(150),
    prix           DECIMAL(10,2) NOT NULL DEFAULT 0,
    quantite_stock INT NOT NULL DEFAULT 0,
    seuil_critique INT NOT NULL DEFAULT 10,
    date_ajout     DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE INDEX idx_medicament_recherche ON medicament(nom, categorie, fabricant);

-- Entité officielle : Ordonnance
-- Une demande de renouvellement est une ordonnance avec est_renouvellement=TRUE
-- qui référence l'ordonnance d'origine, pour réutiliser le même cycle de statut.
CREATE TABLE ordonnance (
    id_ordonnance            INT AUTO_INCREMENT PRIMARY KEY,
    id_client                INT NOT NULL,
    id_pharmacien_validateur INT NULL,
    date_soumission          DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_validation          DATETIME NULL,
    statut                   ENUM('en_attente', 'validee', 'rejetee') NOT NULL DEFAULT 'en_attente',
    est_renouvellement       BOOLEAN NOT NULL DEFAULT FALSE,
    id_ordonnance_originale  INT NULL,
    commentaire              TEXT,
    FOREIGN KEY (id_client) REFERENCES utilisateur(id_utilisateur),
    FOREIGN KEY (id_pharmacien_validateur) REFERENCES utilisateur(id_utilisateur),
    FOREIGN KEY (id_ordonnance_originale) REFERENCES ordonnance(id_ordonnance)
) ENGINE=InnoDB;

-- Table de jointure : une ordonnance contient un ou plusieurs médicaments
CREATE TABLE ordonnance_medicament (
    id_ordonnance      INT NOT NULL,
    id_medicament       INT NOT NULL,
    quantite_prescrite  INT NOT NULL DEFAULT 1,
    posologie           VARCHAR(255),
    PRIMARY KEY (id_ordonnance, id_medicament),
    FOREIGN KEY (id_ordonnance) REFERENCES ordonnance(id_ordonnance) ON DELETE CASCADE,
    FOREIGN KEY (id_medicament) REFERENCES medicament(id_medicament)
) ENGINE=InnoDB;

-- Table métier justifiée : interactions médicamenteuses (section 18 du
-- cahier des charges — enregistrées par le Pharmacien, consultées par le Client)
CREATE TABLE interaction_medicamenteuse (
    id_interaction          INT AUTO_INCREMENT PRIMARY KEY,
    id_medicament_1         INT NOT NULL,
    id_medicament_2         INT NOT NULL,
    description_interaction TEXT NOT NULL,
    niveau_gravite          ENUM('faible', 'moderee', 'elevee') NOT NULL DEFAULT 'moderee',
    id_pharmacien           INT NOT NULL,
    date_enregistrement     DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_medicament_1) REFERENCES medicament(id_medicament),
    FOREIGN KEY (id_medicament_2) REFERENCES medicament(id_medicament),
    FOREIGN KEY (id_pharmacien) REFERENCES utilisateur(id_utilisateur)
) ENGINE=InnoDB;

-- Table métier justifiée : expéditions (section 20 du cahier des charges —
-- rapport d'expéditions consulté par le Responsable Pharmacie)
CREATE TABLE expedition (
    id_expedition   INT AUTO_INCREMENT PRIMARY KEY,
    id_medicament   INT NOT NULL,
    quantite        INT NOT NULL,
    fournisseur     VARCHAR(150),
    date_expedition DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut          ENUM('en_cours', 'livree', 'annulee') NOT NULL DEFAULT 'en_cours',
    FOREIGN KEY (id_medicament) REFERENCES medicament(id_medicament)
) ENGINE=InnoDB;
