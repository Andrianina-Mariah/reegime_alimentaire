-- 1) Créer la base (si elle n'existe pas)
CREATE DATABASE IF NOT EXISTS ci4
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

-- 2) Utiliser la base
USE ci4;

-- 3) Créer la table produits
CREATE TABLE IF NOT EXISTS produits (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nom VARCHAR(150) NOT NULL,
  description TEXT NULL,
  prix DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4) Insérer des données d'exemple
INSERT INTO produits (nom, description, prix, created_at, updated_at) VALUES
('Clavier mécanique', 'Clavier AZERTY rétroéclairé avec switches tactiles.', 79.90, NOW(), NOW()),
('Souris ergonomique', 'Souris sans fil pensée pour les longues sessions de travail.', 34.50, NOW(), NOW()),
('Écran 27 pouces', 'Écran IPS Full HD pour le bureau et le multimédia.', 189.99, NOW(), NOW());