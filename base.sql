DROP DATABASE IF EXISTS regime;
CREATE DATABASE IF NOT EXISTS regime;
USE regime;

CREATE TABLE regime_utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
    genre VARCHAR(10),
    is_gold BOOLEAN DEFAULT 0,
    objectif VARCHAR(30) DEFAULT NULL
);

CREATE TABLE regime_sante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    taille FLOAT,
    poids FLOAT,
    imc FLOAT GENERATED ALWAYS AS (ROUND(poids / POW(taille / 100, 2), 2)) STORED,
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

CREATE TABLE regime_regime_activites (
    regime_id INT NOT NULL,
    activite_id INT NOT NULL,
    PRIMARY KEY (regime_id, activite_id),
    FOREIGN KEY (regime_id) REFERENCES regime_regimes(id) ON DELETE CASCADE,
    FOREIGN KEY (activite_id) REFERENCES regime_activites(id) ON DELETE CASCADE
);

CREATE TABLE regime_recettes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(120),
    description TEXT,
    type_repas VARCHAR(30)
);

CREATE TABLE regime_regime_recettes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    regime_id INT NOT NULL,
    recette_id INT NOT NULL,
    jour INT NOT NULL,
    FOREIGN KEY (regime_id) REFERENCES regime_regimes(id) ON DELETE CASCADE,
    FOREIGN KEY (recette_id) REFERENCES regime_recettes(id) ON DELETE CASCADE
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

CREATE TABLE regime_regime_achats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    regime_id INT NOT NULL,
    prix_paye FLOAT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES regime_utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regime_regimes(id) ON DELETE CASCADE
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
