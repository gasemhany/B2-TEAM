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
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);





