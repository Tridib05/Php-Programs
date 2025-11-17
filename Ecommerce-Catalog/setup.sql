-- E-Commerce Catalog Database Schema
CREATE DATABASE IF NOT EXISTS ecommerce_catalog DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce_catalog;

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    category_id INT,
    is_active TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX (category_id),
    INDEX (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20),
    shipping_address TEXT NOT NULL,
    total_items INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (customer_email),
    INDEX (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Categories
INSERT INTO categories (name, description) VALUES
('Electronics', 'Electronic devices and gadgets'),
('Clothing', 'Apparel and fashion items'),
('Home & Garden', 'Furniture and home accessories'),
('Sports', 'Sports equipment and outdoor gear'),
('Books', 'Books and educational materials');

-- Sample Products
INSERT INTO products (name, description, price, stock, category_id, is_active) VALUES
('Wireless Headphones', 'High-quality Bluetooth headphones with noise cancellation', 79.99, 50, 1, 1),
('T-Shirt', 'Comfortable cotton t-shirt in multiple colors', 19.99, 100, 2, 1),
('Desk Lamp', 'Modern LED desk lamp with adjustable brightness', 34.99, 30, 3, 1),
('Running Shoes', 'Professional running shoes with superior comfort', 89.99, 40, 4, 1),
('Programming Book', 'Learn PHP and MySQL development from scratch', 24.99, 25, 5, 1),
('USB-C Cable', 'Fast charging USB-C cable 2 meters', 9.99, 200, 1, 1),
('Office Chair', 'Ergonomic office chair with lumbar support', 199.99, 15, 3, 1),
('Yoga Mat', 'Non-slip yoga mat for fitness and meditation', 29.99, 60, 4, 1);
