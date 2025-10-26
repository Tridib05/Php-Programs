-- Database creation
CREATE DATABASE IF NOT EXISTS ecommerce_catalog;
USE ecommerce_catalog;

-- Categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    stock_quantity INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Insert sample categories
INSERT INTO categories (name, description, image) VALUES
('Electronics', 'Latest electronic gadgets and devices', 'electronics.jpg'),
('Clothing', 'Fashion and apparel for all ages', 'clothing.jpg'),
('Books', 'Wide collection of books and literature', 'books.jpg'),
('Home & Garden', 'Home improvement and gardening supplies', 'home-garden.jpg'),
('Sports', 'Sports equipment and accessories', 'sports.jpg');

-- Insert sample products
INSERT INTO products (name, description, short_description, price, category_id, image, stock_quantity) VALUES
('Smartphone', 'Latest Android smartphone with advanced features', 'High-performance smartphone with excellent camera', 25000.00, 1, 'smartphone.jpg', 50),
('Laptop', 'Gaming laptop with high-end specifications', 'Powerful laptop for gaming and productivity', 75000.00, 1, 'laptop.jpg', 25),
('T-Shirt', 'Cotton t-shirt available in multiple colors', 'Comfortable cotton t-shirt for casual wear', 800.00, 2, 'tshirt.jpg', 100),
('Jeans', 'Denim jeans with modern fit', 'Stylish denim jeans for everyday wear', 2500.00, 2, 'jeans.jpg', 75),
('Programming Book', 'Complete guide to web development', 'Learn modern web development techniques', 1200.00, 3, 'programming-book.jpg', 30);
