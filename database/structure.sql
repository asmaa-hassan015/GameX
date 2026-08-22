-- =========================================================
-- GAME X DATABASE
-- DATABASE STRUCTURE ONLY
-- NO INSERT DATA
-- =========================================================

CREATE DATABASE IF NOT EXISTS game_x
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE game_x;


-- =========================================================
-- 1. USERS
-- Login / Register / Player Profile / Top Players
-- =========================================================

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(50) NOT NULL UNIQUE,

    email VARCHAR(150) NOT NULL UNIQUE,

    password VARCHAR(255) DEFAULT NULL,

    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',

    terms_accepted BOOLEAN NOT NULL DEFAULT FALSE,

    status ENUM('active', 'blocked') NOT NULL DEFAULT 'active',

    avatar VARCHAR(500) DEFAULT NULL,

    remember_token VARCHAR(255) DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);


-- =========================================================
-- 2. GAMES
-- Games / Game Details
-- Digital Games
-- =========================================================

CREATE TABLE games (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(150) NOT NULL,

    developer VARCHAR(150) DEFAULT NULL,

    publisher VARCHAR(150) DEFAULT NULL,

    description TEXT DEFAULT NULL,

    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    old_price DECIMAL(10,2) DEFAULT NULL,

    discount DECIMAL(5,2) NOT NULL DEFAULT 0.00,

    image VARCHAR(500) DEFAULT NULL,

    release_date DATE DEFAULT NULL,

    rating DECIMAL(3,2) NOT NULL DEFAULT 0.00,

    total_reviews INT UNSIGNED NOT NULL DEFAULT 0,

    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);


-- =========================================================
-- 3. GAME IMAGES
-- =========================================================

CREATE TABLE game_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    game_id INT UNSIGNED NOT NULL,

    image VARCHAR(500) NOT NULL,

    sort_order INT UNSIGNED NOT NULL DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_game_images_game
        FOREIGN KEY (game_id)
        REFERENCES games(id)
        ON DELETE CASCADE
);


-- =========================================================
-- 4. CATEGORIES
-- =========================================================

CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL UNIQUE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =========================================================
-- 5. GAME CATEGORIES
-- =========================================================

