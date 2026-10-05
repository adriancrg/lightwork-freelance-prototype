-- LightWork database schema (MySQL / MariaDB, XAMPP).
--
-- RECONSTRUCTED from the queries in the PHP code: table and column names are exactly the ones
-- the code uses (including the "Descriptionn" spelling); data types, lengths and indexes are my
-- best reconstruction. The code connects to a database called "signinsignup_db".

CREATE DATABASE IF NOT EXISTS signinsignup_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE signinsignup_db;

-- Registered users (php/SU.php, php/SI.php, php/functions.php)
CREATE TABLE IF NOT EXISTS usuarios (
    ID       INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Email    VARCHAR(255) NOT NULL UNIQUE,
    Username VARCHAR(50)  NOT NULL UNIQUE,
    Pword    CHAR(64)     NOT NULL            -- SHA-256 hex digest (see "Known issues" in the README)
);

-- Service offers posted by sellers (php/upload_be.php, php/get_offers.php, display_offer.php)
CREATE TABLE IF NOT EXISTS offers (
    OfferID        INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Title          VARCHAR(255)  NOT NULL,
    Descriptionn   TEXT          NOT NULL,    -- sic: double "n", as used by the code
    Category       VARCHAR(100)  NOT NULL,
    Price          DECIMAL(12,2) NOT NULL,    -- shown in sats in the interface
    SellerUsername VARCHAR(50)   NOT NULL,
    created_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_offers_seller (SellerUsername)
);

-- Photos attached to an offer (php/upload_be.php); files live in assets/offer_images/
CREATE TABLE IF NOT EXISTS images (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    offer_id   INT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    INDEX idx_images_offer (offer_id)
);

-- A buyer taking an offer opens a conversation with the seller (display_offer.php, taken_offer.php)
CREATE TABLE IF NOT EXISTS conversations (
    ID        INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    offer_id  INT UNSIGNED NOT NULL,
    seller_id INT UNSIGNED NOT NULL,
    buyer_id  INT UNSIGNED NOT NULL
);

-- Chat messages of a conversation (php/functions.php, php/update_chat.php)
CREATE TABLE IF NOT EXISTS chat_messages (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT UNSIGNED NOT NULL,
    buyer_seller    VARCHAR(10)  NOT NULL,    -- 'buyer' or 'seller'
    message         TEXT         NOT NULL,
    `timestamp`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_chat_conversation (conversation_id)
);
