<?php

// Include database configuration
require_once 'config.php';

try {
    // Create a temporary connection without database name
    // This allows us to create the database if it doesn't exist
    $tempDsn = "mysql:host=" . DB_HOST;
    $pdo = new PDO($tempDsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    // Example output: "Database 'php_examples' created successfully"
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET $charset COLLATE $collate";
    $pdo->exec($sql);
    echo "Database '" . DB_NAME . "' created successfully\n";
    
    // Switch to the new database
    $pdo->exec("USE " . DB_NAME);
    
    // Create users table
    // This table will store user information for authentication
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo "Table 'users' created successfully\n";
    
    // Create products table
    // This table will store product information for an e-commerce system
    $sql = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        stock INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo "Table 'products' created successfully\n";
    
    // Create orders table
    // This table will store order information linking users and products
    $sql = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo "Table 'orders' created successfully\n";
    
    // Create order_items table
    // This table will store individual items within each order
    $sql = "CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo "Table 'order_items' created successfully\n";
    
    // Insert sample product data
    // Example output: "Sample products inserted successfully"
    $sql = "INSERT INTO products (name, description, price, stock) VALUES 
        ('Laptop', 'High-performance laptop with SSD', 999.99, 10),
        ('Smartphone', '5G-enabled smartphone with dual camera', 599.99, 15),
        ('Headphones', 'Wireless noise-cancelling headphones', 199.99, 20)";
    $pdo->exec($sql);
    echo "Sample products inserted successfully\n";
    
} catch (PDOException $e) {
    // Log the error and display user-friendly message
    error_log("Database Setup Error: " . $e->getMessage());
    die("Setup failed: " . $e->getMessage());
}