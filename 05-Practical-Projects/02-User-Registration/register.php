<?php
// Start session for CSRF protection and user data
// Example: $_SESSION['csrf_token'] = 'abc123...'
session_start();

// Generate CSRF token if not exists
// Example output: New token generated and stored in session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Database connection configuration
// Example: Host = localhost, Database = user_system
$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'user_system',
    'username' => 'root',
    'password' => ''
];

// Function to establish database connection
// Example output: PDO connection object or false on failure
function connectDB($config) {
    try {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        return false;
    }
}

// Function to validate registration data
// Example input: validateData(['email' => 'test@example.com', 'password' => 'Pass123'])
// Example output: ['valid' => false, 'message' => 'Invalid email format']
function validateData($data) {
    $result = ['valid' => true, 'message' => ''];
    
    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $result['valid'] = false;
        $result['message'] = 'Invalid email format';
        return $result;
    }
    
    // Validate password strength
    if (strlen($data['password']) < 8) {
        $result['valid'] = false;
        $result['message'] = 'Password must be at least 8 characters long';
        return $result;
    }
    
    return $result;
}

// Handle registration form submission
// Example input: POST request with email, password, confirm_password
// Example output: JSON response with status and message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $response = ['status' => '', 'message' => ''];
    
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid request';
        echo json_encode($response);
        exit;
    }
    
    // Get and sanitize form data
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate passwords match
    if ($password !== $confirmPassword) {
        $response['status'] = 'error';
        $response['message'] = 'Passwords do not match';
        echo json_encode($response);
        exit;
    }
    
    // Validate registration data
    $validation = validateData([
        'email' => $email,
        'password' => $password
    ]);
    
    if (!$validation['valid']) {
        $response['status'] = 'error';
        $response['message'] = $validation['message'];
        echo json_encode($response);
        exit;
    }
    
    // Connect to database
    $db = connectDB($dbConfig);
    if (!$db) {
        $response['status'] = 'error';
        $response['message'] = 'Database connection failed';
        echo json_encode($response);
        exit;
    }
    
    try {
        // Check if email already exists
        // Example: SELECT query to check existing email
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $response['status'] = 'error';
            $response['message'] = 'Email already registered';
            echo json_encode($response);
            exit;
        }
        
        // Hash password for secure storage
        // Example output: $2y$10$abcdef...
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        // Example: INSERT query with email and hashed password
        $stmt = $db->prepare('INSERT INTO users (email, password, created_at) VALUES (?, ?, NOW())');
        $stmt->execute([$email, $hashedPassword]);
        
        $response['status'] = 'success';
        $response['message'] = 'Registration successful!';
    } catch (PDOException $e) {
        $response['status'] = 'error';
        $response['message'] = 'Registration failed';
    }
    
    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        /* Basic styling for registration form */
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            display: none;
        }
        .success {
            color: green;
            margin-bottom: 15px;
            display: none;
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>User Registration</h2>
        
        <!-- Success/Error messages -->
        <div id="successMessage" class="success"></div>
        <div id="errorMessage" class="error"></div>
        
        <!-- Registration Form -->
        <form id="registerForm" onsubmit="return submitForm(event)">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Register</button>
        </form>
    </div>

    <script>
        // Function to submit registration form via AJAX
        // Example: Form submission with validation and error handling
        async function submitForm(event) {
            event.preventDefault();
            
            // Reset message displays
            document.getElementById('successMessage').style.display = 'none';
            document.getElementById('errorMessage').style.display = 'none';
            
            // Get form data
            const form = document.getElementById('registerForm');
            const formData = new FormData(form);
            
            try {
                // Send AJAX request
                const response = await fetch('register.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                // Parse response
                // Example output: {status: 'success', message: 'Registration successful!'}
                const data = await response.json();
                
                // Handle response
                if (data.status === 'success') {
                    // Show success message and reset form
                    document.getElementById('successMessage').textContent = data.message;
                    document.getElementById('successMessage').style.display = 'block';
                    form.reset();
                } else {
                    // Show error message
                    document.getElementById('errorMessage').textContent = data.message;
                    document.getElementById('errorMessage').style.display = 'block';
                }
            } catch (error) {
                // Handle network or other errors
                document.getElementById('errorMessage').textContent = 'An error occurred. Please try again.';
                document.getElementById('errorMessage').style.display = 'block';
            }
            
            return false;
        }
    </script>
</body>
</html>