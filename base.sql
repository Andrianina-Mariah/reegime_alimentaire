CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
    genre VARCHAR(10),
    is_gold BOOLEAN DEFAULT 0
);

CREATE TABLE health (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    taille FLOAT,
    poids FLOAT,
    imc FLOAT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    duree INT,
    prix FLOAT,
    variation_poids FLOAT,
    pourcentage_viande INT,
    pourcentage_poisson INT,
    pourcentage_volaille INT
);

CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    description TEXT,
    duree INT
);

CREATE TABLE codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50),
    montant FLOAT,
    used BOOLEAN DEFAULT 0
);

CREATE TABLE wallet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    solde FLOAT DEFAULT 0
);