CREATE TABLE game_categories (
    game_id INT UNSIGNED NOT NULL,

    category_id INT UNSIGNED NOT NULL,

    PRIMARY KEY (game_id, category_id),

    CONSTRAINT fk_game_categories_game
        FOREIGN KEY (game_id)
        REFERENCES games(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_game_categories_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE CASCADE
);


-- =========================================================
-- 6. PLATFORMS
-- =========================================================

CREATE TABLE platforms (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL UNIQUE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =========================================================
-- 7. GAME PLATFORMS
-- =========================================================

CREATE TABLE game_platforms (
    game_id INT UNSIGNED NOT NULL,

    platform_id INT UNSIGNED NOT NULL,

    PRIMARY KEY (game_id, platform_id),

    CONSTRAINT fk_game_platforms_game
        FOREIGN KEY (game_id)
        REFERENCES games(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_game_platforms_platform
        FOREIGN KEY (platform_id)
        REFERENCES platforms(id)
        ON DELETE CASCADE
);


-- =========================================================
-- 8. CART
-- One cart per active user
-- =========================================================

CREATE TABLE cart (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id INT UNSIGNED NOT NULL UNIQUE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_cart_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);


-- =========================================================
-- 9. CART ITEMS
-- =========================================================

CREATE TABLE cart_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    cart_id INT UNSIGNED NOT NULL,

    game_id INT UNSIGNED NOT NULL,

    quantity INT UNSIGNED NOT NULL DEFAULT 1,

    price DECIMAL(10,2) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY unique_cart_game (cart_id, game_id),

    CONSTRAINT fk_cart_items_cart
        FOREIGN KEY (cart_id)
        REFERENCES cart(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_cart_items_game
        FOREIGN KEY (game_id)
        REFERENCES games(id)
        ON DELETE CASCADE
);


-- =========================================================
-- 10. WISHLIST
-- =========================================================

CREATE TABLE wishlist (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id INT UNSIGNED NOT NULL,

    game_id INT UNSIGNED NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_wishlist_game (user_id, game_id),

    CONSTRAINT fk_wishlist_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_wishlist_game
        FOREIGN KEY (game_id)
        REFERENCES games(id)
        ON DELETE CASCADE
);


-- =========================================================
-- 11. ORDERS
-- =========================================================

CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id INT UNSIGNED NOT NULL,

    order_number VARCHAR(50) NOT NULL UNIQUE,

    subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    discount DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    tax DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    status ENUM(
        'pending',
        'processing',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);


-- =========================================================
-- 12. ORDER ITEMS
-- =========================================================

CREATE TABLE order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    order_id INT UNSIGNED NOT NULL,

    game_id INT UNSIGNED NOT NULL,

    game_title VARCHAR(150) NOT NULL,

    price DECIMAL(10,2) NOT NULL,

    quantity INT UNSIGNED NOT NULL DEFAULT 1,

    total DECIMAL(10,2) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_order_items_game
        FOREIGN KEY (game_id)
        REFERENCES games(id)
        ON DELETE RESTRICT
);


-- =========================================================
-- 13. PAYMENTS
-- user_id removed
-- User is obtained through orders.user_id
-- =========================================================

CREATE TABLE payments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    order_id INT UNSIGNED NOT NULL UNIQUE,

    payment_method ENUM('card')
        NOT NULL DEFAULT 'card',

    transaction_id VARCHAR(150) DEFAULT NULL,

    card_brand VARCHAR(50) DEFAULT NULL,

    card_last4 VARCHAR(4) DEFAULT NULL,

    amount DECIMAL(10,2) NOT NULL,

    status ENUM(
        'pending',
        'processing',
        'completed',
        'failed',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',

    paid_at DATETIME DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_payments_order
        FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE
);


-- =========================================================
-- 14. REVIEWS
-- =========================================================

CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id INT UNSIGNED NOT NULL,

    game_id INT UNSIGNED NOT NULL,

    rating TINYINT UNSIGNED NOT NULL,

    comment TEXT DEFAULT NULL,

    status ENUM(
        'pending',
        'approved',
        'rejected'
    ) NOT NULL DEFAULT 'approved',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY unique_user_game_review (user_id, game_id),

    CONSTRAINT chk_rating
        CHECK (rating BETWEEN 1 AND 5),

    CONSTRAINT fk_reviews_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_reviews_game
        FOREIGN KEY (game_id)
        REFERENCES games(id)
        ON DELETE CASCADE
);


-- =========================================================
-- 15. PLAYER STATS
-- Admin is NOT a player
-- Blocked users are NOT included
-- =========================================================

CREATE TABLE player_stats (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id INT UNSIGNED NOT NULL UNIQUE,

    games_played INT UNSIGNED NOT NULL DEFAULT 0,

    games_won INT UNSIGNED NOT NULL DEFAULT 0,

    games_lost INT UNSIGNED NOT NULL DEFAULT 0,

    total_score INT UNSIGNED NOT NULL DEFAULT 0,

    global_rank INT UNSIGNED DEFAULT NULL,

    weekly_score INT UNSIGNED NOT NULL DEFAULT 0,

    monthly_score INT UNSIGNED NOT NULL DEFAULT 0,

    level INT UNSIGNED NOT NULL DEFAULT 1,

    xp INT UNSIGNED NOT NULL DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_player_stats_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);


-- =========================================================
-- 16. CONTACTS
-- =========================================================

CREATE TABLE contacts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    email VARCHAR(150) NOT NULL,

    subject VARCHAR(200) NOT NULL,

    message TEXT NOT NULL,

    status ENUM(
        'new',
        'read',
        'replied',
        'closed'
    ) NOT NULL DEFAULT 'new',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);