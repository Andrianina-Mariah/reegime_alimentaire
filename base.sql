
CREATE DATABASE IF NOT EXISTS regime;
USE regime;
CREATE TABLE regime_utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
    genre VARCHAR(10),
    is_gold BOOLEAN DEFAULT 0
);

CREATE TABLE regime_sante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    taille FLOAT,
    poids FLOAT,
    imc FLOAT,
    FOREIGN KEY (utilisateur_id) REFERENCES regime_utilisateur(id)
);

CREATE TABLE regime_regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    duree INT,
    prix FLOAT,
    variation_poids FLOAT,
    pourcentage_viande INT,
    pourcentage_poisson INT,
    pourcentage_volaille INT
);

CREATE TABLE regime_activite (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    description TEXT,
    duree INT
);

CREATE TABLE regime_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50),
    montant FLOAT,
    used BOOLEAN DEFAULT 0
);

CREATE TABLE regime_argent (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    solde FLOAT DEFAULT 0,
    FOREIGN KEY (utilisateur_id) REFERENCES regime_utilisateur(id)
);