-- =========================================================
-- GAME X DATABASE - CLEAN SEED DATA
-- =========================================================

USE game_x;


-- =========================================================
-- 1. USERS
-- =========================================================

INSERT INTO users
(username, email, password, role, terms_accepted, status, avatar)
VALUES

-- Admin
-- Password: Admin123 (already hashed with PHP password_hash())
('admin', 'admin@gamex.com', '$2y$10$JzXwrtzjrl1wGUefMomIcOwKLECbdB48bafhPnck/lrRJkaaTMQKi', 'admin', TRUE, 'active', 'src/Images/avatars/blaze.png'),

('mohamed2', 'mohamed2@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/ghost.png'),
('youssef3', 'youssef3@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/neox.png'),
('omar4', 'omar4@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/nightwolf.png'),
('ali5', 'ali5@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/phantom.png'),
('sara6', 'sara6@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/ravenx.png'),
('nour7', 'nour7@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/shadowking.png'),
('mona8', 'mona8@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/vortex.png'),
('laila9', 'laila9@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/striker.png'),
('hana10', 'hana10@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/reaper.png'),
('karim11', 'karim11@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/samurai.png'),
('tarek12', 'tarek12@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/warlord.png'),
('hesham13', 'hesham13@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/paladin.png'),
('mostafa14', 'mostafa14@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/luna.png'),
('amr15', 'amr15@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/viper.png'),
('dina16', 'dina16@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/sentinel.png'),
('rana17', 'rana17@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/punk.png'),
('yara18', 'yara18@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/nyx.png'),
('salma19', 'salma19@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/echo.png'),
('farah20', 'farah20@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', 'src/Images/avatars/cypher.png'),
('ziad21', 'ziad21@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', NULL),
('adham22', 'adham22@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', NULL),
('sherif23', 'sherif23@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', NULL),
('nada24', 'nada24@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'active', NULL),
('rawan25', 'rawan25@gamex.com', '$2y$10$d7yKifXsF2qjKUezIC3z3ugQo4A9MnvLU4NGDuT9XRSzdggXbwi4C', 'user', TRUE, 'blocked', NULL);


-- =========================================================
-- 2. CATEGORIES
-- =========================================================

INSERT INTO categories (name)
VALUES
('Action'),
('Adventure'),
('RPG'),
('Shooter'),
('Racing'),
('Strategy');


-- =========================================================
-- 3. PLATFORMS
-- =========================================================

INSERT INTO platforms (name)
VALUES
('PC'),
('Linux');


-- =========================================================
-- 4. GAMES
-- stock removed
-- =========================================================

INSERT INTO games
(title, developer, publisher, description, price, old_price, discount,
 image, release_date, rating, total_reviews, status)
VALUES

('Dragon Ball: Sparking! Zero',
 'Spike Chunsoft',
 'Bandai Namco',
 'Dragon Ball: Sparking! Zero is an acclaimed title from Spike Chunsoft, delivering an unforgettable gaming experience.',
 51.96, 68.08, 23.68,
 'src/Images/games/dragon-ball-sparking-zero.png',
 '2021-04-05', 3.65, 4517, 'active'),

('Ghostrunner 2',
 'One More Level',
 '505 Games',
 'Ghostrunner 2 is an acclaimed title from One More Level, delivering an unforgettable gaming experience.',
 24.34, NULL, 0.00,
 'src/Images/games/ghostrunner-2.png',
 '2018-01-03', 3.85, 4981, 'active'),

('Lords of the Fallen',
 'HEXWORKS',
 'CI Games',
 'Lords of the Fallen is an acclaimed title from HEXWORKS, delivering an unforgettable gaming experience.',
 21.32, 37.06, 42.47,
 'src/Images/games/lords-of-the-fallen.png',
 '2023-10-13', 4.38, 103, 'active'),

('Armored Core VI: Fires of Rubicon',
 'FromSoftware',
 'Bandai Namco',
 'Armored Core VI: Fires of Rubicon is an acclaimed title from FromSoftware, delivering an unforgettable gaming experience.',
 57.93, 73.40, 21.08,
 'src/Images/games/armored-core-6-fires-of-rubicon.png',
 '2023-08-25', 4.94, 2807, 'active'),

('Sifu',
 'Sloclap',
 'Microids',
 'Sifu is an acclaimed title from Sloclap, delivering an unforgettable gaming experience.',
 25.10, NULL, 0.00,
 'src/Images/games/sifu.png',
 '2022-02-08', 4.41, 405, 'active'),

('Marvel''s Guardians of the Galaxy',
 'Eidos-Montreal',
 'Square Enix',
 'Marvel''s Guardians of the Galaxy is an acclaimed title from Eidos-Montreal, delivering an unforgettable gaming experience.',
 56.48, 76.08, 25.76,
 'src/Images/games/guardians-of-the-galaxy.png',
 '2021-10-26', 4.74, 3012, 'active'),

('Deathloop',
 'Arkane Lyon',
 'Bethesda Softworks',
 'Deathloop is an acclaimed title from Arkane Lyon, delivering an unforgettable gaming experience.',
 48.86, 54.55, 10.43,
 'src/Images/games/deathloop.png',
 '2021-09-14', 3.85, 877, 'active'),

('It Takes Two',
 'Hazelight Studios',
 'EA Originals',
 'It Takes Two is an acclaimed title from Hazelight Studios, delivering an unforgettable gaming experience.',
 39.00, NULL, 0.00,
 'src/Images/games/it-takes-two.png',
 '2021-03-26', 3.81, 2237, 'active'),

('Ratchet & Clank: Rift Apart',
 'Insomniac Games',
 'PlayStation Studios',
 'Ratchet & Clank: Rift Apart is an acclaimed title from Insomniac Games, delivering an unforgettable gaming experience.',
 55.08, 69.22, 20.43,
 'src/Images/games/ratchet-and-clank-rift-apart.png',
 '2021-06-11', 3.75, 3158, 'active'),

('Baldur''s Gate 3',
 'Larian Studios',
 'Larian Studios',
 'Baldur''s Gate 3 is an acclaimed title from Larian Studios, delivering an unforgettable gaming experience.',
 33.49, 48.76, 31.32,
 'src/Images/games/baldurs-gate-3.png',
 '2023-08-03', 4.71, 3336, 'active'),

('Mortal Kombat 1',
 'NetherRealm Studios',
 'Warner Bros. Games',
 'Mortal Kombat 1 is an acclaimed title from NetherRealm Studios, delivering an unforgettable gaming experience.',
 33.38, 52.08, 35.91,
 'src/Images/games/mortal-kombat-1.png',
 '2023-09-19', 4.09, 3808, 'active'),

('Doom Eternal',
 'id Software',
 'Bethesda Softworks',
 'Doom Eternal is an acclaimed title from id Software, delivering an unforgettable gaming experience.',
 27.13, 35.83, 24.28,
 'src/Images/games/doom-eternal.png',
 '2020-03-20', 4.38, 4830, 'active'),

('Lies of P',
 'Round8 Studio',
 'Neowiz',
 'Lies of P is an acclaimed title from Round8 Studio, delivering an unforgettable gaming experience.',
 39.96, 59.92, 33.31,
 'src/Images/games/lies-of-p.png',
 '2023-09-19', 4.63, 948, 'active'),

('Starfield',
 'Bethesda Game Studios',
 'Bethesda Softworks',
 'Starfield is an acclaimed title from Bethesda Game Studios, delivering an unforgettable gaming experience.',
 27.63, 44.51, 37.92,
 'src/Images/games/starfield.png',
 '2023-09-06', 4.07, 3884, 'active'),

('Monster Hunter Wilds',
 'Capcom',
 'Capcom',
 'Monster Hunter Wilds is an acclaimed title from Capcom, delivering an unforgettable gaming experience.',
 46.45, 61.65, 24.66,
 'src/Images/games/monster-hunter-wilds.png',
 '2025-02-28', 3.90, 2836, 'active'),

('Kingdom Come: Deliverance II',
 'Warhorse Studios',
 'Deep Silver',
 'Kingdom Come: Deliverance II is an acclaimed title from Warhorse Studios, delivering an unforgettable gaming experience.',
 25.57, NULL, 0.00,
 'src/Images/games/kingdom-come-deliverance-2.png',
 '2025-02-04', 4.58, 2207, 'active'),

('Final Fantasy VII Rebirth',
 'Square Enix',
 'Square Enix',
 'Final Fantasy VII Rebirth is an acclaimed title from Square Enix, delivering an unforgettable gaming experience.',
 68.58, 81.20, 15.54,
 'src/Images/games/final-fantasy-7-rebirth.png',
 '2024-02-29', 4.46, 1679, 'active'),

('Marvel''s Spider-Man 2',
 'Insomniac Games',
 'PlayStation Studios',
 'Marvel''s Spider-Man 2 is an acclaimed title from Insomniac Games, delivering an unforgettable gaming experience.',
 27.63, 40.72, 32.15,
 'src/Images/games/spider-man-2.png',
 '2025-01-30', 4.23, 966, 'active'),

('Cyberpunk 2077',
 'CD Projekt Red',
 'CD Projekt',
 'Cyberpunk 2077 is an acclaimed title from CD Projekt Red, delivering an unforgettable gaming experience.',
 66.44, NULL, 0.00,
 'src/Images/games/cyberpunk-2077.png',
 '2020-12-10', 4.35, 695, 'active'),

('Elden Ring',
 'FromSoftware',
 'Bandai Namco',
 'Elden Ring is an acclaimed title from FromSoftware, delivering an unforgettable gaming experience.',
 24.27, NULL, 0.00,
 'src/Images/games/elden-ring.png',
 '2022-02-25', 3.69, 3943, 'active'),

('God of War Ragnarok',
 'Santa Monica Studio',
 'PlayStation Studios',
 'God of War Ragnarok is an acclaimed title from Santa Monica Studio, delivering an unforgettable gaming experience.',
 67.33, 76.31, 11.77,
 'src/Images/games/god-of-war-ragnarok.png',
 '2024-09-19', 4.59, 1697, 'active'),

('Grand Theft Auto V',
 'Rockstar North',
 'Rockstar Games',
 'Grand Theft Auto V is an acclaimed title from Rockstar North, delivering an unforgettable gaming experience.',
 55.64, NULL, 0.00,
 'src/Images/games/gta-5.png',
 '2015-04-14', 3.68, 1890, 'active'),

('Hogwarts Legacy',
 'Avalanche Software',
 'Warner Bros. Games',
 'Hogwarts Legacy is an acclaimed title from Avalanche Software, delivering an unforgettable gaming experience.',
 23.19, 37.01, 37.34,
 'src/Images/games/hogwarts-legacy.png',
 '2023-02-10', 3.61, 532, 'active'),

('Red Dead Redemption 2',
 'Rockstar Games',
 'Rockstar Games',
 'Red Dead Redemption 2 is an acclaimed title from Rockstar Games, delivering an unforgettable gaming experience.',
 31.44, 49.33, 36.27,
 'src/Images/games/red-dead-redemption-2.png',
 '2019-11-05', 4.50, 1805, 'active'),

('The Last of Us Part I',
 'Naughty Dog',
 'PlayStation Studios',
 'The Last of Us Part I is an acclaimed title from Naughty Dog, delivering an unforgettable gaming experience.',
 46.95, NULL, 0.00,
 'src/Images/games/the-last-of-us-part-1.png',
 '2022-09-02', 3.79, 844, 'inactive');


-- =========================================================
-- 5. GAME_IMAGES
-- =========================================================

INSERT INTO game_images (game_id, image, sort_order)
VALUES
(1, 'src/Images/games/dragon-ball-sparking-zero.png', 0),
(2, 'src/Images/games/ghostrunner-2.png', 0),
(3, 'src/Images/games/lords-of-the-fallen.png', 0),
(4, 'src/Images/games/armored-core-6-fires-of-rubicon.png', 0),
(5, 'src/Images/games/sifu.png', 0),
(6, 'src/Images/games/guardians-of-the-galaxy.png', 0),
(7, 'src/Images/games/deathloop.png', 0),
(8, 'src/Images/games/it-takes-two.png', 0),
(9, 'src/Images/games/ratchet-and-clank-rift-apart.png', 0),
(10, 'src/Images/games/baldurs-gate-3.png', 0),
(11, 'src/Images/games/mortal-kombat-1.png', 0),
(12, 'src/Images/games/doom-eternal.png', 0),
(13, 'src/Images/games/lies-of-p.png', 0),
(14, 'src/Images/games/starfield.png', 0),
(15, 'src/Images/games/monster-hunter-wilds.png', 0),
(16, 'src/Images/games/kingdom-come-deliverance-2.png', 0),
(17, 'src/Images/games/final-fantasy-7-rebirth.png', 0),
(18, 'src/Images/games/spider-man-2.png', 0),
(19, 'src/Images/games/cyberpunk-2077.png', 0),
(20, 'src/Images/games/elden-ring.png', 0),
(21, 'src/Images/games/god-of-war-ragnarok.png', 0),
(22, 'src/Images/games/gta-5.png', 0),
(23, 'src/Images/games/hogwarts-legacy.png', 0),
(24, 'src/Images/games/red-dead-redemption-2.png', 0),
(25, 'src/Images/games/the-last-of-us-part-1.png', 0);


-- =========================================================
-- 6. GAME_CATEGORIES
-- =========================================================

INSERT INTO game_categories (game_id, category_id)
VALUES
(1, 1),
(2, 1),
(3, 3),
(4, 1),
(5, 1),
(6, 2),
(7, 1),
(8, 2),
(9, 2),
(10, 3),
(11, 1),
(12, 4),
(13, 1),
(14, 3),
(15, 1),
(16, 3),
(17, 3),
(18, 1),
(19, 3),
(20, 3),
(21, 1),
(22, 2),
(23, 3),
(24, 2),
(25, 2);


-- =========================================================
-- 7. GAME_PLATFORMS
-- =========================================================

INSERT INTO game_platforms (game_id, platform_id)
VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(10, 2),
(11, 1),
(12, 1),
(12, 2),
(13, 1),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(17, 1),
(18, 1),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 1),
(25, 2);


