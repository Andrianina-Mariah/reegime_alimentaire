CREATE DATABASE IF NOT EXISTS regime;
USE regime;

CREATE TABLE regime_utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
    genre VARCHAR(10),
    is_gold BOOLEAN DEFAULT 0
);

CREATE TABLE regime_admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);

INSERT INTO regime_admins (nom, email, password)
VALUES (
    'Administrateur',
    'admin@regime.local',
    '$2y$12$fJTt6mN8HEp6wo6FIBZao.XQKbg6y2UEqC/n4YyXx2IJMeWVabsvO'
);

CREATE TABLE regime_sante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    taille FLOAT,
    poids FLOAT,
    imc FLOAT,
    FOREIGN KEY (user_id) REFERENCES regime_utilisateurs(id)
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

CREATE TABLE regime_activites (
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

CREATE TABLE regime_wallet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    solde FLOAT DEFAULT 0
);
