INSERT INTO regime_utilisateurs (nom, email, password, genre, is_gold) VALUES
('Jean Rakoto', 'jean@gmail.com', '$2y$12$25IXNwul9Uy61xXHVUcFmOt8dcW3kyHBssEGVOB3Ve6vWgSj6gN0m', 'homme', 1),--123456
('Marie Rasoa', 'marie@gmail.com', '$2y$12$25IXNwul9Uy61xXHVUcFmOt8dcW3kyHBssEGVOB3Ve6vWgSj6gN0m', 'femme', 0),--123456
('Paul Randria', 'paul@gmail.com', '$2y$12$25IXNwul9Uy61xXHVUcFmOt8dcW3kyHBssEGVOB3Ve6vWgSj6gN0m', 'homme', 0),--123456
('Lucie Rabe', 'lucie@gmail.com', '$2y$12$25IXNwul9Uy61xXHVUcFmOt8dcW3kyHBssEGVOB3Ve6vWgSj6gN0m', 'femme', 1),--123456
('Eric Andry', 'eric@gmail.com', '$2y$12$25IXNwul9Uy61xXHVUcFmOt8dcW3kyHBssEGVOB3Ve6vWgSj6gN0m', 'homme', 0);--123456


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

INSERT INTO regime_regime_activites (regime_id, activite_id) VALUES
-- Perte rapide
(1, 1),
(1, 2),
(1, 5),
-- Fitness
(2, 1),
(2, 3),
(2, 5),
-- Végétarien
(3, 2),
(3, 4),
-- Musculation
(4, 3),
-- Equilibre
(5, 2),
(5, 4),
(5, 5);

INSERT INTO regime_recettes (nom, description, type_repas) VALUES
('Bol d avoine aux fruits', 'Flocons d avoine, lait, banane, fruits rouges.', 'petit-dejeuner'),
('Omelette aux légumes', 'Oeufs, tomates, poivrons, herbes.', 'petit-dejeuner'),
('Salade quinoa poulet', 'Quinoa, poulet grillé, concombre, citron.', 'dejeuner'),
('Poisson vapeur légumes', 'Filet de poisson, brocoli, carottes.', 'dejeuner'),
('Soupe légumes verts', 'Courgette, épinards, herbes fraîches.', 'diner'),
('Poulet grillé riz complet', 'Poulet, riz complet, haricots verts.', 'diner'),
('Smoothie protéiné', 'Yaourt, banane, beurre de cacahuète.', 'collation'),
('Salade végétarienne', 'Pois chiches, tomates, avocat.', 'dejeuner'),
('Pâtes complètes sauce tomate', 'Pâtes complètes, tomate, basilic.', 'diner'),
('Bowl fruits secs', 'Amandes, noix, fruits secs, yaourt.', 'collation');

INSERT INTO regime_regime_recettes (regime_id, recette_id, jour) VALUES
-- Perte rapide
(1, 1, 1),
(1, 3, 1),
(1, 5, 1),
(1, 2, 2),
(1, 4, 2),
(1, 5, 2),
(1, 1, 3),
(1, 3, 3),
(1, 5, 3),
-- Fitness
(2, 2, 1),
(2, 3, 1),
(2, 6, 1),
(2, 1, 2),
(2, 4, 2),
(2, 6, 2),
-- Végétarien
(3, 1, 1),
(3, 8, 1),
(3, 9, 1),
(3, 1, 2),
(3, 8, 2),
(3, 9, 2),
-- Musculation
(4, 2, 1),
(4, 3, 1),
(4, 6, 1),
(4, 7, 2),
(4, 3, 2),
(4, 6, 2),
-- Equilibre
(5, 1, 1),
(5, 3, 1),
(5, 5, 1),
(5, 2, 2),
(5, 4, 2),
(5, 5, 2);

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

INSERT INTO regime_sante (user_id, taille, poids) VALUES
(1, 175, 70),
(2, 160, 60),
(3, 180, 85),
(4, 165, 55),
(5, 170, 90);

INSERT INTO regime_wallet (user_id, solde) VALUES
(1, 50000),
(2, 20000),
(3, 10000),
(4, 80000),
(5, 15000);

INSERT INTO regime_regime_achats (user_id, regime_id, prix_paye) VALUES
(1, 1, 50000);