-- =========================================================
-- 8. CART
-- Blocked user 25 removed
-- =========================================================

INSERT INTO cart (user_id)
VALUES
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24);


-- =========================================================
-- 9. CART_ITEMS
-- Cart IDs are now 1 -> 23
-- =========================================================

INSERT INTO cart_items
(cart_id, game_id, quantity, price)
VALUES

(1, 14, 2, 27.63),
(2, 16, 2, 25.57),
(3, 25, 3, 46.95),
(4, 6, 3, 56.48),
(5, 2, 2, 24.34),
(6, 2, 2, 24.34),
(6, 11, 1, 33.38),
(7, 7, 3, 48.86),
(8, 4, 2, 57.93),
(8, 1, 3, 51.96),
(9, 7, 3, 48.86),
(10, 8, 3, 39.00),
(11, 8, 1, 39.00),
(12, 15, 1, 46.45),
(13, 15, 1, 46.45),
(14, 13, 3, 39.96),
(15, 13, 3, 39.96),
(16, 15, 1, 46.45),
(17, 14, 3, 27.63),
(18, 21, 2, 67.33),
(19, 14, 1, 27.63),
(20, 13, 2, 39.96),
(21, 21, 2, 67.33),
(22, 4, 3, 57.93),
(23, 9, 2, 55.08);


