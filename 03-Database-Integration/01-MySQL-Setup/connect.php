<?php

// Include the database configuration file
require_once 'config.php';

// Example of establishing a database connection
// This file demonstrates how to connect to MySQL and handle potential errors

try {
    // Get database connection using the function from config.php
    // Example output on success: "Connected to database: php_examples"
    $pdo = getDbConnection();
    echo "Connected to database: " . DB_NAME . "\n";
    
    // Test the connection by running a simple query
    // Example output: "Database connection test successful"
    $stmt = $pdo->query("SELECT 1");
    echo "Database connection test successful\n";
    
    // Get server information
    // Example output: "MySQL Server Info: 5.7.24"
    echo "MySQL Server Info: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
    
    // Get client information
    // Example output: "MySQL Client Info: mysqlnd 5.0.12-dev"
    echo "MySQL Client Info: " . $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION) . "\n";
    
    // Get connection status
    // Example output: "Connection Status: Connection OK"
    echo "Connection Status: " . $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "\n";
    
    // Test query to get table list
    // Example output: "Available tables: users, products, orders, order_items"
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Available tables: " . implode(", ", $tables) . "\n";
    
} catch (PDOException $e) {
    // Handle database connection errors
    // Example output on error: "Connection failed: Access denied for user 'root'"
    error_log("Database Connection Error: " . $e->getMessage());
    die("Connection failed: " . $e->getMessage());
} catch (Exception $e) {
    // Handle other types of errors
    error_log("General Error: " . $e->getMessage());
    die("Error: " . $e->getMessage());
} finally {
    // Close the connection (PDO does this automatically, but it's good practice)
    // The connection will be closed when $pdo goes out of scope
    $pdo = null;
    echo "Connection closed\n";
}