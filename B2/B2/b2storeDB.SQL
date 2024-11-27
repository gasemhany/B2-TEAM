CREATE DATABASE payment_db;

USE b2;

CREATE TABLE orders (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    vodafone_number VARCHAR(20),
    card_number VARCHAR(20),
    expiry_date DATE,
    cvv VARCHAR(10),
    reg_date TIMESTAMP
);