-- =========================================================
-- 10. WISHLIST
-- =========================================================

INSERT INTO wishlist (user_id, game_id)
VALUES
(7, 17),
(3, 1),
(23, 10),
(9, 5),
(10, 6),
(4, 24),
(8, 12),
(10, 22),
(21, 9),
(20, 7),
(15, 20),
(12, 3),
(4, 5),
(7, 22),
(3, 18),
(9, 4),
(9, 10),
(23, 11),
(20, 21),
(22, 18),
(17, 1),
(19, 4),
(17, 16),
(18, 5),
(15, 18);


-- =========================================================
-- 11. ORDERS
-- =========================================================

INSERT INTO orders
(user_id, order_number, subtotal, discount, tax, total, status)
VALUES

(9,  'ORD-2026-1001', 146.58, 10.00, 20.49, 157.07, 'pending'),
(21, 'ORD-2026-1002', 138.60, 8.30, 19.40, 149.70, 'completed'),
(11, 'ORD-2026-1003', 235.91, 19.11, 33.03, 249.83, 'completed'),
(6,  'ORD-2026-1004', 227.54, 16.55, 31.86, 242.85, 'cancelled'),
(18, 'ORD-2026-1005', 59.16, 2.26, 3.18, 60.08, 'cancelled'),
(18, 'ORD-2026-1006', 30.09, 3.00, 4.21, 31.30, 'processing'),
(14, 'ORD-2026-1007', 55.68, 9.25, 7.80, 54.23, 'pending'),
(12, 'ORD-2026-1008', 78.00, 7.49, 11.03, 81.54, 'pending'),
(12, 'ORD-2026-1009', 238.43, 26.52, 33.38, 245.29, 'cancelled'),
(20, 'ORD-2026-1010', 229.85, 27.77, 32.18, 234.26, 'processing'),
(6,  'ORD-2026-1011', 293.34, 24.32, 41.07, 310.09, 'cancelled'),
(22, 'ORD-2026-1012', 261.94, 24.32, 36.67, 274.29, 'completed'),
(6,  'ORD-2026-1013', 240.46, 3.24, 33.66, 270.88, 'pending'),
(16, 'ORD-2026-1014', 82.28, 24.50, 11.52, 69.30, 'cancelled'),
(12, 'ORD-2026-1015', 105.45, 23.86, 14.76, 96.35, 'processing'),
(8,  'ORD-2026-1016', 26.63, 5.79, 3.73, 24.57, 'completed'),
(9,  'ORD-2026-1017', 262.02, 29.01, 36.68, 269.69, 'completed'),
(12, 'ORD-2026-1018', 199.61, 11.99, 27.95, 215.57, 'completed'),
(20, 'ORD-2026-1019', 141.68, 21.86, 19.84, 139.66, 'completed'),
(19, 'ORD-2026-1020', 289.51, 7.96, 40.53, 322.08, 'pending'),
(20, 'ORD-2026-1021', 141.68, 21.86, 19.84, 139.66, 'completed'),
(14, 'ORD-2026-1022', 189.74, 15.34, 26.56, 200.96, 'cancelled'),
(19, 'ORD-2026-1023', 73.22, 1.33, 10.25, 82.14, 'cancelled'),
(21, 'ORD-2026-1024', 165.58, 24.20, 23.18, 164.56, 'processing');


