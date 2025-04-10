<?php

// Include database configuration
require_once '../01-MySQL-Setup/config.php';

class UserOperations {
    private $pdo;
    
    public function __construct() {
        // Initialize database connection
        // Example output: "Database connection established"
        $this->pdo = getDbConnection();
    }
    
    // Create new user
    // Example input: createUser('john_doe', 'john@example.com', 'secure123')
    // Example output: "User created successfully with ID: 1"
    public function createUser($username, $email, $password) {
        try {
            // Hash password for security
            // Example: '$2y$10$abcdef...'
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Prepare SQL statement to prevent SQL injection
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username, $email, $hashedPassword]);
            
            return "User created successfully with ID: " . $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Create User Error: " . $e->getMessage());
            throw new Exception("Failed to create user");
        }
    }
    
    // Read user information
    // Example input: getUser(1)
    // Example output: Array with user data
    public function getUser($userId) {
        try {
            $sql = "SELECT id, username, email, created_at FROM users WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Get User Error: " . $e->getMessage());
            throw new Exception("Failed to retrieve user");
        }
    }
    
    // Update user profile
    // Example input: updateUser(1, 'new_email@example.com', 'newpassword')
    // Example output: "User updated successfully"
    public function updateUser($userId, $email, $newPassword = null) {
        try {
            if ($newPassword) {
                // Update email and password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET email = ?, password = ? WHERE id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$email, $hashedPassword, $userId]);
            } else {
                // Update email only
                $sql = "UPDATE users SET email = ? WHERE id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$email, $userId]);
            }
            
            return "User updated successfully";
        } catch (PDOException $e) {
            error_log("Update User Error: " . $e->getMessage());
            throw new Exception("Failed to update user");
        }
    }
    
    // Delete user account
    // Example input: deleteUser(1)
    // Example output: "User deleted successfully"
    public function deleteUser($userId) {
        try {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            
            return "User deleted successfully";
        } catch (PDOException $e) {
            error_log("Delete User Error: " . $e->getMessage());
            throw new Exception("Failed to delete user");
        }
    }
    
    // Verify user password
    // Example input: verifyPassword('john_doe', 'secure123')
    // Example output: true/false
    public function verifyPassword($username, $password) {
        try {
            $sql = "SELECT password FROM users WHERE username = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            return $user && password_verify($password, $user['password']);
        } catch (PDOException $e) {
            error_log("Password Verification Error: " . $e->getMessage());
            throw new Exception("Failed to verify password");
        }
    }
}

// Example usage:
/*
try {
    $userOps = new UserOperations();
    
    // Create new user
    echo $userOps->createUser('john_doe', 'john@example.com', 'secure123');
    
    // Get user information
    $user = $userOps->getUser(1);
    print_r($user);
    
    // Update user email
    echo $userOps->updateUser(1, 'newemail@example.com');
    
    // Verify password
    $isValid = $userOps->verifyPassword('john_doe', 'secure123');
    echo $isValid ? "Password valid" : "Password invalid";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
*/