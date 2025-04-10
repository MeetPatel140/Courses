<?php
// Start session for admin authentication
// Example: $_SESSION['admin_id'] = 1
session_start();

// Database connection configuration
// Example: Host = localhost, Database = admin_system
$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'admin_system',
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

// Function to get users with pagination
// Example input: getUsers($db, 1, 10)
// Example output: ['users' => [...], 'total' => 100]
function getUsers($db, $page = 1, $limit = 10) {
    try {
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;
        
        // Get total users count
        $stmt = $db->query('SELECT COUNT(*) FROM users');
        $total = $stmt->fetchColumn();
        
        // Get users for current page
        $stmt = $db->prepare(
            'SELECT id, name, email, role, created_at, status 
             FROM users 
             ORDER BY created_at DESC 
             LIMIT ? OFFSET ?'
        );
        $stmt->execute([$limit, $offset]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ['users' => $users, 'total' => $total];
    } catch (PDOException $e) {
        return ['users' => [], 'total' => 0];
    }
}

// Function to create new user
// Example input: createUser($db, ['name' => 'John', 'email' => 'john@example.com', ...])
// Example output: ['success' => true, 'message' => 'User created successfully']
function createUser($db, $userData) {
    try {
        $stmt = $db->prepare(
            'INSERT INTO users (name, email, password, role, status) 
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $userData['name'],
            $userData['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT),
            $userData['role'],
            'active'
        ]);
        
        return ['success' => true, 'message' => 'User created successfully'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to create user'];
    }
}

// Function to update user
// Example input: updateUser($db, 1, ['name' => 'John Updated', ...])
// Example output: ['success' => true, 'message' => 'User updated successfully']
function updateUser($db, $userId, $userData) {
    try {
        $updates = [];
        $params = [];
        
        // Build update query dynamically
        foreach ($userData as $field => $value) {
            if ($field !== 'id') {
                $updates[] = "$field = ?";
                $params[] = $value;
            }
        }
        
        $params[] = $userId;
        $updateStr = implode(', ', $updates);
        
        $stmt = $db->prepare("UPDATE users SET $updateStr WHERE id = ?");
        $stmt->execute($params);
        
        return ['success' => true, 'message' => 'User updated successfully'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to update user'];
    }
}

// Connect to database
$db = connectDB($dbConfig);

// Handle AJAX requests
// Example input: POST request with action and user data
// Example output: JSON response with status and message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => 'Invalid action'];
    
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    
    switch ($action) {
        case 'create':
            $response = createUser($db, $data['user']);
            break;
            
        case 'update':
            $response = updateUser($db, $data['user']['id'], $data['user']);
            break;
    }
    
    echo json_encode($response);
    exit;
}

// Get users for current page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$userData = $db ? getUsers($db, $page) : ['users' => [], 'total' => 0];
$totalPages = ceil($userData['total'] / 10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin Dashboard</title>
    <style>
        /* Inherit dashboard styles */
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #2196F3;
            --danger-color: #f44336;
            --success-color: #4CAF50;
            --warning-color: #ff9800;
            --text-color: #333;
            --bg-color: #f5f5f5;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar styling */
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
        }
        
        .sidebar h2 {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #555;
        }
        
        .nav-item {
            padding: 10px;
            margin: 5px 0;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .nav-item:hover {
            background-color: #555;
        }
        
        /* Main content styling */
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        /* User table styling */
        .user-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 20px;
        }
        
        .user-table th,
        .user-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .user-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        /* Form styling */
        .user-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            background-color: var(--primary-color);
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        /* Pagination styling */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 10px;
        }
        
        .pagination a {
            padding: 8px 12px;
            background: white;
            border-radius: 4px;
            text-decoration: none;
            color: var(--text-color);
        }
        
        .pagination a.active {
            background: var(--primary-color);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar navigation -->
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <div class="nav-item" onclick="location.href='admin_dashboard.php'">Dashboard</div>
            <div class="nav-item" onclick="location.href='user_management.php'">Users</div>
            <div class="nav-item" onclick="location.href='product_management.php'">Products</div>
            <div class="nav-item" onclick="location.href='order_management.php'">Orders</div>
            <div class="nav-item" onclick="location.href='analytics.php'">Analytics</div>
        </div>
        
        <!-- Main content area -->
        <div class="main-content">
            <h1>User Management</h1>
            
            <!-- User creation form -->
            <div class="user-form">
                <h2>Create New User</h2>
                <form id="createUserForm" onsubmit="return handleSubmit(event)">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn">Create User</button>
                </form>
            </div>
            
            <!-- Users table -->
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userData['users'] as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td><?php echo htmlspecialchars($user['status']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <button class="btn" onclick="editUser(<?php echo $user['id']; ?>)">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($userData['users'])): ?>
                        <tr>
                            <td colspan="7">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" 
                           class="<?php echo $page === $i ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Function to handle form submission
        // Example: Submit form data via AJAX
        async function handleSubmit(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const userData = {};
            
            formData.forEach((value, key) => {
                userData[key] = value;
            });
            
            try {
                const response = await fetch('user_management.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        action: 'create',
                        user: userData
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert(data.message);
                    form.reset();
                    location.reload();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
            }
            
            return false;
        }
        
        // Function to edit user
        // Example: Open edit form for user with ID 1
        function editUser(userId) {
            // Implement edit functionality
            alert('Edit user ' + userId);
        }
    </script>
</body>
</html>