-- =========================================================
-- 12. ORDER_ITEMS
-- =========================================================

INSERT INTO order_items
(order_id, game_id, game_title, price, quantity, total)
VALUES

(1, 14, 'Starfield', 27.63, 3, 82.89),
(2, 20, 'Elden Ring', 24.27, 1, 24.27),
(3, 10, 'Baldur''s Gate 3', 33.49, 3, 100.47),
(4, 11, 'Mortal Kombat 1', 33.38, 2, 66.76),
(5, 5, 'Sifu', 25.10, 3, 75.30),
(6, 22, 'Grand Theft Auto V', 55.64, 1, 55.64),
(7, 19, 'Cyberpunk 2077', 66.44, 1, 66.44),
(8, 10, 'Baldur''s Gate 3', 33.49, 2, 66.98),
(9, 20, 'Elden Ring', 24.27, 2, 48.54),
(10, 15, 'Monster Hunter Wilds', 46.45, 3, 139.35),
(11, 24, 'Red Dead Redemption 2', 31.44, 1, 31.44),
(12, 17, 'Final Fantasy VII Rebirth', 68.58, 3, 205.74),
(13, 3, 'Lords of the Fallen', 21.32, 1, 21.32),
(14, 10, 'Baldur''s Gate 3', 33.49, 1, 33.49),
(15, 1, 'Dragon Ball: Sparking! Zero', 51.96, 2, 103.92),
(16, 25, 'The Last of Us Part I', 46.95, 2, 93.90),
(17, 19, 'Cyberpunk 2077', 66.44, 1, 66.44),
(18, 16, 'Kingdom Come: Deliverance II', 25.57, 1, 25.57),
(19, 23, 'Hogwarts Legacy', 23.19, 1, 23.19),
(20, 14, 'Starfield', 27.63, 3, 82.89),
(21, 15, 'Monster Hunter Wilds', 46.45, 1, 46.45),
(22, 15, 'Monster Hunter Wilds', 46.45, 2, 92.90),
(23, 17, 'Final Fantasy VII Rebirth', 68.58, 2, 137.16),
(24, 17, 'Final Fantasy VII Rebirth', 68.58, 1, 68.58);


