
INSERT INTO regime_utilisateurs (nom, email, password, genre, is_gold) VALUES
('Jean Rakoto', 'jean@gmail.com', '123456', 'Homme', 1),
('Marie Rasoa', 'marie@gmail.com', '123456', 'Femme', 0),
('Paul Randria', 'paul@gmail.com', '123456', 'Homme', 0),
('Lucie Rabe', 'lucie@gmail.com', '123456', 'Femme', 1),
('Eric Andry', 'eric@gmail.com', '123456', 'Homme', 0);


INSERT INTO regime_regimes (nom, duree, prix, variation_poids, pourcentage_viande, pourcentage_poisson, pourcentage_volaille) VALUES
('Perte rapide', 30, 50000, -5.0, 20, 30, 50),
('Fitness', 60, 80000, -8.0, 30, 30, 40),
('Végétarien', 45, 60000, -4.0, 0, 0, 0),
('Musculation', 90, 100000, 5.0, 40, 30, 30),
('Equilibre', 30, 40000, -2.0, 25, 25, 50);

INSERT INTO regime_activites (nom, description, duree) VALUES
('Course', 'Course à pied pour brûler les calories', 30),
('Marche', 'Marche rapide quotidienne', 45),
('Musculation', 'Exercices de renforcement musculaire', 60),
('Yoga', 'Relaxation et souplesse', 40),
('Vélo', 'Cyclisme pour cardio', 50);

INSERT INTO regime_codes (code, montant, used) VALUES
('CODE001', 10000, 0),
('CODE002', 15000, 0),
('CODE003', 20000, 0),
('CODE004', 5000, 0),
('CODE005', 12000, 0),
('CODE006', 8000, 0),
('CODE007', 9000, 0),
('CODE008', 11000, 0),
('CODE009', 13000, 0),
('CODE010', 7000, 0),
('CODE011', 6000, 0),
('CODE012', 14000, 0),
('CODE013', 16000, 0),
('CODE014', 17000, 0),
('CODE015', 18000, 0);

INSERT INTO regime_sante (user_id, taille, poids, imc) VALUES
(1, 1.75, 70, 22.9),
(2, 1.60, 60, 23.4),
(3, 1.80, 85, 26.2),
(4, 1.65, 55, 20.2),
(5, 1.70, 90, 31.1);

INSERT INTO regime_wallet (user_id, solde) VALUES
(1, 50000),
(2, 20000),
(3, 10000),
(4, 80000),
(5, 15000);