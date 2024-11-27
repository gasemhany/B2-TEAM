CREATE DATABASE b2;

USE b2;
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- معرّف فريد لكل منتج
    name VARCHAR(255) NOT NULL,               -- اسم المنتج
    description TEXT,                         -- وصف المنتج
    price DECIMAL(10, 2) NOT NULL,            -- سعر المنتج
    image VARCHAR(255),                       -- رابط الصورة
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- وقت إنشاء السجل
);