-- =========================================================
-- 13. PAYMENTS
-- user_id removed
-- Payment status synchronized with Order status
-- =========================================================

INSERT INTO payments
(order_id, payment_method, transaction_id, card_brand,
 card_last4, amount, status, paid_at)
VALUES

(1,  'card', 'TXN100001', 'MasterCard', '3607', 157.07, 'pending', NULL),

(2,  'card', 'TXN100002', 'MasterCard', '5246', 149.70,
 'completed', '2026-08-15 12:00:00'),

(3,  'card', 'TXN100003', 'American Express', '8939', 249.83,
 'completed', '2026-08-15 12:00:00'),

(4,  'card', 'TXN100004', 'MasterCard', '2269', 242.85,
 'cancelled', NULL),

(5,  'card', 'TXN100005', 'MasterCard', '6502', 60.08,
 'cancelled', NULL),

(6,  'card', 'TXN100006', 'Visa', '3267', 31.30,
 'processing', NULL),

(7,  'card', 'TXN100007', 'American Express', '3503', 54.23,
 'pending', NULL),

(8,  'card', 'TXN100008', 'MasterCard', '7678', 81.54,
 'pending', NULL),

(9,  'card', 'TXN100009', 'MasterCard', '2020', 245.29,
 'cancelled', NULL),

