<?php

require_once '../../03-Database-Integration/01-MySQL-Setup/config.php';

class Authentication {
    private $pdo;
    private $sessionName = 'user_session';
    private $rememberTokenName = 'remember_token';
    
    public function __construct() {
        // Initialize database connection
        // Example output: "Database connection established"
        $this->pdo = getDbConnection();
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            // Configure secure session settings
            // Example: Prevents JavaScript access to session cookie
            ini_set('session.cookie_httponly', 1);
            // Example: Only send cookie over HTTPS in production
            if (isset($_SERVER['HTTPS'])) {
                ini_set('session.cookie_secure', 1);
            }
            session_start();
        }
    }
    
    // Login user with email and password
    // Example input: login('john@example.com', 'secure123', true)
    // Example output: ['success' => true, 'message' => 'Login successful']
    public function login($email, $password, $remember = false) {
        try {
            // Prepare SQL to fetch user
            // Example SQL: "SELECT * FROM users WHERE email = ?"
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            // Verify user exists and password is correct
            // Example: password_verify('secure123', '$2y$10$abcdef...')
            if ($user && password_verify($password, $user['password'])) {
                // Generate new session ID to prevent session fixation
                session_regenerate_id(true);
                
                // Store user data in session
                // Example: $_SESSION['user_session'] = ['id' => 1, 'email' => 'john@example.com']
                $_SESSION[$this->sessionName] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'username' => $user['username']
                ];
                
                // Handle remember me functionality
                if ($remember) {
                    $this->setRememberToken($user['id']);
                }
                
                return ['success' => true, 'message' => 'Login successful'];
            }
            
            return ['success' => false, 'message' => 'Invalid email or password'];
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Login failed'];
        }
    }
    
    // Set remember me token
    // Example input: setRememberToken(1)
    // Example output: Sets secure cookie with encrypted token
    private function setRememberToken($userId) {
        // Generate secure random token
        // Example: bin2hex(random_bytes(32))
        $token = bin2hex(random_bytes(32));
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        
        try {
            // Store token in database
            $sql = "UPDATE users SET remember_token = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$hashedToken, $userId]);
            
            // Set secure cookie with token
            // Example: remember_token=abc123... (30 days expiry)
            $expiry = time() + (30 * 24 * 60 * 60);
            setcookie(
                $this->rememberTokenName,
                $token,
                [
                    'expires' => $expiry,
                    'path' => '/',
                    'httponly' => true,
                    'secure' => isset($_SERVER['HTTPS']),
                    'samesite' => 'Strict'
                ]
            );
        } catch (PDOException $e) {
            error_log("Remember Token Error: " . $e->getMessage());
        }
    }
    
    // Check if user is logged in
    // Example output: true/false
    public function isLoggedIn() {
        return isset($_SESSION[$this->sessionName]);
    }
    
    // Get current user data
    // Example output: ['id' => 1, 'email' => 'john@example.com', 'username' => 'john_doe']
    public function getCurrentUser() {
        return $this->isLoggedIn() ? $_SESSION[$this->sessionName] : null;
    }
    
    // Logout user
    // Example output: Destroys session and removes remember token
    public function logout() {
        try {
            if ($this->isLoggedIn()) {
                // Clear remember token if exists
                if (isset($_COOKIE[$this->rememberTokenName])) {
                    // Remove token from database
                    $userId = $_SESSION[$this->sessionName]['id'];
                    $sql = "UPDATE users SET remember_token = NULL WHERE id = ?";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$userId]);
                    
                    // Delete remember cookie
                    setcookie($this->rememberTokenName, '', time() - 3600, '/');
                }
                
                // Destroy session
                session_unset();
                session_destroy();
                
                return ['success' => true, 'message' => 'Logout successful'];
            }
        } catch (PDOException $e) {
            error_log("Logout Error: " . $e->getMessage());
        }
        
        return ['success' => false, 'message' => 'No active session'];
    }
}

// Example usage:
/*
try {
    $auth = new Authentication();
    
    // Login user
    $result = $auth->login('john@example.com', 'secure123', true);
    echo $result['message']; // "Login successful"
    
    // Check login status
    if ($auth->isLoggedIn()) {
        $user = $auth->getCurrentUser();
        echo "Welcome " . $user['username'];
    }
    
    // Logout user
    $result = $auth->logout();
    echo $result['message']; // "Logout successful"
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
*/