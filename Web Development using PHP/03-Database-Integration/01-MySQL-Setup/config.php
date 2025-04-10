<?php

// Database Configuration File
// This file contains the database connection parameters and configuration settings
// IMPORTANT: In production, store these credentials securely and never commit them to version control

// Define database connection constants
define('DB_HOST', 'localhost');     // Database host (usually localhost for XAMPP)
define('DB_USER', 'root');         // Database username (default is root for XAMPP)
define('DB_PASS', '');             // Database password (empty by default in XAMPP)
define('DB_NAME', 'php_examples'); // Name of the database we'll create

// Error reporting settings
// In development: Show all errors for debugging
// In production: Set this to 0 or comment out
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set default character set and collation
$charset = 'utf8mb4';              // Modern character set supporting emojis and special characters
$collate = 'utf8mb4_unicode_ci';   // Case-insensitive Unicode collation

// Optional: Define custom database settings
$db_settings = [
    'MYSQL_ATTR_INIT_COMMAND' => "SET NAMES $charset COLLATE $collate",
    'MYSQL_ATTR_SSL_CA' => false,  // SSL certificate path for secure connections
    'MYSQL_ATTR_SSL_VERIFY' => false // Verify SSL certificate (recommended in production)
];

// Function to create a new database connection
function getDbConnection() {
    try {
        // Create new PDO instance
        // Format: mysql:host=hostname;dbname=database_name
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        
        // Create connection with error handling enabled
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,    // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Return arrays indexed by column name
            PDO::ATTR_EMULATE_PREPARES => false,           // Use real prepared statements
        ]);
        
        return $pdo;
    } catch (PDOException $e) {
        // Log error details (in production, use proper logging)
        error_log("Database Connection Error: " . $e->getMessage());
        throw new Exception("Database connection failed. Please check your configuration.");
    }
}

// Example Usage:
/*
try {
    $conn = getDbConnection();
    echo "Database connection successful!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
*/