(10, 'card', 'TXN100010', 'MasterCard', '1320', 234.26,
 'processing', NULL),

(11, 'card', 'TXN100011', 'MasterCard', '8814', 310.09,
 'cancelled', NULL),

(12, 'card', 'TXN100012', 'American Express', '9947', 274.29,
 'completed', '2026-08-15 12:00:00'),

(13, 'card', 'TXN100013', 'MasterCard', '4595', 270.88,
 'pending', NULL),

(14, 'card', 'TXN100014', 'Visa', '7371', 69.30,
 'cancelled', NULL),

(15, 'card', 'TXN100015', 'American Express', '3704', 96.35,
 'processing', NULL),

(16, 'card', 'TXN100016', 'American Express', '9751', 24.57,
 'completed', '2026-08-15 12:00:00'),

(17, 'card', 'TXN100017', 'American Express', '1444', 269.69,
 'completed', '2026-08-15 12:00:00'),

(18, 'card', 'TXN100018', 'Visa', '8564', 215.57,
 'completed', '2026-08-15 12:00:00'),

(19, 'card', 'TXN100019', 'MasterCard', '6363', 139.66,
 'completed', '2026-08-15 12:00:00'),

(20, 'card', 'TXN100020', 'MasterCard', '7211', 322.08,
 'pending', NULL),

(21, 'card', 'TXN100021', 'MasterCard', '2341', 139.66,
 'completed', '2026-08-15 12:00:00'),

(22, 'card', 'TXN100022', 'Visa', '6733', 200.96,
 'cancelled', NULL),

(23, 'card', 'TXN100023', 'American Express', '1659', 82.14,
 'cancelled', NULL),

(24, 'card', 'TXN100024', 'Visa', '1333', 164.56,
 'processing', NULL);


-- =========================================================
-- 14. REVIEWS
-- =========================================================

INSERT INTO reviews
(user_id, game_id, rating, comment, status)
VALUES

(23, 7, 5, 'Could be better balanced.', 'pending'),
(20, 20, 4, 'Disappointing ending.', 'pending'),
(3, 19, 4, 'Could be better balanced.', 'rejected'),
(5, 16, 4, 'Not worth the price.', 'approved'),
(23, 25, 2, 'Solid gameplay overall.', 'rejected'),
(14, 22, 3, 'Highly recommend it!', 'rejected'),
(24, 23, 4, 'Disappointing ending.', 'approved'),
(20, 4, 5, 'Great graphics and story.', 'approved'),
(15, 23, 2, 'Good but has some bugs.', 'approved'),
(22, 4, 4, 'Best game I''ve played this year!', 'approved'),
(12, 3, 5, 'Highly recommend it!', 'rejected'),
(12, 6, 4, 'Could be better balanced.', 'pending'),
(23, 21, 4, 'Could be better balanced.', 'pending'),
(19, 2, 4, 'Best game I''ve played this year!', 'approved'),
(12, 18, 3, 'Could be better balanced.', 'approved'),
(9, 25, 5, 'Great graphics and story.', 'rejected'),
(13, 13, 1, 'Solid gameplay overall.', 'pending'),
(6, 10, 1, 'Best game I''ve played this year!', 'rejected'),
(8, 4, 4, 'Disappointing ending.', 'rejected'),
(4, 19, 2, 'Disappointing ending.', 'rejected'),
(19, 7, 4, 'Disappointing ending.', 'pending'),
(4, 25, 1, 'Great graphics and story.', 'pending'),
(19, 22, 4, 'Best game I''ve played this year!', 'approved'),
(10, 22, 5, 'Solid gameplay overall.', 'approved');


-- =========================================================
-- 15. PLAYER_STATS
-- Blocked user 25 removed
-- =========================================================

INSERT INTO player_stats
(user_id, games_played, games_won, games_lost, total_score,
 global_rank, weekly_score, monthly_score, level, xp)
VALUES

(2, 148, 78, 70, 16575, 1, 472, 988, 93, 25242),
(3, 171, 30, 141, 48785, 2, 1097, 7789, 98, 90461),
(4, 104, 24, 80, 14280, 3, 1512, 3966, 36, 94972),
(5, 311, 268, 43, 39211, 4, 579, 823, 25, 38829),
(6, 126, 46, 80, 11859, 5, 619, 115, 91, 70010),
(7, 74, 35, 39, 3082, 6, 1995, 446, 71, 38290),
(8, 367, 64, 303, 41903, 7, 1778, 6166, 63, 13446),
(9, 456, 6, 450, 37721, 8, 582, 3845, 62, 57733),
(10, 184, 47, 137, 3467, 9, 517, 7709, 62, 14953),
(11, 430, 33, 397, 26360, 10, 1007, 606, 74, 82502),
(12, 361, 27, 334, 10043, 11, 305, 6644, 73, 39824),
(13, 53, 15, 38, 7862, 12, 1142, 6262, 54, 79471),
(14, 315, 115, 200, 34347, 13, 779, 3690, 57, 38974),
(15, 450, 301, 149, 28203, 14, 625, 4658, 80, 7894),
(16, 322, 50, 272, 13717, 15, 1281, 1728, 34, 86563),
(17, 51, 10, 41, 15819, 16, 355, 4521, 10, 20517),
(18, 11, 6, 5, 29624, 17, 1411, 4864, 61, 38175),
(19, 26, 7, 19, 18981, 18, 1447, 2316, 90, 59510),
(20, 46, 43, 3, 15397, 19, 1892, 2167, 81, 77304),
(21, 348, 101, 247, 27961, 20, 235, 4461, 29, 84886),
(22, 86, 34, 52, 9421, 21, 146, 488, 22, 40319),
(23, 314, 291, 23, 19014, 22, 899, 1018, 60, 90266),
(24, 165, 103, 62, 17941, 23, 1024, 4423, 64, 57377);